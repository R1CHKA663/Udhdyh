from fastapi import FastAPI, APIRouter, HTTPException, Depends, Query, Request
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
from pydantic import BaseModel, Field, ConfigDict
from typing import List, Optional, Dict, Any
import uuid
from datetime import datetime, timezone, timedelta
import jwt
from passlib.context import CryptContext
from decimal import Decimal, ROUND_DOWN
import httpx

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
pwd_context = CryptContext(schemes=["bcrypt"], deprecated="auto")
security = HTTPBearer(auto_error=False)

# Max bet limit to prevent server crash
MAX_BET = 1000000

# Create the main app
app = FastAPI(title="EASY MONEY Gaming Platform")
api_router = APIRouter(prefix="/api")

logging.basicConfig(level=logging.INFO, format='%(asctime)s - %(name)s - %(levelname)s - %(message)s')
logger = logging.getLogger(__name__)

# ================== MODELS ==================

class TelegramAuthData(BaseModel):
    id: int
    first_name: str
    last_name: Optional[str] = ""
    username: Optional[str] = ""
    photo_url: Optional[str] = ""
    auth_date: int
    hash: str
    ref_code: Optional[str] = None

class GamePlayRequest(BaseModel):
    bet: float = Field(ge=1, le=MAX_BET)

class MinesPlayRequest(GamePlayRequest):
    bombs: int = Field(ge=2, le=24)

class MinesPressRequest(BaseModel):
    cell: int = Field(ge=1, le=25)

class DicePlayRequest(GamePlayRequest):
    chance: float = Field(ge=1, le=95)
    direction: str = Field(pattern="^(up|down)$")

class BubblesPlayRequest(GamePlayRequest):
    target: float = Field(ge=1.05, le=1000000)

class WheelPlayRequest(BaseModel):
    bet: float = Field(ge=1, le=MAX_BET)
    level: int = Field(ge=1, le=3)

class PromoCreate(BaseModel):
    name: str = Field(min_length=3, max_length=20)
    reward: float = Field(ge=0)
    limit: int = Field(ge=0)
    type: int = Field(ge=0, le=4)  # 0=balance, 1=deposit_bonus, 2=freespins, 3=no_wager, 4=cashback
    deposit_required: bool = False
    wager_multiplier: float = Field(default=3.0, ge=0, le=100)
    min_deposit: float = Field(default=0, ge=0)
    bonus_percent: float = Field(default=0, ge=0, le=500)  # For deposit bonus promos

class PaymentCreate(BaseModel):
    amount: float = Field(ge=50, le=100000)
    system: str
    promo_code: Optional[str] = None

class WithdrawCreate(BaseModel):
    amount: float = Field(ge=100)
    wallet: str
    system: str

class AdminLoginRequest(BaseModel):
    password: str

class AdminUserUpdate(BaseModel):
    user_id: str
    balance: Optional[float] = None
    is_admin: Optional[bool] = None
    is_ban: Optional[bool] = None
    is_ban_comment: Optional[str] = None
    is_drain: Optional[bool] = None
    is_drain_chance: Optional[float] = None
    is_youtuber: Optional[bool] = None

class RTPUpdate(BaseModel):
    dice_rtp: Optional[float] = Field(default=None, ge=90, le=99.9)
    mines_rtp: Optional[float] = Field(default=None, ge=90, le=99.9)
    bubbles_rtp: Optional[float] = Field(default=None, ge=90, le=99.9)
    wheel_rtp: Optional[float] = Field(default=None, ge=90, le=99.9)
    slots_rtp: Optional[float] = Field(default=None, ge=90, le=99.9)

# ================== HELPERS ==================

def round_money(value: float) -> float:
    """Round money to 2 decimal places"""
    return float(Decimal(str(value)).quantize(Decimal('0.01'), rounding=ROUND_DOWN))

def create_token(user_id: str) -> str:
    expire = datetime.now(timezone.utc) + timedelta(days=30)
    return jwt.encode({"sub": user_id, "exp": expire}, SECRET_KEY, algorithm=ALGORITHM)

