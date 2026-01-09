from fastapi import FastAPI, APIRouter, HTTPException, Depends, Query, Request, Response
from fastapi.security import HTTPBearer, HTTPAuthorizationCredentials
from dotenv import load_dotenv
from starlette.middleware.cors import CORSMiddleware
from motor.motor_asyncio import AsyncIOMotorClient
import os
import logging
import hashlib
import hmac
import secrets
import random
from pathlib import Path
from pydantic import BaseModel, Field
from typing import List, Optional, Dict, Any
import uuid
from datetime import datetime, timezone, timedelta
import jwt
import httpx
import json
from decimal import Decimal, ROUND_DOWN

ROOT_DIR = Path(__file__).parent
load_dotenv(ROOT_DIR / '.env')

# MongoDB connection
mongo_url = os.environ.get('MONGO_URL', 'mongodb://localhost:27017')
client = AsyncIOMotorClient(mongo_url)
db = client[os.environ.get('DB_NAME', 'easymoney')]

# Security
SECRET_KEY = os.environ.get('SECRET_KEY', secrets.token_hex(32))
ADMIN_PASSWORD = os.environ.get('ADMIN_PASSWORD', 'easymoney2025admin')
TELEGRAM_BOT_TOKEN = os.environ.get('TELEGRAM_BOT_TOKEN', '8271361408:AAGzA--uL8Wrs4OJjcwYNUaYc7VkPqDHSlg')
ALGORITHM = "HS256"
security = HTTPBearer(auto_error=False)

# Slots API Configuration
SLOTS_API_URL = "https://int.apiforb2b.com"
OPERATOR_ID = os.environ.get('SLOTS_OPERATOR_ID', '40084')
YT_OPERATOR_ID = os.environ.get('SLOTS_YT_OPERATOR_ID', '40085')

MAX_BET = 1000000

app = FastAPI(title="EASY MONEY Gaming Platform")
api_router = APIRouter(prefix="/api")

logging.basicConfig(level=logging.INFO)
logger = logging.getLogger(__name__)

# ================== HELPERS ==================

def round_money(value: float) -> float:
    return float(Decimal(str(value)).quantize(Decimal('0.01'), rounding=ROUND_DOWN))

def create_token(user_id: str) -> str:
    expire = datetime.now(timezone.utc) + timedelta(days=30)
    return jwt.encode({"sub": user_id, "exp": expire}, SECRET_KEY, algorithm=ALGORITHM)

def generate_api_token():
    return secrets.token_hex(30)

async def get_current_user(credentials: HTTPAuthorizationCredentials = Depends(security)):
    if not credentials:
        raise HTTPException(status_code=401, detail="Требуется авторизация")
    try:
        payload = jwt.decode(credentials.credentials, SECRET_KEY, algorithms=[ALGORITHM])
        user_id = payload.get("sub")
        if not user_id:
            raise HTTPException(status_code=401, detail="Неверный токен")
        user = await db.users.find_one({"id": user_id}, {"_id": 0})
        if not user:
            raise HTTPException(status_code=401, detail="Пользователь не найден")
        return user
    except jwt.ExpiredSignatureError:
        raise HTTPException(status_code=401, detail="Токен истек")
    except jwt.JWTError:
        raise HTTPException(status_code=401, detail="Неверный токен")

async def verify_admin_token(credentials: HTTPAuthorizationCredentials = Depends(security)):
    if not credentials:
        raise HTTPException(status_code=401, detail="Требуется авторизация")
    try:
        payload = jwt.decode(credentials.credentials, SECRET_KEY, algorithms=[ALGORITHM])
        if not payload.get("admin"):
            raise HTTPException(status_code=403, detail="Доступ запрещен")
        return True
    except:
        raise HTTPException(status_code=401, detail="Неверный токен")

async def get_settings():
    settings = await db.settings.find_one({"id": "main"}, {"_id": 0})
    if not settings:
        settings = {
            "id": "main", "raceback_percent": 10, "ref_percent": 50, "min_withdraw": 100,
            "dice_rtp": 97, "mines_rtp": 97, "bubbles_rtp": 97, "wheel_rtp": 97, "slots_rtp": 97, "crash_rtp": 97,
            "dice_bank": 10000, "mines_bank": 10000, "bubbles_bank": 10000, "wheel_bank": 10000
        }
        await db.settings.insert_one(settings)
    return settings

async def update_bank(game: str, status: str, amount: float, user: dict):
    if user.get("is_youtuber"):
        return
    if status == "win":
        await db.settings.update_one({"id": "main"}, {"$inc": {f"{game}_bank": -amount}})
    else:
        await db.settings.update_one({"id": "main"}, {"$inc": {f"{game}_bank": amount * 0.75}})

async def calculate_raceback(user_id: str, bet: float):
    settings = await get_settings()
    percent = settings.get("raceback_percent", 10) / 100
    raceback_amount = round_money(bet * percent)
    await db.users.update_one({"id": user_id}, {"$inc": {"raceback": raceback_amount}})

async def add_ref_bonus(user: dict, deposit_amount: float):
    if not user.get("invited_by"):
        return
    settings = await get_settings()
    percent = settings.get("ref_percent", 50) / 100
    bonus = round_money(deposit_amount * percent)
    await db.users.update_one({"id": user["invited_by"]}, {"$inc": {"income": bonus, "income_all": bonus}})

def should_player_win(rtp: float, user: dict) -> bool:
    if user.get("is_youtuber"):
        return random.random() < 0.8
    if user.get("is_drain"):
        drain_chance = user.get("is_drain_chance", 20)
        if random.randint(1, 100) <= drain_chance:
            return False
    return random.random() * 100 < rtp

# ================== STARTUP ==================

@app.on_event("startup")
async def startup():
    await get_settings()
    await init_slots_from_api()
    logger.info("EASY MONEY Gaming Platform started")

@app.on_event("shutdown")
async def shutdown():
    client.close()