def verify_telegram_auth(data: dict) -> bool:
    check_hash = data.get('hash')
    if not check_hash:
        return False
    data_check_arr = []
    for key in sorted(data.keys()):
        if key != 'hash' and data[key] is not None and data[key] != '':
            data_check_arr.append(f"{key}={data[key]}")
    data_check_string = "\n".join(data_check_arr)
    secret_key = hashlib.sha256(TELEGRAM_BOT_TOKEN.encode()).digest()
    hmac_hash = hmac.new(secret_key, data_check_string.encode(), hashlib.sha256).hexdigest()
    return hmac_hash == check_hash

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

async def get_optional_user(credentials: HTTPAuthorizationCredentials = Depends(security)):
    if not credentials:
        return None
    try:
        payload = jwt.decode(credentials.credentials, SECRET_KEY, algorithms=[ALGORITHM])
        user_id = payload.get("sub")
        if user_id:
            return await db.users.find_one({"id": user_id}, {"_id": 0})
    except:
        pass
    return None

async def verify_admin_token(credentials: HTTPAuthorizationCredentials = Depends(security)):
    if not credentials:
        raise HTTPException(status_code=401, detail="Требуется авторизация")
    try:
        payload = jwt.decode(credentials.credentials, SECRET_KEY, algorithms=[ALGORITHM])
        if not payload.get("admin"):
            raise HTTPException(status_code=403, detail="Доступ запрещен")
        return True
    except jwt.ExpiredSignatureError:
        raise HTTPException(status_code=401, detail="Сессия истекла")
    except:
        raise HTTPException(status_code=401, detail="Неверный токен")

async def get_settings():
    settings = await db.settings.find_one({"id": "main"}, {"_id": 0})
    if not settings:
        settings = {
            "id": "main",
            "raceback_percent": 10,
            "ref_percent": 50,
            "min_withdraw": 100,
            "dice_rtp": 97,
            "mines_rtp": 97,
            "bubbles_rtp": 97,
            "wheel_rtp": 97,
            "slots_rtp": 97,
            "dice_bank": 10000,
            "mines_bank": 10000,
            "bubbles_bank": 10000,
            "wheel_bank": 10000
        }
        await db.settings.insert_one(settings)
    return settings

async def update_bank(game: str, status: str, amount: float, user: dict):
    if user.get("is_youtuber"):
        return
    settings = await get_settings()
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
    """Determine if player should win based on RTP and user status"""
    if user.get("is_youtuber"):
        return random.random() < 0.8  # Youtubers win 80%
    if user.get("is_drain"):
        drain_chance = user.get("is_drain_chance", 20)
        if random.randint(1, 100) <= drain_chance:
            return False
    return random.random() * 100 < rtp

# ================== STARTUP ==================

@app.on_event("startup")
async def startup():
    await get_settings()
    # Create slots collection with sample data
    slots_count = await db.slots.count_documents({})
    if slots_count == 0:
        await init_slots()
    logger.info("EASY MONEY Gaming Platform started")

@app.on_event("shutdown")
async def shutdown():
    client.close()

async def init_slots():
    """Initialize slots with sample games"""
    providers = [
        {"id": "pragmatic", "name": "Pragmatic Play", "logo": "/assets/providers/pragmatic.png"},
        {"id": "netent", "name": "NetEnt", "logo": "/assets/providers/netent.png"},
        {"id": "playngo", "name": "Play'n GO", "logo": "/assets/providers/playngo.png"},
        {"id": "microgaming", "name": "Microgaming", "logo": "/assets/providers/microgaming.png"},
        {"id": "egt", "name": "EGT", "logo": "/assets/providers/egt.png"},
    ]
    
    sample_slots = [
        {"id": "gates-of-olympus", "name": "Gates of Olympus", "provider": "pragmatic", "image": "https://cdn.softswiss.net/i/s2/pragmaticexternal/vs20olympgate.png", "rtp": 96.5},
        {"id": "sweet-bonanza", "name": "Sweet Bonanza", "provider": "pragmatic", "image": "https://cdn.softswiss.net/i/s2/pragmaticexternal/vs20fruitsw.png", "rtp": 96.48},
        {"id": "big-bass-bonanza", "name": "Big Bass Bonanza", "provider": "pragmatic", "image": "https://cdn.softswiss.net/i/s2/pragmaticexternal/vs10bbbonanza.png", "rtp": 96.71},
        {"id": "dog-house", "name": "The Dog House", "provider": "pragmatic", "image": "https://cdn.softswiss.net/i/s2/pragmaticexternal/vs20doghouse.png", "rtp": 96.51},
        {"id": "starz-megaways", "name": "Starz Megaways", "provider": "pragmatic", "image": "https://cdn.softswiss.net/i/s2/pragmaticexternal/vswayshive.png", "rtp": 96.48},
        {"id": "wolf-gold", "name": "Wolf Gold", "provider": "pragmatic", "image": "https://cdn.softswiss.net/i/s2/pragmaticexternal/vs25wolfgold.png", "rtp": 96},
        {"id": "book-of-dead", "name": "Book of Dead", "provider": "playngo", "image": "https://cdn.softswiss.net/i/s2/playngo/BookofDead.png", "rtp": 96.21},
        {"id": "starburst", "name": "Starburst", "provider": "netent", "image": "https://cdn.softswiss.net/i/s2/netent/starburst.png", "rtp": 96.09},
        {"id": "gonzo-quest", "name": "Gonzo's Quest", "provider": "netent", "image": "https://cdn.softswiss.net/i/s2/netent/gonzos_quest.png", "rtp": 95.97},
        {"id": "reactoonz", "name": "Reactoonz", "provider": "playngo", "image": "https://cdn.softswiss.net/i/s2/playngo/Reactoonz.png", "rtp": 96.51},
        {"id": "immortal-romance", "name": "Immortal Romance", "provider": "microgaming", "image": "https://cdn.softswiss.net/i/s2/microgaming/ImmortalRomance.png", "rtp": 96.86},
        {"id": "40-burning-hot", "name": "40 Burning Hot", "provider": "egt", "image": "https://cdn.softswiss.net/i/s2/egt/40BurningHot.png", "rtp": 95.53},
    ]
    
    await db.providers.delete_many({})
    await db.providers.insert_many(providers)
    
    for slot in sample_slots:
        slot["active"] = True
        slot["created_at"] = datetime.now(timezone.utc).isoformat()
    await db.slots.insert_many(sample_slots)

# ================== AUTH ==================

@api_router.post("/auth/telegram")
async def telegram_auth(data: TelegramAuthData, request: Request):
    auth_data = {'id': data.id, 'first_name': data.first_name, 'auth_date': data.auth_date, 'hash': data.hash}
    if data.last_name: auth_data['last_name'] = data.last_name
    if data.username: auth_data['username'] = data.username
    if data.photo_url: auth_data['photo_url'] = data.photo_url
    
    # Get client IP
    client_ip = request.headers.get("x-forwarded-for", request.client.host if request.client else "unknown")
    
    user = await db.users.find_one({"telegram_id": data.id}, {"_id": 0})
    
    if user:
        name = f"{data.first_name} {data.last_name or ''}".strip()
        await db.users.update_one({"telegram_id": data.id}, {"$set": {
            "name": name, "username": data.username or "", "img": data.photo_url or "/logo.png",
            "last_login": datetime.now(timezone.utc).isoformat(), "last_ip": client_ip
        }})
        user = await db.users.find_one({"telegram_id": data.id}, {"_id": 0})
    else:
        user_id = str(uuid.uuid4())
        ref_link = secrets.token_hex(5)
        name = f"{data.first_name} {data.last_name or ''}".strip()
        
        invited_by = None
        if data.ref_code:
            inviter = await db.users.find_one({"ref_link": data.ref_code}, {"_id": 0})
            if inviter:
                invited_by = inviter["id"]
                await db.users.update_one({"id": inviter["id"]}, {"$inc": {"referalov": 1}})
        
        user = {
            "id": user_id, "telegram_id": data.id, "username": data.username or "", "name": name,
            "img": data.photo_url or "/logo.png", "balance": 0.0, "deposit": 0.0, "raceback": 0.0,
            "referalov": 0, "income": 0.0, "income_all": 0.0, "ref_link": ref_link,
            "invited_by": invited_by, "is_admin": False, "is_ban": False, "is_ban_comment": None,
            "is_youtuber": False, "is_drain": False, "is_drain_chance": 20.0, "wager": 0.0,
            "register_ip": client_ip, "last_ip": client_ip,
            "created_at": datetime.now(timezone.utc).isoformat(),
            "last_login": datetime.now(timezone.utc).isoformat()
        }
        await db.users.insert_one(user)
        user = await db.users.find_one({"telegram_id": data.id}, {"_id": 0})
    
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
async def mines_play(data: MinesPlayRequest, user: dict = Depends(get_current_user)):
    if user.get("is_ban"):
        raise HTTPException(status_code=403, detail="Ваш аккаунт заблокирован")
    
    active_game = await db.mines_games.find_one({"user_id": user["id"], "active": True}, {"_id": 0})
    if active_game:
        raise HTTPException(status_code=400, detail="У вас есть активная игра")
    
    bet = min(data.bet, user["balance"])  # Prevent betting more than balance
    if bet < 1:
        raise HTTPException(status_code=400, detail="Недостаточно средств")
    
    await db.users.update_one({"id": user["id"]}, {"$inc": {"balance": -bet, "wager": -bet}})
    
    all_positions = list(range(1, 26))
    random.shuffle(all_positions)
    mines_positions = all_positions[:data.bombs]
    
    game = {
        "id": str(uuid.uuid4()), "user_id": user["id"], "bet": bet, "bombs": data.bombs,
        "mines": mines_positions, "clicked": [], "win": 0.0, "active": True,
        "created_at": datetime.now(timezone.utc).isoformat()
    }
    await db.mines_games.insert_one(game)
    
    return {"success": True, "balance": round_money(user["balance"] - bet), "game_id": game["id"]}