async def init_slots_from_api():
    """Fetch slots from external API and cache them"""
    try:
        slots_count = await db.slots.count_documents({})
        if slots_count > 0:
            return
        
        # Try to fetch from API
        async with httpx.AsyncClient(timeout=30) as client_http:
            url = f"{SLOTS_API_URL}/frontendsrv/apihandler.api?cmd={{%22api%22:%22ls-games-by-operator-id-get%22,%22operator_id%22:%22{OPERATOR_ID}%22}}"
            response = await client_http.get(url)
            if response.status_code == 200:
                data = response.json()
                if data.get("locator", {}).get("groups"):
                    slots = []
                    providers = []
                    for group in data["locator"]["groups"]:
                        provider_id = group["gr_id"]
                        provider_name = group["gr_title"]
                        if not any(p["id"] == provider_id for p in providers):
                            providers.append({"id": provider_id, "name": provider_name, "logo": f"/assets/providers/{provider_id}.png"})
                        
                        for game in group.get("games", []):
                            icon_name = game.get("icons", [{}])[0].get("ic_name", "")
                            slots.append({
                                "id": game["gm_bk_id"],
                                "name": game["gm_title"],
                                "provider": provider_id,
                                "provider_name": provider_name,
                                "image": f"https://int.apiforb2b.com/game/icons/{icon_name}" if icon_name else "",
                                "active": True,
                                "created_at": datetime.now(timezone.utc).isoformat()
                            })
                    
                    if slots:
                        await db.slots.insert_many(slots)
                        await db.providers.insert_many(providers)
                        logger.info(f"Loaded {len(slots)} slots from API")
                        return
    except Exception as e:
        logger.warning(f"Failed to fetch slots from API: {e}")
    
    # Fallback to sample slots
    await init_sample_slots()

async def init_sample_slots():
    """Initialize with sample slot data"""
    providers = [
        {"id": "pragmatic", "name": "Pragmatic Play"},
        {"id": "netent", "name": "NetEnt"},
        {"id": "playngo", "name": "Play'n GO"},
        {"id": "egt", "name": "EGT"},
        {"id": "amatic", "name": "Amatic"},
    ]
    
    slots = [
        {"id": "vs20olympgate", "name": "Gates of Olympus", "provider": "pragmatic", "image": "https://cdn.softswiss.net/i/s2/pragmaticexternal/vs20olympgate.png"},
        {"id": "vs20fruitsw", "name": "Sweet Bonanza", "provider": "pragmatic", "image": "https://cdn.softswiss.net/i/s2/pragmaticexternal/vs20fruitsw.png"},
        {"id": "vs10bbbonanza", "name": "Big Bass Bonanza", "provider": "pragmatic", "image": "https://cdn.softswiss.net/i/s2/pragmaticexternal/vs10bbbonanza.png"},
        {"id": "vs20doghouse", "name": "The Dog House", "provider": "pragmatic", "image": "https://cdn.softswiss.net/i/s2/pragmaticexternal/vs20doghouse.png"},
        {"id": "vs25wolfgold", "name": "Wolf Gold", "provider": "pragmatic", "image": "https://cdn.softswiss.net/i/s2/pragmaticexternal/vs25wolfgold.png"},
        {"id": "vs20sugarrush", "name": "Sugar Rush", "provider": "pragmatic", "image": "https://cdn.softswiss.net/i/s2/pragmaticexternal/vs20sugarrush.png"},
        {"id": "vs20starlightx", "name": "Starlight Princess", "provider": "pragmatic", "image": "https://cdn.softswiss.net/i/s2/pragmaticexternal/vs20starlightx.png"},
        {"id": "BookofDead", "name": "Book of Dead", "provider": "playngo", "image": "https://cdn.softswiss.net/i/s2/playngo/BookofDead.png"},
        {"id": "Reactoonz", "name": "Reactoonz", "provider": "playngo", "image": "https://cdn.softswiss.net/i/s2/playngo/Reactoonz.png"},
        {"id": "starburst", "name": "Starburst", "provider": "netent", "image": "https://cdn.softswiss.net/i/s2/netent/starburst.png"},
        {"id": "gonzos_quest", "name": "Gonzo's Quest", "provider": "netent", "image": "https://cdn.softswiss.net/i/s2/netent/gonzos_quest.png"},
        {"id": "40BurningHot", "name": "40 Burning Hot", "provider": "egt", "image": "https://cdn.softswiss.net/i/s2/egt/40BurningHot.png"},
    ]
    
    await db.providers.delete_many({})
    await db.providers.insert_many(providers)
    
    for slot in slots:
        slot["active"] = True
        slot["created_at"] = datetime.now(timezone.utc).isoformat()
    await db.slots.insert_many(slots)
    logger.info(f"Loaded {len(slots)} sample slots")

# ================== AUTH ==================

@api_router.post("/auth/telegram")
async def telegram_auth(request: Request):
    data = await request.json()
    client_ip = request.headers.get("x-forwarded-for", request.client.host if request.client else "unknown")
    
    user = await db.users.find_one({"telegram_id": data.get("id")}, {"_id": 0})
    
    if user:
        await db.users.update_one({"telegram_id": data.get("id")}, {"$set": {
            "name": f"{data.get('first_name', '')} {data.get('last_name', '')}".strip(),
            "username": data.get("username", ""),
            "img": data.get("photo_url", "/logo.png"),
            "last_login": datetime.now(timezone.utc).isoformat(),
            "last_ip": client_ip
        }})
        user = await db.users.find_one({"telegram_id": data.get("id")}, {"_id": 0})
    else:
        user_id = str(uuid.uuid4())
        ref_link = secrets.token_hex(5)
        invited_by = None
        ref_code = data.get("ref_code")
        if ref_code:
            inviter = await db.users.find_one({"ref_link": ref_code}, {"_id": 0})
            if inviter:
                invited_by = inviter["id"]
                await db.users.update_one({"id": inviter["id"]}, {"$inc": {"referalov": 1}})
        
        user = {
            "id": user_id, "telegram_id": data.get("id"), 
            "username": data.get("username", ""),
            "name": f"{data.get('first_name', '')} {data.get('last_name', '')}".strip(),
            "img": data.get("photo_url", "/logo.png"),
            "balance": 0.0, "deposit": 0.0, "raceback": 0.0, "referalov": 0,
            "income": 0.0, "income_all": 0.0, "ref_link": ref_link, "invited_by": invited_by,
            "is_admin": False, "is_ban": False, "is_ban_comment": None,
            "is_youtuber": False, "is_drain": False, "is_drain_chance": 20.0, "wager": 0.0,
            "api_token": generate_api_token(), "game_token": generate_api_token(),
            "register_ip": client_ip, "last_ip": client_ip,
            "created_at": datetime.now(timezone.utc).isoformat(),
            "last_login": datetime.now(timezone.utc).isoformat()
        }
        await db.users.insert_one(user)
        user = await db.users.find_one({"telegram_id": data.get("id")}, {"_id": 0})
    
    return {"success": True, "token": create_token(user["id"]), "user": user}