@api_router.post("/games/mines/press")
async def mines_press(data: MinesPressRequest, user: dict = Depends(get_current_user)):
    game = await db.mines_games.find_one({"user_id": user["id"], "active": True}, {"_id": 0})
    if not game:
        raise HTTPException(status_code=400, detail="У вас нет активных игр")
    
    if data.cell in game["clicked"]:
        raise HTTPException(status_code=400, detail="Вы уже нажали на эту ячейку")
    
    settings = await get_settings()
    rtp = settings.get("mines_rtp", 97)
    clicked = game["clicked"] + [data.cell]
    
    current_coeff = get_mines_coefficient(game["bombs"], len(clicked))
    potential_win = round_money(game["bet"] * current_coeff)
    
    # Determine win/lose based on RTP
    hit_mine = data.cell in game["mines"]
    
    if not hit_mine and not user.get("is_youtuber"):
        bank = settings.get("mines_bank", 10000)
        if potential_win > bank or not should_player_win(rtp, user):
            # Force lose - move mine to clicked cell
            other_clicked = [c for c in clicked if c != data.cell]
            available = [i for i in range(1, 26) if i not in other_clicked]
            random.shuffle(available)
            new_mines = [data.cell] + [p for p in available if p != data.cell][:game["bombs"]-1]
            game["mines"] = new_mines
            hit_mine = True
            await db.mines_games.update_one({"id": game["id"]}, {"$set": {"mines": new_mines}})
    
    if hit_mine:
        await db.mines_games.update_one({"id": game["id"]}, {"$set": {"active": False, "clicked": clicked, "win": 0}})
        await update_bank("mines", "lose", game["bet"], user)
        await calculate_raceback(user["id"], game["bet"])
        win_positions = [i for i in range(1, 26) if i not in game["mines"] and i not in clicked]
        return {"success": True, "status": "lose", "cell": data.cell, "mines": game["mines"], "win_positions": win_positions}
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
    return {"success": True, "win": win, "coefficient": win / game["bet"], "balance": user_data["balance"], "mines": game["mines"]}

@api_router.get("/games/mines/current")
async def mines_current(user: dict = Depends(get_current_user)):
    game = await db.mines_games.find_one({"user_id": user["id"], "active": True}, {"_id": 0})
    if game:
        return {"success": True, "active": True, "win": game["win"], "clicked": game["clicked"], "bet": game["bet"], "bombs": game["bombs"]}
    return {"success": True, "active": False}

# ================== GAMES - DICE ==================