@api_router.post("/auth/demo")
async def demo_auth(request: Request, username: str = "demo_user", ref_code: Optional[str] = None):
    client_ip = request.headers.get("x-forwarded-for", request.client.host if request.client else "unknown")
    user = await db.users.find_one({"username": username}, {"_id": 0})
    
    if not user:
        user_id = str(uuid.uuid4())
        ref_link = secrets.token_hex(5)
        invited_by = None
        if ref_code:
            inviter = await db.users.find_one({"ref_link": ref_code}, {"_id": 0})
            if inviter:
                invited_by = inviter["id"]
                await db.users.update_one({"id": inviter["id"]}, {"$inc": {"referalov": 1}})
        
        user = {
            "id": user_id, "telegram_id": random.randint(100000000, 999999999),
            "username": username, "name": username, "img": "/logo.png",
            "balance": 1000.0, "deposit": 0.0, "raceback": 0.0, "referalov": 0,
            "income": 0.0, "income_all": 0.0, "ref_link": ref_link, "invited_by": invited_by,
            "is_admin": False, "is_ban": False, "is_ban_comment": None,
            "is_youtuber": False, "is_drain": False, "is_drain_chance": 20.0, "wager": 0.0,
            "api_token": generate_api_token(), "game_token": generate_api_token(),
            "register_ip": client_ip, "last_ip": client_ip,
            "created_at": datetime.now(timezone.utc).isoformat(),
            "last_login": datetime.now(timezone.utc).isoformat()
        }
        await db.users.insert_one(user)
        user = await db.users.find_one({"id": user_id}, {"_id": 0})
    
    return {"success": True, "token": create_token(user["id"]), "user": user}

@api_router.get("/auth/me")
async def get_me(user: dict = Depends(get_current_user)):
    return {"success": True, "user": user}

# ================== GAMES - MINES ==================

def get_mines_coefficient(bombs: int, opened: int) -> float:
    coeff = 1.0
    for i in range(opened):
        coeff *= (25 - i) / (25 - bombs - i)
    return round(coeff, 2)

@api_router.post("/games/mines/play")
async def mines_play(request: Request, user: dict = Depends(get_current_user)):
    data = await request.json()
    if user.get("is_ban"):
        raise HTTPException(status_code=403, detail="Ваш аккаунт заблокирован")
    
    active_game = await db.mines_games.find_one({"user_id": user["id"], "active": True}, {"_id": 0})
    if active_game:
        raise HTTPException(status_code=400, detail="У вас есть активная игра")
    
    bet = min(float(data.get("bet", 10)), user["balance"], MAX_BET)
    bombs = int(data.get("bombs", 5))
    if bet < 1:
        raise HTTPException(status_code=400, detail="Недостаточно средств")
    
    await db.users.update_one({"id": user["id"]}, {"$inc": {"balance": -bet, "wager": -bet}})
    
    all_positions = list(range(1, 26))
    random.shuffle(all_positions)
    mines_positions = all_positions[:bombs]
    
    game = {
        "id": str(uuid.uuid4()), "user_id": user["id"], "bet": bet, "bombs": bombs,
        "mines": mines_positions, "clicked": [], "win": 0.0, "active": True,
        "created_at": datetime.now(timezone.utc).isoformat()
    }
    await db.mines_games.insert_one(game)
    
    return {"success": True, "balance": round_money(user["balance"] - bet), "game_id": game["id"]}

@api_router.post("/games/mines/press")
async def mines_press(request: Request, user: dict = Depends(get_current_user)):
    data = await request.json()
    cell = int(data.get("cell", 1))
    
    game = await db.mines_games.find_one({"user_id": user["id"], "active": True}, {"_id": 0})
    if not game:
        raise HTTPException(status_code=400, detail="У вас нет активных игр")
    
    if cell in game["clicked"]:
        raise HTTPException(status_code=400, detail="Вы уже нажали на эту ячейку")
    
    settings = await get_settings()
    rtp = settings.get("mines_rtp", 97)
    clicked = game["clicked"] + [cell]
    
    current_coeff = get_mines_coefficient(game["bombs"], len(clicked))
    potential_win = round_money(game["bet"] * current_coeff)
    
    hit_mine = cell in game["mines"]
    
    if not hit_mine and not user.get("is_youtuber"):
        bank = settings.get("mines_bank", 10000)
        if potential_win > bank or not should_player_win(rtp, user):
            other_clicked = [c for c in clicked if c != cell]
            available = [i for i in range(1, 26) if i not in other_clicked]
            random.shuffle(available)
            new_mines = [cell] + [p for p in available if p != cell][:game["bombs"]-1]
            game["mines"] = new_mines
            hit_mine = True
            await db.mines_games.update_one({"id": game["id"]}, {"$set": {"mines": new_mines}})
    
    if hit_mine:
        await db.mines_games.update_one({"id": game["id"]}, {"$set": {"active": False, "clicked": clicked, "win": 0}})
        await update_bank("mines", "lose", game["bet"], user)
        await calculate_raceback(user["id"], game["bet"])
        return {"success": True, "status": "lose", "cell": cell, "mines": game["mines"]}
    else:
        coeff = get_mines_coefficient(game["bombs"], len(clicked))
        win = round_money(game["bet"] * coeff)
        await db.mines_games.update_one({"id": game["id"]}, {"$set": {"clicked": clicked, "win": win}})
        
        if len(clicked) == 25 - game["bombs"]:
            await db.mines_games.update_one({"id": game["id"]}, {"$set": {"active": False}})
            await db.users.update_one({"id": user["id"]}, {"$inc": {"balance": win}})
            await update_bank("mines", "win", win - game["bet"], user)
            user_data = await db.users.find_one({"id": user["id"]}, {"_id": 0})
            return {"success": True, "status": "finish", "win": win, "coefficient": coeff, "balance": user_data["balance"], "mines": game["mines"]}
        
        return {"success": True, "status": "continue", "win": win, "coefficient": coeff, "clicked": clicked}

@api_router.post("/games/mines/take")
async def mines_take(user: dict = Depends(get_current_user)):
    game = await db.mines_games.find_one({"user_id": user["id"], "active": True}, {"_id": 0})
    if not game:
        raise HTTPException(status_code=400, detail="У вас нет активных игр")
    if not game["clicked"]:
        raise HTTPException(status_code=400, detail="Сделайте хотя бы один клик")
    
    win = game["win"]
    if win <= 0:
        raise HTTPException(status_code=400, detail="Нечего забирать")
    
    await db.mines_games.update_one({"id": game["id"]}, {"$set": {"active": False}})
    await db.users.update_one({"id": user["id"]}, {"$inc": {"balance": win}})
    await update_bank("mines", "win", win - game["bet"], user)
    
    user_data = await db.users.find_one({"id": user["id"]}, {"_id": 0})
    return {"success": True, "win": win, "balance": user_data["balance"], "mines": game["mines"]}

@api_router.get("/games/mines/current")
async def mines_current(user: dict = Depends(get_current_user)):
    game = await db.mines_games.find_one({"user_id": user["id"], "active": True}, {"_id": 0})
    if game:
        return {"success": True, "active": True, "win": game["win"], "clicked": game["clicked"], "bet": game["bet"], "bombs": game["bombs"]}
    return {"success": True, "active": False}

# ================== GAMES - DICE ==================

@api_router.post("/games/dice/play")
async def dice_play(request: Request, user: dict = Depends(get_current_user)):
    data = await request.json()
    if user.get("is_ban"):
        raise HTTPException(status_code=403, detail="Ваш аккаунт заблокирован")
    
    bet = min(float(data.get("bet", 10)), user["balance"], MAX_BET)
    chance = float(data.get("chance", 50))
    direction = data.get("direction", "down")
    
    if bet < 1:
        raise HTTPException(status_code=400, detail="Недостаточно средств")
    
    settings = await get_settings()
    rtp = settings.get("dice_rtp", 97)
    
    rand_num = random.randint(1, 1000000)
    threshold = int((chance / 100) * 999999)
    
    if direction == "down":
        is_win = rand_num < threshold
    else:
        is_win = rand_num > (999999 - threshold)
    
    coefficient = round(100 / chance, 2)
    potential_win = round_money(bet * coefficient)
    
    if is_win and not user.get("is_youtuber"):
        bank = settings.get("dice_bank", 10000)
        if potential_win - bet > bank or not should_player_win(rtp, user):
            is_win = False
            if direction == "down":
                rand_num = random.randint(threshold, 1000000)
            else:
                rand_num = random.randint(0, 999999 - threshold)
    
    if is_win:
        win = potential_win
        balance_change = win - bet
        await update_bank("dice", "win", win - bet, user)
    else:
        win = 0
        balance_change = -bet
        await update_bank("dice", "lose", bet, user)
        await calculate_raceback(user["id"], bet)
    
    await db.users.update_one({"id": user["id"]}, {"$inc": {"balance": balance_change, "wager": -bet}})
    
    user_data = await db.users.find_one({"id": user["id"]}, {"_id": 0})
    return {"success": True, "status": "win" if is_win else "lose", "result": rand_num, "win": win, "balance": user_data["balance"], "coefficient": coefficient}

# ================== GAMES - WHEEL ==================

WHEEL_COEFFICIENTS = {1: {"blue": 1.2, "red": 1.5}, 2: {"blue": 1.2, "red": 1.5, "green": 3.0, "pink": 5.0}, 3: {"pink": 49.5}}
WHEEL_ITEMS = {
    1: ["lose"] * 11 + ["red"] * 6 + ["blue"] * 36,
    2: ["lose"] * 26 + ["blue"] * 14 + ["red"] * 9 + ["green"] * 4 + ["pink"] * 2,
    3: ["lose"] * 50 + ["pink"] * 2
}

@api_router.post("/games/wheel/play")
async def wheel_play(request: Request, user: dict = Depends(get_current_user)):
    data = await request.json()
    if user.get("is_ban"):
        raise HTTPException(status_code=403, detail="Ваш аккаунт заблокирован")
    
    bet = min(float(data.get("bet", 10)), user["balance"], MAX_BET)
    level = int(data.get("level", 1))
    
    if bet < 1:
        raise HTTPException(status_code=400, detail="Недостаточно средств")
    
    settings = await get_settings()
    rtp = settings.get("wheel_rtp", 97)
    
    items = WHEEL_ITEMS.get(level, WHEEL_ITEMS[1]).copy()
    random.shuffle(items)
    color = random.choice(items)
    coef = WHEEL_COEFFICIENTS.get(level, {}).get(color, 0)
    total_win = round_money(bet * coef)
    
    if total_win > 0 and not user.get("is_youtuber"):
        bank = settings.get("wheel_bank", 10000)
        if total_win - bet > bank or not should_player_win(rtp, user):
            color = "lose"
            coef = 0
            total_win = 0
    
    profit = total_win - bet
    await db.users.update_one({"id": user["id"]}, {"$inc": {"balance": profit, "wager": -bet}})
    
    if coef > 0:
        await update_bank("wheel", "win", profit, user)
    else:
        await update_bank("wheel", "lose", bet, user)
        await calculate_raceback(user["id"], bet)
    
    position = random.randint(360, 1440)
    
    user_data = await db.users.find_one({"id": user["id"]}, {"_id": 0})
    return {"success": True, "color": color, "coef": coef, "win": total_win, "position": position, "balance": user_data["balance"]}