@api_router.post("/games/dice/play")
async def dice_play(data: DicePlayRequest, user: dict = Depends(get_current_user)):
    if user.get("is_ban"):
        raise HTTPException(status_code=403, detail="Ваш аккаунт заблокирован")
    
    bet = min(data.bet, user["balance"])
    if bet < 1:
        raise HTTPException(status_code=400, detail="Недостаточно средств")
    
    settings = await get_settings()
    rtp = settings.get("dice_rtp", 97)
    
    rand_num = random.randint(1, 1000000)
    threshold = int((data.chance / 100) * 999999)
    
    if data.direction == "down":
        is_win = rand_num < threshold
    else:
        is_win = rand_num > (999999 - threshold)
    
    coefficient = round(100 / data.chance, 2)
    potential_win = round_money(bet * coefficient)
    
    # Apply RTP
    if is_win and not user.get("is_youtuber"):
        bank = settings.get("dice_bank", 10000)
        if potential_win - bet > bank or not should_player_win(rtp, user):
            is_win = False
            if data.direction == "down":
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
    
    await db.dice_games.insert_one({
        "id": str(uuid.uuid4()), "user_id": user["id"], "bet": bet, "chance": data.chance,
        "direction": data.direction, "result": rand_num, "win": win,
        "created_at": datetime.now(timezone.utc).isoformat()
    })
    
    user_data = await db.users.find_one({"id": user["id"]}, {"_id": 0})
    return {"success": True, "status": "win" if is_win else "lose", "result": rand_num, "win": win, "balance": user_data["balance"], "coefficient": coefficient}

# ================== GAMES - BUBBLES ==================

@api_router.post("/games/bubbles/play")
async def bubbles_play(data: BubblesPlayRequest, user: dict = Depends(get_current_user)):
    if user.get("is_ban"):
        raise HTTPException(status_code=403, detail="Ваш аккаунт заблокирован")
    
    bet = min(data.bet, user["balance"])
    if bet < 1:
        raise HTTPException(status_code=400, detail="Недостаточно средств")
    
    settings = await get_settings()
    rtp = settings.get("bubbles_rtp", 97)
    
    rand_val = random.randint(1, 1000000) / 1000000
    rand_multiplier = round(1000000 / (int(rand_val * 1000000) + 1), 2)
    
    is_win = rand_multiplier >= data.target
    potential_win = round_money(bet * data.target)
    
    if is_win and not user.get("is_youtuber"):
        bank = settings.get("bubbles_bank", 10000)
        if potential_win - bet > bank or not should_player_win(rtp, user):
            is_win = False
            while rand_multiplier >= data.target:
                rand_val = random.randint(1, 1000000) / 1000000
                rand_multiplier = round(1000000 / (int(rand_val * 1000000) + 1), 2)
    
    if is_win:
        win = potential_win
        balance_change = win - bet
        await update_bank("bubbles", "win", win - bet, user)
    else:
        win = 0
        balance_change = -bet
        await update_bank("bubbles", "lose", bet, user)
        await calculate_raceback(user["id"], bet)
    
    await db.users.update_one({"id": user["id"]}, {"$inc": {"balance": balance_change, "wager": -bet}})
    
    await db.bubbles_games.insert_one({
        "id": str(uuid.uuid4()), "user_id": user["id"], "bet": bet, "target": data.target,
        "result": rand_multiplier, "win": win, "created_at": datetime.now(timezone.utc).isoformat()
    })
    
    user_data = await db.users.find_one({"id": user["id"]}, {"_id": 0})
    return {"success": True, "status": "win" if is_win else "lose", "result": rand_multiplier, "win": win, "balance": user_data["balance"]}

# ================== GAMES - WHEEL ==================

WHEEL_COEFFICIENTS = {1: {"blue": 1.2, "red": 1.5}, 2: {"blue": 1.2, "red": 1.5, "green": 3.0, "pink": 5.0}, 3: {"pink": 49.5}}
WHEEL_ITEMS = {
    1: ["lose"] * 11 + ["red"] * 6 + ["blue"] * 36,
    2: ["lose"] * 26 + ["blue"] * 14 + ["red"] * 9 + ["green"] * 4 + ["pink"] * 2,
    3: ["lose"] * 50 + ["pink"] * 2
}

@api_router.post("/games/wheel/play")
async def wheel_play(data: WheelPlayRequest, user: dict = Depends(get_current_user)):
    if user.get("is_ban"):
        raise HTTPException(status_code=403, detail="Ваш аккаунт заблокирован")
    
    bet = min(data.bet, user["balance"])
    if bet < 1:
        raise HTTPException(status_code=400, detail="Недостаточно средств")
    
    settings = await get_settings()
    rtp = settings.get("wheel_rtp", 97)
    
    items = WHEEL_ITEMS.get(data.level, WHEEL_ITEMS[1]).copy()
    random.shuffle(items)
    color = random.choice(items)
    coef = WHEEL_COEFFICIENTS.get(data.level, {}).get(color, 0)
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
    
    position = random.randint(360, 720) + (random.randint(0, 360))
    
    await db.wheel_games.insert_one({
        "id": str(uuid.uuid4()), "user_id": user["id"], "bet": bet, "level": data.level,
        "color": color, "coef": coef, "win": total_win, "created_at": datetime.now(timezone.utc).isoformat()
    })
    
    user_data = await db.users.find_one({"id": user["id"]}, {"_id": 0})
    return {"success": True, "color": color, "coef": coef, "win": total_win, "position": position, "balance": user_data["balance"]}

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