# ================== GAMES - CRASH ==================

@api_router.post("/games/crash/bet")
async def crash_bet(request: Request, user: dict = Depends(get_current_user)):
    data = await request.json()
    if user.get("is_ban"):
        raise HTTPException(status_code=403, detail="Ваш аккаунт заблокирован")
    
    bet = min(float(data.get("bet", 10)), user["balance"], 10000)
    auto_cashout = float(data.get("auto_cashout", 2.0))
    
    if bet < 1:
        raise HTTPException(status_code=400, detail="Недостаточно средств")
    
    settings = await get_settings()
    rtp = settings.get("crash_rtp", 97)
    
    # Generate crash point
    r = random.random()
    crash_point = 0.99 / (1 - r) if r < 0.99 else 100 + random.random() * 900
    crash_point = round(min(crash_point, 1000), 2)
    
    # Apply RTP adjustment
    if not user.get("is_youtuber") and not should_player_win(rtp, user):
        crash_point = round(random.uniform(1.0, 1.5), 2)
    
    is_win = crash_point >= auto_cashout
    
    if is_win:
        win = round_money(bet * auto_cashout)
        balance_change = win - bet
    else:
        win = 0
        balance_change = -bet
        await calculate_raceback(user["id"], bet)
    
    await db.users.update_one({"id": user["id"]}, {"$inc": {"balance": balance_change, "wager": -bet}})
    
    user_data = await db.users.find_one({"id": user["id"]}, {"_id": 0})
    return {
        "success": True, "status": "win" if is_win else "lose",
        "crash_point": crash_point, "auto_cashout": auto_cashout,
        "win": win, "balance": user_data["balance"]
    }

# ================== SLOTS ==================