@api_router.post("/slots/play/{slot_id}")
async def play_slot(slot_id: str, bet: float = 10, user: dict = Depends(get_current_user)):
    if user.get("is_ban"):
        raise HTTPException(status_code=403, detail="Ваш аккаунт заблокирован")
    
    slot = await db.slots.find_one({"id": slot_id}, {"_id": 0})
    if not slot:
        raise HTTPException(status_code=404, detail="Слот не найден")
    
    bet = min(max(bet, 1), user["balance"])
    if bet < 1:
        raise HTTPException(status_code=400, detail="Недостаточно средств")
    
    settings = await get_settings()
    rtp = settings.get("slots_rtp", 97)
    
    # Simulate slot spin with RTP
    if should_player_win(rtp, user):
        # Win - random multiplier between 1.1 and 50
        multiplier = round(random.uniform(1.1, 5.0) if random.random() < 0.8 else random.uniform(5.0, 50.0), 2)
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
    
    await db.slot_games.insert_one({
        "id": str(uuid.uuid4()), "user_id": user["id"], "slot_id": slot_id, "bet": bet,
        "multiplier": multiplier, "win": win, "created_at": datetime.now(timezone.utc).isoformat()
    })
    
    user_data = await db.users.find_one({"id": user["id"]}, {"_id": 0})
    
    # Generate random symbols for display
    symbols = ["🍒", "🍋", "🍊", "🍇", "⭐", "7️⃣", "💎", "🔔"]
    reels = [[random.choice(symbols) for _ in range(3)] for _ in range(5)]
    
    return {
        "success": True, "status": "win" if win > 0 else "lose",
        "reels": reels, "multiplier": multiplier, "win": win, "balance": user_data["balance"]
    }

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
async def create_payment(data: PaymentCreate, user: dict = Depends(get_current_user)):
    payment = {
        "id": str(uuid.uuid4()), "user_id": user["id"], "amount": data.amount,
        "system": data.system, "promo_code": data.promo_code, "status": "pending",
        "created_at": datetime.now(timezone.utc).isoformat()
    }
    await db.payments.insert_one(payment)
    return {"success": True, "payment_id": payment["id"], "link": f"/payment/mock/{payment['id']}", "message": "Платежная система в режиме тестирования"}

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
            if promo.get("type") == 1:  # Deposit bonus
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
async def create_withdraw(data: WithdrawCreate, user: dict = Depends(get_current_user)):
    settings = await get_settings()
    min_withdraw = settings.get("min_withdraw", 100)
    
    if data.amount < min_withdraw:
        raise HTTPException(status_code=400, detail=f"Минимальная сумма вывода: {min_withdraw}")
    if user["balance"] < data.amount:
        raise HTTPException(status_code=400, detail="Недостаточно средств")
    if user["wager"] > 0:
        raise HTTPException(status_code=400, detail=f"Необходимо отыграть вейджер: {user['wager']:.2f}")
    
    await db.users.update_one({"id": user["id"]}, {"$inc": {"balance": -data.amount}})
    
    withdraw = {
        "id": str(uuid.uuid4()), "user_id": user["id"], "amount": data.amount,
        "wallet": data.wallet, "system": data.system, "status": "pending", "comment": None,
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
async def activate_promo(code: str, user: dict = Depends(get_current_user)):
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
    if promo.get("min_deposit", 0) > user["deposit"]:
        raise HTTPException(status_code=400, detail=f"Требуется депозит от {promo['min_deposit']}₽")
    
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
    
    for coll, game_name in [("dice_games", "dice"), ("mines_games", "mines"), ("bubbles_games", "bubbles"), ("wheel_games", "wheel")]:
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
async def admin_login(data: AdminLoginRequest):
    if data.password != ADMIN_PASSWORD:
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
    
    all_withdraws = await db.withdraws.find({"status": "completed"}, {"_id": 0}).to_list(10000)
    withdraw_all = sum(w["amount"] for w in all_withdraws)
    
    pending_withdraws = await db.withdraws.find({"status": "pending"}, {"_id": 0}).to_list(1000)
    
    users_all = await db.users.count_documents({})
    users_today = await db.users.count_documents({"created_at": {"$gte": today.isoformat()}})
    
    settings = await get_settings()
    
    return {
        "success": True,
        "payments": {"today": payment_today, "week": payment_week, "all": payment_all},
        "withdrawals": {"all": withdraw_all, "pending_count": len(pending_withdraws), "pending_sum": sum(w["amount"] for w in pending_withdraws)},
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
async def admin_update_user(data: AdminUserUpdate, _: bool = Depends(verify_admin_token)):
    update_data = {k: v for k, v in data.model_dump().items() if v is not None and k != "user_id"}
    if update_data:
        await db.users.update_one({"id": data.user_id}, {"$set": update_data})
    return {"success": True}

@api_router.put("/admin/rtp")
async def admin_update_rtp(data: RTPUpdate, _: bool = Depends(verify_admin_token)):
    update_data = {k: v for k, v in data.model_dump().items() if v is not None}
    if update_data:
        await db.settings.update_one({"id": "main"}, {"$set": update_data})
    return {"success": True}

@api_router.get("/admin/settings")
async def admin_get_settings(_: bool = Depends(verify_admin_token)):
    settings = await get_settings()
    return {"success": True, "settings": settings}

@api_router.put("/admin/settings")
async def admin_update_settings(raceback_percent: Optional[float] = None, ref_percent: Optional[float] = None, min_withdraw: Optional[float] = None, _: bool = Depends(verify_admin_token)):
    update_data = {}
    if raceback_percent is not None: update_data["raceback_percent"] = raceback_percent
    if ref_percent is not None: update_data["ref_percent"] = ref_percent
    if min_withdraw is not None: update_data["min_withdraw"] = min_withdraw
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
async def admin_create_promo(data: PromoCreate, _: bool = Depends(verify_admin_token)):
    existing = await db.promos.find_one({"name": data.name})
    if existing:
        raise HTTPException(status_code=400, detail="Промокод уже существует")
    
    promo = {
        "id": str(uuid.uuid4()), "name": data.name, "reward": data.reward, "limit": data.limit,
        "limited": 0, "type": data.type, "deposit_required": data.deposit_required,
        "wager_multiplier": data.wager_multiplier, "min_deposit": data.min_deposit,
        "bonus_percent": data.bonus_percent, "status": False,
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
async def admin_update_withdraw(withdraw_id: str, status: str, comment: Optional[str] = None, _: bool = Depends(verify_admin_token)):
    withdraw = await db.withdraws.find_one({"id": withdraw_id}, {"_id": 0})
    if not withdraw:
        raise HTTPException(status_code=404, detail="Вывод не найден")
    
    if status == "rejected":
        await db.users.update_one({"id": withdraw["user_id"]}, {"$inc": {"balance": withdraw["amount"]}})
    
    await db.withdraws.update_one({"id": withdraw_id}, {"$set": {"status": status, "comment": comment}})
    return {"success": True}

# Include router
app.include_router(api_router)

# CORS
app.add_middleware(
    CORSMiddleware,
    allow_credentials=True,
    allow_origins=os.environ.get('CORS_ORIGINS', '*').split(','),
    allow_methods=["*"],
    allow_headers=["*"],
)