@api_router.get("/slots")
async def get_slots(search: str = "", provider: str = "", page: int = 1, limit: int = 30):
    query = {"active": True}
    if search:
        query["name"] = {"$regex": search, "$options": "i"}
    if provider:
        query["provider"] = provider
    
    skip = (page - 1) * limit
    slots = await db.slots.find(query, {"_id": 0}).skip(skip).limit(limit).to_list(limit)
    total = await db.slots.count_documents(query)
    
    return {"success": True, "slots": slots, "total": total, "page": page, "pages": (total + limit - 1) // limit}

@api_router.get("/slots/providers")
async def get_providers():
    providers = await db.providers.find({}, {"_id": 0}).to_list(100)
    return {"success": True, "providers": providers}

@api_router.get("/slots/game/{game_id}")
async def get_slot_game_url(game_id: str, user: dict = Depends(get_current_user)):
    """Get game URL for iframe"""
    if user.get("is_ban"):
        raise HTTPException(status_code=403, detail="Ваш аккаунт заблокирован")
    
    slot = await db.slots.find_one({"id": game_id}, {"_id": 0})
    if not slot:
        raise HTTPException(status_code=404, detail="Игра не найдена")
    
    # Ensure user has api_token
    if not user.get("api_token"):
        api_token = generate_api_token()
        await db.users.update_one({"id": user["id"]}, {"$set": {"api_token": api_token}})
        user["api_token"] = api_token
    
    operator_id = YT_OPERATOR_ID if user.get("is_youtuber") else OPERATOR_ID
    
    game_url = f"{SLOTS_API_URL}/gamesbycode/{game_id}.gamecode?operator_id={operator_id}&language=ru&user_id={user['id']}&auth_token={user['api_token']}&currency=RUB&home_url=/slots"
    
    return {"success": True, "url": game_url, "name": slot["name"], "image": slot.get("image", "")}

@api_router.post("/slots/play/{slot_id}")
async def play_slot(slot_id: str, request: Request, user: dict = Depends(get_current_user)):
    """Simple slot simulation for demo mode"""
    data = await request.json() if request.headers.get("content-type") == "application/json" else {}
    bet = float(data.get("bet", 10))
    
    if user.get("is_ban"):
        raise HTTPException(status_code=403, detail="Ваш аккаунт заблокирован")
    
    bet = min(max(bet, 1), user["balance"], MAX_BET)
    if bet < 1:
        raise HTTPException(status_code=400, detail="Недостаточно средств")
    
    settings = await get_settings()
    rtp = settings.get("slots_rtp", 97)
    
    if should_player_win(rtp, user):
        multiplier = round(random.choices(
            [random.uniform(1.1, 2.0), random.uniform(2.0, 5.0), random.uniform(5.0, 20.0), random.uniform(20.0, 100.0)],
            weights=[60, 25, 10, 5]
        )[0], 2)
        win = round_money(bet * multiplier)
    else:
        multiplier = 0
        win = 0
    
    profit = win - bet
    await db.users.update_one({"id": user["id"]}, {"$inc": {"balance": profit, "wager": -bet}})
    
    if win > 0:
        await update_bank("slots", "win", profit, user)
    else:
        await calculate_raceback(user["id"], bet)
    
    symbols = ["🍒", "🍋", "🍊", "🍇", "⭐", "7️⃣", "💎", "🔔", "🃏", "👑"]
    reels = [[random.choice(symbols) for _ in range(3)] for _ in range(5)]
    
    user_data = await db.users.find_one({"id": user["id"]}, {"_id": 0})
    
    return {
        "success": True, "status": "win" if win > 0 else "lose",
        "reels": reels, "multiplier": multiplier, "win": win, "balance": user_data["balance"]
    }

# ================== SLOTS CALLBACK (for real slot providers) ==================

@api_router.post("/slots/callback")
async def slots_callback(request: Request):
    """Handle callbacks from slot providers"""
    data = await request.json()
    api = data.get("api", "")
    request_data = data.get("data", {})
    
    logger.info(f"Slots callback: {api}")
    
    try:
        if api == "do-auth-user-ingame":
            user = await db.users.find_one({"id": request_data.get("user_id"), "api_token": request_data.get("user_game_token")}, {"_id": 0})
            if not user:
                raise Exception("User not found")
            
            return {
                "success": True, "api": api,
                "answer": {
                    "operator_id": OPERATOR_ID, "user_id": str(user["id"]), "user_nickname": user["name"],
                    "balance": str(user["balance"]), "bonus_balance": "0", "currency": "RUB",
                    "error_code": 0, "error_description": "ok"
                }
            }
        
        elif api == "do-debit-user-ingame":
            user = await db.users.find_one({"id": request_data.get("user_id")}, {"_id": 0})
            if not user:
                raise Exception("User not found")
            
            debit_amount = float(request_data.get("debit_amount", 0))
            if user["balance"] < debit_amount:
                raise Exception("Not enough balance")
            
            await db.users.update_one({"id": user["id"]}, {"$inc": {"balance": -debit_amount}})
            user = await db.users.find_one({"id": user["id"]}, {"_id": 0})
            
            return {
                "success": True, "api": api,
                "answer": {
                    "operator_id": OPERATOR_ID, "transaction_id": request_data.get("transaction_id"),
                    "user_id": str(user["id"]), "user_nickname": user["name"],
                    "balance": str(user["balance"]), "bonus_balance": "0", "currency": "RUB",
                    "error_code": 0, "error_description": "ok"
                }
            }
        
        elif api == "do-credit-user-ingame":
            user = await db.users.find_one({"id": request_data.get("user_id")}, {"_id": 0})
            if not user:
                raise Exception("User not found")
            
            credit_amount = float(request_data.get("credit_amount", 0))
            await db.users.update_one({"id": user["id"]}, {"$inc": {"balance": credit_amount}})
            user = await db.users.find_one({"id": user["id"]}, {"_id": 0})
            
            return {
                "success": True, "api": api,
                "answer": {
                    "operator_id": OPERATOR_ID, "transaction_id": request_data.get("transaction_id"),
                    "user_id": str(user["id"]), "user_nickname": user["name"],
                    "balance": str(user["balance"]), "bonus_balance": "0", "currency": "RUB",
                    "error_code": 0, "error_description": "ok"
                }
            }
        
        else:
            return {"success": True, "api": api, "answer": {"error_code": 0, "error_description": "ok"}}
    
    except Exception as e:
        return {"success": True, "api": api, "answer": {"error_code": 1, "error_description": str(e)}}

# ================== REFERRAL ==================

@api_router.get("/ref/stats")
async def get_ref_stats(user: dict = Depends(get_current_user)):
    return {"success": True, "ref_link": user["ref_link"], "referalov": user["referalov"], "income": user["income"], "income_all": user["income_all"]}

@api_router.post("/ref/withdraw")
async def ref_withdraw(user: dict = Depends(get_current_user)):
    if user["income"] < 10:
        raise HTTPException(status_code=400, detail="Минимум для вывода - 10 рублей")
    income = user["income"]
    await db.users.update_one({"id": user["id"]}, {"$inc": {"balance": income}, "$set": {"income": 0}})
    user_data = await db.users.find_one({"id": user["id"]}, {"_id": 0})
    return {"success": True, "withdrawn": income, "balance": user_data["balance"]}

# ================== RACEBACK ==================

@api_router.get("/bonus/raceback")
async def get_raceback(user: dict = Depends(get_current_user)):
    return {"success": True, "raceback": user["raceback"]}

@api_router.post("/bonus/raceback/claim")
async def claim_raceback(user: dict = Depends(get_current_user)):
    if user["balance"] > 0:
        raise HTTPException(status_code=400, detail="Кешбэк доступен только при нулевом балансе")
    if user["raceback"] < 1:
        raise HTTPException(status_code=400, detail="Недостаточно кешбэка")
    raceback = user["raceback"]
    await db.users.update_one({"id": user["id"]}, {"$inc": {"balance": raceback}, "$set": {"raceback": 0}})
    user_data = await db.users.find_one({"id": user["id"]}, {"_id": 0})
    return {"success": True, "claimed": raceback, "balance": user_data["balance"]}

# ================== PAYMENTS ==================

@api_router.post("/payment/create")
async def create_payment(request: Request, user: dict = Depends(get_current_user)):
    data = await request.json()
    payment = {
        "id": str(uuid.uuid4()), "user_id": user["id"], "amount": float(data.get("amount", 100)),
        "system": data.get("system", "mock"), "promo_code": data.get("promo_code"), "status": "pending",
        "created_at": datetime.now(timezone.utc).isoformat()
    }
    await db.payments.insert_one(payment)
    return {"success": True, "payment_id": payment["id"], "link": f"/payment/mock/{payment['id']}", "message": "Платежная система в тестовом режиме"}

@api_router.post("/payment/mock/complete/{payment_id}")
async def complete_mock_payment(payment_id: str):
    payment = await db.payments.find_one({"id": payment_id}, {"_id": 0})
    if not payment:
        raise HTTPException(status_code=404, detail="Платеж не найден")
    if payment["status"] != "pending":
        raise HTTPException(status_code=400, detail="Платеж уже обработан")
    
    user = await db.users.find_one({"id": payment["user_id"]}, {"_id": 0})
    
    bonus = 0
    wager_mult = 3
    if payment.get("promo_code"):
        promo = await db.promos.find_one({"name": payment["promo_code"], "status": False}, {"_id": 0})
        if promo and promo.get("limited", 0) < promo.get("limit", 0):
            if promo.get("type") == 1:
                bonus = payment["amount"] * (promo.get("bonus_percent", 0) / 100)
            else:
                bonus = promo.get("reward", 0)
            wager_mult = promo.get("wager_multiplier", 3)
            await db.promos.update_one({"id": promo["id"]}, {"$inc": {"limited": 1}})
    
    total_amount = payment["amount"] + bonus
    wager = payment["amount"] * wager_mult
    
    await db.users.update_one({"id": user["id"]}, {"$inc": {"balance": total_amount, "deposit": payment["amount"], "wager": wager}})
    await db.payments.update_one({"id": payment_id}, {"$set": {"status": "completed", "bonus": bonus}})
    await add_ref_bonus(user, payment["amount"])
    
    return {"success": True, "amount": total_amount, "bonus": bonus}

@api_router.get("/payment/history")
async def payment_history(user: dict = Depends(get_current_user)):
    payments = await db.payments.find({"user_id": user["id"]}, {"_id": 0}).sort("created_at", -1).limit(20).to_list(20)
    return {"success": True, "payments": payments}

# ================== WITHDRAWALS ==================

@api_router.post("/withdraw/create")
async def create_withdraw(request: Request, user: dict = Depends(get_current_user)):
    data = await request.json()
    settings = await get_settings()
    min_withdraw = settings.get("min_withdraw", 100)
    amount = float(data.get("amount", 100))
    
    if amount < min_withdraw:
        raise HTTPException(status_code=400, detail=f"Минимальная сумма вывода: {min_withdraw}")
    if user["balance"] < amount:
        raise HTTPException(status_code=400, detail="Недостаточно средств")
    if user["wager"] > 0:
        raise HTTPException(status_code=400, detail=f"Необходимо отыграть вейджер: {user['wager']:.2f}")
    
    await db.users.update_one({"id": user["id"]}, {"$inc": {"balance": -amount}})
    
    withdraw = {
        "id": str(uuid.uuid4()), "user_id": user["id"], "amount": amount,
        "wallet": data.get("wallet", ""), "system": data.get("system", ""), "status": "pending",
        "created_at": datetime.now(timezone.utc).isoformat()
    }
    await db.withdraws.insert_one(withdraw)
    return {"success": True, "withdraw_id": withdraw["id"]}

@api_router.get("/withdraw/history")
async def withdraw_history(user: dict = Depends(get_current_user)):
    withdraws = await db.withdraws.find({"user_id": user["id"]}, {"_id": 0}).sort("created_at", -1).limit(20).to_list(20)
    return {"success": True, "withdraws": withdraws}

# ================== PROMO ==================

@api_router.post("/promo/activate")
async def activate_promo(request: Request, user: dict = Depends(get_current_user)):
    data = await request.json()
    code = data.get("code", "")
    
    promo = await db.promos.find_one({"name": code, "status": False}, {"_id": 0})
    if not promo:
        raise HTTPException(status_code=404, detail="Промокод не найден")
    if promo.get("limited", 0) >= promo.get("limit", 0):
        raise HTTPException(status_code=400, detail="Промокод исчерпан")
    
    used = await db.promo_logs.find_one({"user_id": user["id"], "promo_id": promo["id"]})
    if used:
        raise HTTPException(status_code=400, detail="Вы уже использовали этот промокод")
    
    if promo.get("deposit_required") and user["deposit"] == 0:
        raise HTTPException(status_code=400, detail="Промокод доступен только после депозита")
    
    reward = promo.get("reward", 0)
    wager = reward * promo.get("wager_multiplier", 3) if promo.get("type") != 3 else 0
    
    await db.users.update_one({"id": user["id"]}, {"$inc": {"balance": reward, "wager": wager}})
    await db.promos.update_one({"id": promo["id"]}, {"$inc": {"limited": 1}})
    await db.promo_logs.insert_one({
        "id": str(uuid.uuid4()), "user_id": user["id"], "promo_id": promo["id"],
        "reward": reward, "created_at": datetime.now(timezone.utc).isoformat()
    })
    
    user_data = await db.users.find_one({"id": user["id"]}, {"_id": 0})
    return {"success": True, "reward": reward, "balance": user_data["balance"], "wager": wager}

# ================== HISTORY ==================

@api_router.get("/history/recent")
async def get_recent_history(limit: int = Query(default=10, le=50)):
    history = []
    for coll, game_name in [("dice_games", "dice"), ("mines_games", "mines"), ("wheel_games", "wheel")]:
        query = {"active": False} if coll == "mines_games" else {}
        games = await db[coll].find(query, {"_id": 0}).sort("created_at", -1).limit(limit).to_list(limit)
        for g in games:
            user = await db.users.find_one({"id": g["user_id"]}, {"_id": 0, "name": 1})
            if user:
                coef = g.get("coef", g.get("target", round(g.get("win", 0) / g.get("bet", 1), 2) if g.get("bet") else 0))
                history.append({
                    "game": game_name, "name": user["name"], "bet": g.get("bet", 0), "coefficient": coef,
                    "win": g.get("win", 0), "status": "win" if g.get("win", 0) > 0 else "lose", "created_at": g.get("created_at")
                })
    history.sort(key=lambda x: x["created_at"], reverse=True)
    return {"success": True, "history": history[:limit]}

# ================== SOCIAL ==================

@api_router.get("/social")
async def get_social():
    return {"success": True, "social": {"telegram": "https://t.me/easymoneycaspro", "bot": "https://t.me/Irjeukdnr_bot"}}

# ================== ADMIN ==================

@api_router.post("/admin/login")
async def admin_login(request: Request):
    data = await request.json()
    if data.get("password") != ADMIN_PASSWORD:
        raise HTTPException(status_code=401, detail="Неверный пароль")
    admin_token = jwt.encode({"admin": True, "exp": datetime.now(timezone.utc) + timedelta(hours=24)}, SECRET_KEY, algorithm=ALGORITHM)
    return {"success": True, "token": admin_token}

@api_router.get("/admin/stats")
async def admin_stats(_: bool = Depends(verify_admin_token)):
    today = datetime.now(timezone.utc).replace(hour=0, minute=0, second=0, microsecond=0)
    week_ago = today - timedelta(days=7)
    
    all_payments = await db.payments.find({"status": "completed"}, {"_id": 0}).to_list(10000)
    payment_today = sum(p["amount"] for p in all_payments if p["created_at"] >= today.isoformat())
    payment_week = sum(p["amount"] for p in all_payments if p["created_at"] >= week_ago.isoformat())
    payment_all = sum(p["amount"] for p in all_payments)
    
    pending_withdraws = await db.withdraws.find({"status": "pending"}, {"_id": 0}).to_list(1000)
    
    users_all = await db.users.count_documents({})
    users_today = await db.users.count_documents({"created_at": {"$gte": today.isoformat()}})
    
    settings = await get_settings()
    
    return {
        "success": True,
        "payments": {"today": payment_today, "week": payment_week, "all": payment_all},
        "withdrawals": {"pending_count": len(pending_withdraws), "pending_sum": sum(w["amount"] for w in pending_withdraws)},
        "users": {"today": users_today, "all": users_all},
        "settings": settings
    }

@api_router.get("/admin/users")
async def admin_users(search: Optional[str] = None, page: int = 1, limit: int = 20, _: bool = Depends(verify_admin_token)):
    query = {}
    if search:
        query = {"$or": [{"name": {"$regex": search, "$options": "i"}}, {"username": {"$regex": search, "$options": "i"}}]}
    skip = (page - 1) * limit
    users = await db.users.find(query, {"_id": 0}).sort("created_at", -1).skip(skip).limit(limit).to_list(limit)
    total = await db.users.count_documents(query)
    return {"success": True, "users": users, "total": total, "page": page, "pages": (total + limit - 1) // limit}

@api_router.put("/admin/user")
async def admin_update_user(request: Request, _: bool = Depends(verify_admin_token)):
    data = await request.json()
    user_id = data.pop("user_id", None)
    if user_id and data:
        await db.users.update_one({"id": user_id}, {"$set": data})
    return {"success": True}

@api_router.put("/admin/rtp")
async def admin_update_rtp(request: Request, _: bool = Depends(verify_admin_token)):
    data = await request.json()
    update_data = {k: v for k, v in data.items() if v is not None and k.endswith("_rtp")}
    if update_data:
        await db.settings.update_one({"id": "main"}, {"$set": update_data})
    return {"success": True}

@api_router.get("/admin/settings")
async def admin_get_settings(_: bool = Depends(verify_admin_token)):
    settings = await get_settings()
    return {"success": True, "settings": settings}

@api_router.put("/admin/settings")
async def admin_update_settings(request: Request, _: bool = Depends(verify_admin_token)):
    data = await request.json()
    update_data = {k: v for k, v in data.items() if v is not None}
    if update_data:
        await db.settings.update_one({"id": "main"}, {"$set": update_data})
    return {"success": True}

@api_router.get("/admin/promos")
async def admin_promos(page: int = 1, limit: int = 20, _: bool = Depends(verify_admin_token)):
    skip = (page - 1) * limit
    promos = await db.promos.find({}, {"_id": 0}).sort("created_at", -1).skip(skip).limit(limit).to_list(limit)
    total = await db.promos.count_documents({})
    return {"success": True, "promos": promos, "total": total}

@api_router.post("/admin/promo")
async def admin_create_promo(request: Request, _: bool = Depends(verify_admin_token)):
    data = await request.json()
    existing = await db.promos.find_one({"name": data.get("name")})
    if existing:
        raise HTTPException(status_code=400, detail="Промокод уже существует")
    
    promo = {
        "id": str(uuid.uuid4()), "name": data.get("name"), "reward": float(data.get("reward", 0)),
        "limit": int(data.get("limit", 100)), "limited": 0, "type": int(data.get("type", 0)),
        "deposit_required": data.get("deposit_required", False),
        "wager_multiplier": float(data.get("wager_multiplier", 3)),
        "bonus_percent": float(data.get("bonus_percent", 0)), "status": False,
        "created_at": datetime.now(timezone.utc).isoformat()
    }
    await db.promos.insert_one(promo)
    return {"success": True, "promo": promo}

@api_router.get("/admin/withdraws")
async def admin_withdraws(status: str = "pending", page: int = 1, limit: int = 20, _: bool = Depends(verify_admin_token)):
    skip = (page - 1) * limit
    withdraws = await db.withdraws.find({"status": status}, {"_id": 0}).sort("created_at", -1).skip(skip).limit(limit).to_list(limit)
    for w in withdraws:
        user = await db.users.find_one({"id": w["user_id"]}, {"_id": 0, "name": 1, "balance": 1})
        if user:
            w["user_name"] = user["name"]
            w["user_balance"] = user["balance"]
    total = await db.withdraws.count_documents({"status": status})
    return {"success": True, "withdraws": withdraws, "total": total}

@api_router.put("/admin/withdraw/{withdraw_id}")
async def admin_update_withdraw(withdraw_id: str, request: Request, _: bool = Depends(verify_admin_token)):
    data = await request.json()
    status = data.get("status", "")
    withdraw = await db.withdraws.find_one({"id": withdraw_id}, {"_id": 0})
    if not withdraw:
        raise HTTPException(status_code=404, detail="Вывод не найден")
    if status == "rejected":
        await db.users.update_one({"id": withdraw["user_id"]}, {"$inc": {"balance": withdraw["amount"]}})
    await db.withdraws.update_one({"id": withdraw_id}, {"$set": {"status": status, "comment": data.get("comment")}})
    return {"success": True}

app.include_router(api_router)

app.add_middleware(
    CORSMiddleware,
    allow_credentials=True,
    allow_origins=os.environ.get('CORS_ORIGINS', '*').split(','),
    allow_methods=["*"],
    allow_headers=["*"],
)
