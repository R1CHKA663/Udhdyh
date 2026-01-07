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

# Create the main app
app = FastAPI(title="EASY MONEY Gaming Platform")
api_router = APIRouter(prefix="/api")

# Configure logging
logging.basicConfig(level=logging.INFO, format='%(asctime)s - %(name)s - %(levelname)s - %(message)s')
logger = logging.getLogger(__name__)

# ================== MODELS ==================

class UserCreate(BaseModel):
    telegram_id: int
    username: str
    first_name: str
    last_name: Optional[str] = ""
    photo_url: Optional[str] = ""
    ref_code: Optional[str] = None

class UserResponse(BaseModel):
    model_config = ConfigDict(extra="ignore")
    id: str
    telegram_id: int
    username: str
    name: str
    img: str
    balance: float
    deposit: float
    raceback: float
    referalov: int
    income: float
    income_all: float
    ref_link: str
    is_admin: bool
    is_ban: bool
    is_ban_comment: Optional[str] = None
    created_at: str

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
    bet: float = Field(ge=1, le=10000)

class MinesPlayRequest(GamePlayRequest):
    bombs: int = Field(ge=2, le=24)

class MinesPressRequest(BaseModel):
    cell: int = Field(ge=1, le=25)

class DicePlayRequest(GamePlayRequest):
    chance: float = Field(ge=1, le=95)
    direction: str = Field(pattern="^(up|down)$")

class BubblesPlayRequest(GamePlayRequest):
    target: float = Field(ge=1.05, le=1000000)

class PromoCreate(BaseModel):
    name: str = Field(min_length=3, max_length=20)
    reward: float = Field(ge=0)
    limit: int = Field(ge=0)
    type: int = Field(ge=0, le=2)
    deposit_required: bool = False

class PaymentCreate(BaseModel):
    amount: float = Field(ge=50, le=15000)
    system: str = Field(pattern="^(freekassa|linepay|qiwi)$")
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

class BankUpdate(BaseModel):
    dice: Optional[float] = None
    mines: Optional[float] = None
    bubbles: Optional[float] = None
    wheel: Optional[float] = None
    normal_dice: Optional[float] = None
    normal_mines: Optional[float] = None
    normal_bubbles: Optional[float] = None
    normal_wheel: Optional[float] = None
    fee_dice: Optional[float] = None
    fee_mines: Optional[float] = None
    fee_bubbles: Optional[float] = None
    fee_wheel: Optional[float] = None

class WheelPlayRequest(BaseModel):
    bet: float = Field(ge=1, le=1000000)
    level: int = Field(ge=1, le=3)

# ================== HELPERS ==================

def create_token(user_id: str) -> str:
    expire = datetime.now(timezone.utc) + timedelta(days=30)
    return jwt.encode({"sub": user_id, "exp": expire}, SECRET_KEY, algorithm=ALGORITHM)

def verify_telegram_auth(data: dict) -> bool:
    """Verify Telegram Login Widget data"""
    check_hash = data.get('hash')
    if not check_hash:
        return False
    
    # Create data check string (sorted alphabetically, without hash)
    data_check_arr = []
    for key in sorted(data.keys()):
        if key != 'hash' and data[key] is not None and data[key] != '':
            data_check_arr.append(f"{key}={data[key]}")
    data_check_string = "\n".join(data_check_arr)
    
    # Create secret key from bot token
    secret_key = hashlib.sha256(TELEGRAM_BOT_TOKEN.encode()).digest()
    
    # Calculate HMAC-SHA256
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

async def get_admin_user(credentials: HTTPAuthorizationCredentials = Depends(security)):
    user = await get_current_user(credentials)
    if not user.get("is_admin"):
        raise HTTPException(status_code=403, detail="Доступ запрещен")
    return user

async def init_bank():
    """Initialize bank if not exists"""
    bank = await db.bank.find_one({"id": "main"})
    if not bank:
        await db.bank.insert_one({
            "id": "main",
            "dice": 10000,
            "mines": 10000,
            "bubbles": 10000,
            "normal_dice": 10000,
            "normal_mines": 10000,
            "normal_bubbles": 10000,
            "fee_dice": 25,
            "fee_mines": 25,
            "fee_bubbles": 25,
            "jackpot_sum": 100
        })

async def init_admin_settings():
    """Initialize admin settings"""
    settings = await db.admin_settings.find_one({"id": "main"})
    if not settings:
        await db.admin_settings.insert_one({
            "id": "main",
            "raceback_percent": 10,
            "ref_percent": 50,
            "min_withdraw": 100
        })

async def update_bank(game: str, status: str, amount: float, user: dict):
    """Update bank after game"""
    if user.get("is_youtuber"):
        return
    
    bank = await db.bank.find_one({"id": "main"})
    fee = bank.get(f"fee_{game}", 25)
    
    if status == "win":
        await db.bank.update_one({"id": "main"}, {"$inc": {game: -amount}})
    else:
        await db.bank.update_one({"id": "main"}, {"$inc": {game: amount * (1 - fee / 100)}})

async def calculate_raceback(user_id: str, game: str, bet: float):
    """Calculate and add raceback on loss"""
    settings = await db.admin_settings.find_one({"id": "main"})
    percent = settings.get("raceback_percent", 10) / 100
    raceback_amount = bet * percent
    
    await db.users.update_one({"id": user_id}, {"$inc": {"raceback": raceback_amount}})

async def add_ref_bonus(user: dict, deposit_amount: float):
    """Add referral bonus when user deposits"""
    if not user.get("invited_by"):
        return
    
    settings = await db.admin_settings.find_one({"id": "main"})
    percent = settings.get("ref_percent", 50) / 100
    bonus = deposit_amount * percent
    
    await db.users.update_one(
        {"id": user["invited_by"]},
        {"$inc": {"income": bonus, "income_all": bonus}}
    )
    
    await db.logs.insert_one({
        "id": str(uuid.uuid4()),
        "user_id": user["invited_by"],
        "type": "ref_bonus",
        "info": f"Реферальный бонус от депозита пользователя {user['name']}: {bonus:.2f}",
        "amount": bonus,
        "created_at": datetime.now(timezone.utc).isoformat()
    })

def get_mines_coefficient(bombs: int, opened: int) -> float:
    """Calculate mines game coefficient"""
    coeff = 1.0
    for i in range(opened):
        coeff *= (25 - i) / (25 - bombs - i)
    return coeff

# ================== AUTH ROUTES ==================

@api_router.post("/auth/telegram")
async def telegram_auth(data: TelegramAuthData):
    """Authenticate via Telegram Login Widget"""
    auth_data = {
        'id': data.id,
        'first_name': data.first_name,
        'auth_date': data.auth_date,
        'hash': data.hash
    }
    if data.last_name:
        auth_data['last_name'] = data.last_name
    if data.username:
        auth_data['username'] = data.username
    if data.photo_url:
        auth_data['photo_url'] = data.photo_url
    
    ref_code = data.ref_code
    
    # Verify Telegram data
    if not verify_telegram_auth(auth_data):
        logger.warning(f"Invalid Telegram auth hash for user {data.id}")
        # For development, allow if auth_date is recent (within 24 hours)
        current_time = int(datetime.now(timezone.utc).timestamp())
        if current_time - data.auth_date > 86400:
            raise HTTPException(status_code=400, detail="Ошибка верификации данных Telegram")
    
    # Check if user exists
    user = await db.users.find_one({"telegram_id": data.id}, {"_id": 0})
    
    if user:
        # Update user info
        name = f"{data.first_name} {data.last_name or ''}".strip()
        await db.users.update_one(
            {"telegram_id": data.id},
            {"$set": {
                "name": name,
                "username": data.username or "",
                "img": data.photo_url or "/logo.png",
                "last_login": datetime.now(timezone.utc).isoformat()
            }}
        )
        user = await db.users.find_one({"telegram_id": data.id}, {"_id": 0})
    else:
        # Create new user
        user_id = str(uuid.uuid4())
        ref_link = secrets.token_hex(5)
        name = f"{data.first_name} {data.last_name or ''}".strip()
        
        invited_by = None
        if ref_code:
            inviter = await db.users.find_one({"ref_link": ref_code}, {"_id": 0})
            if inviter:
                invited_by = inviter["id"]
                await db.users.update_one({"id": inviter["id"]}, {"$inc": {"referalov": 1}})
        
        user = {
            "id": user_id,
            "telegram_id": data.id,
            "username": data.username or "",
            "name": name,
            "img": data.photo_url or "/logo.png",
            "balance": 0.0,
            "deposit": 0.0,
            "raceback": 0.0,
            "referalov": 0,
            "income": 0.0,
            "income_all": 0.0,
            "ref_link": ref_link,
            "invited_by": invited_by,
            "is_admin": False,
            "is_ban": False,
            "is_ban_comment": None,
            "is_youtuber": False,
            "is_drain": False,
            "is_drain_chance": 20.0,
            "wager": 0.0,
            "created_at": datetime.now(timezone.utc).isoformat(),
            "last_login": datetime.now(timezone.utc).isoformat()
        }
        await db.users.insert_one(user)
        user = await db.users.find_one({"telegram_id": data.id}, {"_id": 0})
    
    token = create_token(user["id"])
    return {"success": True, "token": token, "user": user}

@api_router.post("/auth/demo")
async def demo_auth(username: str = "demo_user", ref_code: Optional[str] = None):
    """Demo authentication for testing"""
    # Check if user exists
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
            "id": user_id,
            "telegram_id": random.randint(100000000, 999999999),
            "username": username,
            "name": username,
            "img": "/logo.png",
            "balance": 1000.0,  # Demo balance
            "deposit": 0.0,
            "raceback": 0.0,
            "referalov": 0,
            "income": 0.0,
            "income_all": 0.0,
            "ref_link": ref_link,
            "invited_by": invited_by,
            "is_admin": False,
            "is_ban": False,
            "is_ban_comment": None,
            "is_youtuber": False,
            "is_drain": False,
            "is_drain_chance": 20.0,
            "wager": 0.0,
            "created_at": datetime.now(timezone.utc).isoformat(),
            "last_login": datetime.now(timezone.utc).isoformat()
        }
        await db.users.insert_one(user)
        # Fetch without _id
        user = await db.users.find_one({"id": user_id}, {"_id": 0})
    
    token = create_token(user["id"])
    return {"success": True, "token": token, "user": user}

@api_router.get("/auth/me")
async def get_me(user: dict = Depends(get_current_user)):
    """Get current user info"""
    return {"success": True, "user": user}

@api_router.post("/auth/logout")
async def logout():
    return {"success": True}

# ================== GAMES - MINES ==================

@api_router.post("/games/mines/play")
async def mines_play(data: MinesPlayRequest, user: dict = Depends(get_current_user)):
    """Start a new Mines game"""
    if user.get("is_ban"):
        raise HTTPException(status_code=403, detail="Ваш аккаунт заблокирован")
    
    # Check for active game
    active_game = await db.mines_games.find_one({"user_id": user["id"], "active": True}, {"_id": 0})
    if active_game:
        raise HTTPException(status_code=400, detail="У вас есть активная игра")
    
    if user["balance"] < data.bet:
        raise HTTPException(status_code=400, detail="Недостаточно средств")
    
    # Deduct bet
    await db.users.update_one({"id": user["id"]}, {"$inc": {"balance": -data.bet, "wager": -data.bet}})
    
    # Generate mines positions (bombs)
    all_positions = list(range(1, 26))
    random.shuffle(all_positions)
    mines_positions = all_positions[:data.bombs]
    
    game = {
        "id": str(uuid.uuid4()),
        "user_id": user["id"],
        "bet": data.bet,
        "bombs": data.bombs,
        "mines": mines_positions,
        "clicked": [],
        "win": 0,
        "active": True,
        "created_at": datetime.now(timezone.utc).isoformat()
    }
    await db.mines_games.insert_one(game)
    
    new_balance = user["balance"] - data.bet
    return {"success": True, "balance": new_balance, "game_id": game["id"]}

@api_router.post("/games/mines/press")
async def mines_press(data: MinesPressRequest, user: dict = Depends(get_current_user)):
    """Press a cell in Mines game"""
    game = await db.mines_games.find_one({"user_id": user["id"], "active": True}, {"_id": 0})
    if not game:
        raise HTTPException(status_code=400, detail="У вас нет активных игр")
    
    if data.cell in game["clicked"]:
        raise HTTPException(status_code=400, detail="Вы уже нажали на эту ячейку")
    
    bank = await db.bank.find_one({"id": "main"})
    clicked = game["clicked"] + [data.cell]
    
    # Check if should force lose (bank protection & drain)
    current_coeff = get_mines_coefficient(game["bombs"], len(clicked))
    potential_win = game["bet"] * current_coeff
    
    should_force_lose = False
    if not user.get("is_youtuber"):
        bank_ratio = (bank["mines"] - potential_win) / bank["normal_mines"]
        if bank_ratio < random.random():
            should_force_lose = True
        if user.get("is_drain") and user.get("is_drain_chance", 20) > random.randint(1, 100):
            should_force_lose = True
    
    # Check if hit mine
    hit_mine = data.cell in game["mines"]
    
    if should_force_lose and not hit_mine:
        # Regenerate mines to include clicked cell
        other_clicked = [c for c in clicked if c != data.cell]
        available = [i for i in range(1, 26) if i not in other_clicked]
        random.shuffle(available)
        new_mines = [data.cell] + [p for p in available if p != data.cell][:game["bombs"]-1]
        game["mines"] = new_mines
        hit_mine = True
        await db.mines_games.update_one({"id": game["id"]}, {"$set": {"mines": new_mines}})
    
    if hit_mine:
        # Lost
        await db.mines_games.update_one(
            {"id": game["id"]},
            {"$set": {"active": False, "clicked": clicked, "win": 0}}
        )
        await update_bank("mines", "lose", game["bet"], user)
        await calculate_raceback(user["id"], "mines", game["bet"])
        
        # Calculate win positions for display
        win_positions = [i for i in range(1, 26) if i not in game["mines"] and i not in clicked]
        
        return {
            "success": True,
            "status": "lose",
            "cell": data.cell,
            "mines": game["mines"],
            "win_positions": win_positions
        }
    else:
        # Still alive
        coeff = get_mines_coefficient(game["bombs"], len(clicked))
        win = game["bet"] * coeff
        
        await db.mines_games.update_one(
            {"id": game["id"]},
            {"$set": {"clicked": clicked, "win": win}}
        )
        
        # Check if all safe cells opened
        if len(clicked) == 25 - game["bombs"]:
            # Auto-finish with win
            await db.mines_games.update_one({"id": game["id"]}, {"$set": {"active": False}})
            await db.users.update_one({"id": user["id"]}, {"$inc": {"balance": win}})
            await update_bank("mines", "win", win - game["bet"], user)
            
            user_data = await db.users.find_one({"id": user["id"]}, {"_id": 0})
            win_positions = [i for i in range(1, 26) if i not in game["mines"] and i not in clicked]
            
            return {
                "success": True,
                "status": "finish",
                "win": win,
                "coefficient": coeff,
                "balance": user_data["balance"],
                "mines": game["mines"],
                "win_positions": win_positions,
                "clicked": clicked
            }
        
        return {
            "success": True,
            "status": "continue",
            "win": win,
            "coefficient": coeff,
            "clicked": clicked
        }

@api_router.post("/games/mines/take")
async def mines_take(user: dict = Depends(get_current_user)):
    """Take winnings and end Mines game"""
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
    win_positions = [i for i in range(1, 26) if i not in game["mines"] and i not in game["clicked"]]
    
    return {
        "success": True,
        "win": win,
        "coefficient": win / game["bet"],
        "balance": user_data["balance"],
        "mines": game["mines"],
        "win_positions": win_positions
    }

@api_router.get("/games/mines/current")
async def mines_current(user: dict = Depends(get_current_user)):
    """Get current active Mines game"""
    game = await db.mines_games.find_one({"user_id": user["id"], "active": True}, {"_id": 0})
    if game:
        return {
            "success": True,
            "active": True,
            "win": game["win"],
            "clicked": game["clicked"],
            "bet": game["bet"],
            "bombs": game["bombs"]
        }
    return {"success": True, "active": False}

# ================== GAMES - DICE ==================

@api_router.post("/games/dice/play")
async def dice_play(data: DicePlayRequest, user: dict = Depends(get_current_user)):
    """Play Dice game"""
    if user.get("is_ban"):
        raise HTTPException(status_code=403, detail="Ваш аккаунт заблокирован")
    
    if user["balance"] < data.bet:
        raise HTTPException(status_code=400, detail="Недостаточно средств")
    
    bank = await db.bank.find_one({"id": "main"})
    
    # Generate random number 1-1000000
    rand_num = random.randint(1, 1000000)
    threshold = int((data.chance / 100) * 999999)
    
    # Check win condition
    if data.direction == "down":
        is_win = rand_num < threshold
    else:
        is_win = rand_num > (999999 - threshold)
    
    potential_win = (100 / data.chance) * data.bet
    
    # Bank protection & drain check
    if is_win and not user.get("is_youtuber"):
        bank_ratio = (bank["dice"] - (potential_win - data.bet)) / bank["normal_dice"]
        if bank_ratio < random.random():
            is_win = False
            if data.direction == "down":
                rand_num = random.randint(threshold, 1000000)
            else:
                rand_num = random.randint(0, 999999 - threshold)
        
        if user.get("is_drain") and user.get("is_drain_chance", 20) > random.randint(1, 100):
            is_win = False
            if data.direction == "down":
                rand_num = random.randint(threshold, 1000000)
            else:
                rand_num = random.randint(0, 999999 - threshold)
    
    if is_win:
        win = potential_win
        new_balance_change = win - data.bet
        await update_bank("dice", "win", win - data.bet, user)
    else:
        win = 0
        new_balance_change = -data.bet
        await update_bank("dice", "lose", data.bet, user)
        await calculate_raceback(user["id"], "dice", data.bet)
        # Add to jackpot
        await db.bank.update_one({"id": "main"}, {"$inc": {"jackpot_sum": data.bet * 0.01}})
    
    await db.users.update_one({"id": user["id"]}, {"$inc": {"balance": new_balance_change, "wager": -data.bet}})
    
    # Log game
    await db.dice_games.insert_one({
        "id": str(uuid.uuid4()),
        "user_id": user["id"],
        "bet": data.bet,
        "chance": data.chance,
        "direction": data.direction,
        "result": rand_num,
        "win": win,
        "created_at": datetime.now(timezone.utc).isoformat()
    })
    
    user_data = await db.users.find_one({"id": user["id"]}, {"_id": 0})
    
    return {
        "success": True,
        "status": "win" if is_win else "lose",
        "result": rand_num,
        "win": win,
        "balance": user_data["balance"],
        "coefficient": 100 / data.chance
    }

# ================== GAMES - BUBBLES ==================

@api_router.post("/games/bubbles/play")
async def bubbles_play(data: BubblesPlayRequest, user: dict = Depends(get_current_user)):
    """Play Bubbles game"""
    if user.get("is_ban"):
        raise HTTPException(status_code=403, detail="Ваш аккаунт заблокирован")
    
    if user["balance"] < data.bet:
        raise HTTPException(status_code=400, detail="Недостаточно средств")
    
    bank = await db.bank.find_one({"id": "main"})
    
    # Generate random multiplier
    rand_val = random.randint(1, 1000000) / 1000000
    rand_multiplier = 1000000 / (int(rand_val * 1000000) + 1)
    
    is_win = rand_multiplier >= data.target
    potential_win = data.bet * data.target
    
    # Bank protection & drain check
    if is_win and not user.get("is_youtuber"):
        bank_ratio = (bank["bubbles"] - (potential_win - data.bet)) / bank["normal_bubbles"]
        if bank_ratio < random.random():
            is_win = False
            while rand_multiplier >= data.target:
                rand_val = random.randint(1, 1000000) / 1000000
                rand_multiplier = 1000000 / (int(rand_val * 1000000) + 1)
        
        if user.get("is_drain") and user.get("is_drain_chance", 20) > random.randint(1, 100):
            is_win = False
            while rand_multiplier >= data.target:
                rand_val = random.randint(1, 1000000) / 1000000
                rand_multiplier = 1000000 / (int(rand_val * 1000000) + 1)
    
    if is_win:
        win = potential_win
        new_balance_change = win - data.bet
        await update_bank("bubbles", "win", win - data.bet, user)
    else:
        win = 0
        new_balance_change = -data.bet
        await update_bank("bubbles", "lose", data.bet, user)
        await calculate_raceback(user["id"], "bubbles", data.bet)
        await db.bank.update_one({"id": "main"}, {"$inc": {"jackpot_sum": data.bet * 0.01}})
    
    await db.users.update_one({"id": user["id"]}, {"$inc": {"balance": new_balance_change, "wager": -data.bet}})
    
    # Log game
    await db.bubbles_games.insert_one({
        "id": str(uuid.uuid4()),
        "user_id": user["id"],
        "bet": data.bet,
        "target": data.target,
        "result": rand_multiplier,
        "win": win,
        "created_at": datetime.now(timezone.utc).isoformat()
    })
    
    user_data = await db.users.find_one({"id": user["id"]}, {"_id": 0})
    
    return {
        "success": True,
        "status": "win" if is_win else "lose",
        "result": round(rand_multiplier, 2),
        "win": win,
        "balance": user_data["balance"]
    }

# ================== REFERRAL SYSTEM ==================

@api_router.get("/ref/stats")
async def get_ref_stats(user: dict = Depends(get_current_user)):
    """Get referral statistics"""
    return {
        "success": True,
        "ref_link": user["ref_link"],
        "referalov": user["referalov"],
        "income": user["income"],
        "income_all": user["income_all"]
    }

@api_router.post("/ref/withdraw")
async def ref_withdraw(user: dict = Depends(get_current_user)):
    """Withdraw referral earnings to balance"""
    if user["income"] < 10:
        raise HTTPException(status_code=400, detail="Минимум для вывода - 10 рублей")
    
    income = user["income"]
    await db.users.update_one(
        {"id": user["id"]},
        {"$inc": {"balance": income}, "$set": {"income": 0}}
    )
    
    user_data = await db.users.find_one({"id": user["id"]}, {"_id": 0})
    
    return {
        "success": True,
        "withdrawn": income,
        "balance": user_data["balance"]
    }

# ================== RACEBACK (CASHBACK) ==================

@api_router.get("/bonus/raceback")
async def get_raceback(user: dict = Depends(get_current_user)):
    """Get available raceback"""
    return {
        "success": True,
        "raceback": user["raceback"]
    }

@api_router.post("/bonus/raceback/claim")
async def claim_raceback(user: dict = Depends(get_current_user)):
    """Claim raceback when balance is zero"""
    if user["balance"] > 0:
        raise HTTPException(status_code=400, detail="Кешбэк доступен только при нулевом балансе")
    
    if user["raceback"] < 1:
        raise HTTPException(status_code=400, detail="Недостаточно кешбэка")
    
    raceback = user["raceback"]
    await db.users.update_one(
        {"id": user["id"]},
        {"$inc": {"balance": raceback}, "$set": {"raceback": 0}}
    )
    
    user_data = await db.users.find_one({"id": user["id"]}, {"_id": 0})
    
    return {
        "success": True,
        "claimed": raceback,
        "balance": user_data["balance"]
    }

# ================== PAYMENTS (MOCK) ==================

@api_router.post("/payment/create")
async def create_payment(data: PaymentCreate, user: dict = Depends(get_current_user)):
    """Create payment (MOCK)"""
    payment = {
        "id": str(uuid.uuid4()),
        "user_id": user["id"],
        "amount": data.amount,
        "system": data.system,
        "promo_code": data.promo_code,
        "status": "pending",
        "created_at": datetime.now(timezone.utc).isoformat()
    }
    await db.payments.insert_one(payment)
    
    # Mock payment link
    return {
        "success": True,
        "payment_id": payment["id"],
        "link": f"/payment/mock/{payment['id']}",
        "message": "Платежная система в режиме тестирования"
    }

@api_router.post("/payment/mock/complete/{payment_id}")
async def complete_mock_payment(payment_id: str):
    """Complete mock payment (for testing)"""
    payment = await db.payments.find_one({"id": payment_id}, {"_id": 0})
    if not payment:
        raise HTTPException(status_code=404, detail="Платеж не найден")
    
    if payment["status"] != "pending":
        raise HTTPException(status_code=400, detail="Платеж уже обработан")
    
    user = await db.users.find_one({"id": payment["user_id"]}, {"_id": 0})
    
    # Apply promo bonus if exists
    bonus = 0
    if payment.get("promo_code"):
        promo = await db.promos.find_one({"name": payment["promo_code"], "status": False}, {"_id": 0})
        if promo and promo["limited"] < promo["limit"]:
            bonus = payment["amount"] * (promo["reward"] / 100)
            await db.promos.update_one({"id": promo["id"]}, {"$inc": {"limited": 1}})
    
    total_amount = payment["amount"] + bonus
    
    await db.users.update_one(
        {"id": user["id"]},
        {"$inc": {"balance": total_amount, "deposit": payment["amount"], "wager": payment["amount"] * 3}}
    )
    
    await db.payments.update_one({"id": payment_id}, {"$set": {"status": "completed"}})
    
    # Add referral bonus
    await add_ref_bonus(user, payment["amount"])
    
    return {"success": True, "amount": total_amount}

@api_router.get("/payment/history")
async def payment_history(user: dict = Depends(get_current_user)):
    """Get payment history"""
    payments = await db.payments.find({"user_id": user["id"]}, {"_id": 0}).sort("created_at", -1).limit(20).to_list(20)
    return {"success": True, "payments": payments}

# ================== WITHDRAWALS ==================

@api_router.post("/withdraw/create")
async def create_withdraw(data: WithdrawCreate, user: dict = Depends(get_current_user)):
    """Create withdrawal request"""
    settings = await db.admin_settings.find_one({"id": "main"})
    min_withdraw = settings.get("min_withdraw", 100)
    
    if data.amount < min_withdraw:
        raise HTTPException(status_code=400, detail=f"Минимальная сумма вывода: {min_withdraw}")
    
    if user["balance"] < data.amount:
        raise HTTPException(status_code=400, detail="Недостаточно средств")
    
    if user["wager"] > 0:
        raise HTTPException(status_code=400, detail=f"Необходимо отыграть вейджер: {user['wager']:.2f}")
    
    await db.users.update_one({"id": user["id"]}, {"$inc": {"balance": -data.amount}})
    
    withdraw = {
        "id": str(uuid.uuid4()),
        "user_id": user["id"],
        "amount": data.amount,
        "wallet": data.wallet,
        "system": data.system,
        "status": "pending",
        "comment": None,
        "created_at": datetime.now(timezone.utc).isoformat()
    }
    await db.withdraws.insert_one(withdraw)
    
    return {"success": True, "withdraw_id": withdraw["id"]}

@api_router.get("/withdraw/history")
async def withdraw_history(user: dict = Depends(get_current_user)):
    """Get withdrawal history"""
    withdraws = await db.withdraws.find({"user_id": user["id"]}, {"_id": 0}).sort("created_at", -1).limit(20).to_list(20)
    return {"success": True, "withdraws": withdraws}

# ================== PROMO CODES ==================

@api_router.post("/promo/activate")
async def activate_promo(code: str, user: dict = Depends(get_current_user)):
    """Activate promo code"""
    promo = await db.promos.find_one({"name": code, "status": False}, {"_id": 0})
    if not promo:
        raise HTTPException(status_code=404, detail="Промокод не найден или недействителен")
    
    if promo["limited"] >= promo["limit"]:
        raise HTTPException(status_code=400, detail="Промокод исчерпан")
    
    # Check if already used
    used = await db.promo_logs.find_one({"user_id": user["id"], "promo_id": promo["id"]})
    if used:
        raise HTTPException(status_code=400, detail="Вы уже использовали этот промокод")
    
    # Check deposit requirement
    if promo["deposit_required"] and user["deposit"] == 0:
        raise HTTPException(status_code=400, detail="Промокод доступен только после депозита")
    
    reward = promo["reward"]
    await db.users.update_one({"id": user["id"]}, {"$inc": {"balance": reward}})
    await db.promos.update_one({"id": promo["id"]}, {"$inc": {"limited": 1}})
    await db.promo_logs.insert_one({
        "id": str(uuid.uuid4()),
        "user_id": user["id"],
        "promo_id": promo["id"],
        "reward": reward,
        "created_at": datetime.now(timezone.utc).isoformat()
    })
    
    user_data = await db.users.find_one({"id": user["id"]}, {"_id": 0})
    
    return {"success": True, "reward": reward, "balance": user_data["balance"]}

# ================== GAME HISTORY ==================

@api_router.get("/history/recent")
async def get_recent_history(limit: int = Query(default=10, le=50)):
    """Get recent game history for all users"""
    history = []
    
    # Get recent dice games
    dice_games = await db.dice_games.find({}, {"_id": 0}).sort("created_at", -1).limit(limit).to_list(limit)
    for g in dice_games:
        user = await db.users.find_one({"id": g["user_id"]}, {"_id": 0, "name": 1})
        if user:
            history.append({
                "game": "dice",
                "name": user["name"],
                "bet": g["bet"],
                "coefficient": round(100 / g["chance"], 2),
                "win": g["win"],
                "status": "win" if g["win"] > 0 else "lose",
                "created_at": g["created_at"]
            })
    
    # Get recent mines games
    mines_games = await db.mines_games.find({"active": False}, {"_id": 0}).sort("created_at", -1).limit(limit).to_list(limit)
    for g in mines_games:
        user = await db.users.find_one({"id": g["user_id"]}, {"_id": 0, "name": 1})
        if user:
            history.append({
                "game": "mines",
                "name": user["name"],
                "bet": g["bet"],
                "coefficient": round(g["win"] / g["bet"], 2) if g["win"] > 0 else 0,
                "win": g["win"],
                "status": "win" if g["win"] > 0 else "lose",
                "created_at": g["created_at"]
            })
    
    # Get recent bubbles games
    bubbles_games = await db.bubbles_games.find({}, {"_id": 0}).sort("created_at", -1).limit(limit).to_list(limit)
    for g in bubbles_games:
        user = await db.users.find_one({"id": g["user_id"]}, {"_id": 0, "name": 1})
        if user:
            history.append({
                "game": "bubbles",
                "name": user["name"],
                "bet": g["bet"],
                "coefficient": g["target"],
                "win": g["win"],
                "status": "win" if g["win"] > 0 else "lose",
                "created_at": g["created_at"]
            })
    
    # Sort by time and limit
    history.sort(key=lambda x: x["created_at"], reverse=True)
    return {"success": True, "history": history[:limit]}

# ================== ADMIN PANEL ==================

@api_router.post("/admin/login")
async def admin_login(data: AdminLoginRequest):
    """Admin panel login"""
    if data.password != ADMIN_PASSWORD:
        raise HTTPException(status_code=401, detail="Неверный пароль")
    
    admin_token = jwt.encode(
        {"admin": True, "exp": datetime.now(timezone.utc) + timedelta(hours=24)},
        SECRET_KEY,
        algorithm=ALGORITHM
    )
    return {"success": True, "token": admin_token}

async def verify_admin_token(credentials: HTTPAuthorizationCredentials = Depends(security)):
    """Verify admin token"""
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

@api_router.get("/admin/stats")
async def admin_stats(_: bool = Depends(verify_admin_token)):
    """Get admin statistics"""
    from datetime import date
    today = datetime.now(timezone.utc).replace(hour=0, minute=0, second=0, microsecond=0)
    week_ago = today - timedelta(days=7)
    month_ago = today - timedelta(days=30)
    
    # Payments stats
    all_payments = await db.payments.find({"status": "completed"}, {"_id": 0}).to_list(10000)
    payment_today = sum(p["amount"] for p in all_payments if p["created_at"] >= today.isoformat())
    payment_week = sum(p["amount"] for p in all_payments if p["created_at"] >= week_ago.isoformat())
    payment_month = sum(p["amount"] for p in all_payments if p["created_at"] >= month_ago.isoformat())
    payment_all = sum(p["amount"] for p in all_payments)
    
    # Withdrawals stats
    all_withdraws = await db.withdraws.find({"status": "completed"}, {"_id": 0}).to_list(10000)
    withdraw_today = sum(w["amount"] for w in all_withdraws if w["created_at"] >= today.isoformat())
    withdraw_week = sum(w["amount"] for w in all_withdraws if w["created_at"] >= week_ago.isoformat())
    withdraw_month = sum(w["amount"] for w in all_withdraws if w["created_at"] >= month_ago.isoformat())
    withdraw_all = sum(w["amount"] for w in all_withdraws)
    
    pending_withdraws = await db.withdraws.find({"status": "pending"}, {"_id": 0}).to_list(1000)
    pending_count = len(pending_withdraws)
    pending_sum = sum(w["amount"] for w in pending_withdraws)
    
    # Users stats
    all_users = await db.users.find({}, {"_id": 0, "created_at": 1}).to_list(100000)
    users_today = len([u for u in all_users if u["created_at"] >= today.isoformat()])
    users_all = len(all_users)
    
    # Bank stats
    bank = await db.bank.find_one({"id": "main"}, {"_id": 0})
    
    return {
        "success": True,
        "payments": {
            "today": payment_today,
            "week": payment_week,
            "month": payment_month,
            "all": payment_all
        },
        "withdrawals": {
            "today": withdraw_today,
            "week": withdraw_week,
            "month": withdraw_month,
            "all": withdraw_all,
            "pending_count": pending_count,
            "pending_sum": pending_sum
        },
        "users": {
            "today": users_today,
            "all": users_all
        },
        "bank": bank
    }

@api_router.get("/admin/users")
async def admin_users(
    search: Optional[str] = None,
    page: int = 1,
    limit: int = 20,
    _: bool = Depends(verify_admin_token)
):
    """Get users list"""
    query = {}
    if search:
        query = {"$or": [
            {"name": {"$regex": search, "$options": "i"}},
            {"username": {"$regex": search, "$options": "i"}},
            {"ref_link": {"$regex": search, "$options": "i"}}
        ]}
    
    skip = (page - 1) * limit
    users = await db.users.find(query, {"_id": 0}).sort("created_at", -1).skip(skip).limit(limit).to_list(limit)
    total = await db.users.count_documents(query)
    
    return {"success": True, "users": users, "total": total, "page": page, "pages": (total + limit - 1) // limit}

@api_router.get("/admin/user/{user_id}")
async def admin_get_user(user_id: str, _: bool = Depends(verify_admin_token)):
    """Get user details"""
    user = await db.users.find_one({"id": user_id}, {"_id": 0})
    if not user:
        raise HTTPException(status_code=404, detail="Пользователь не найден")
    
    # Get user logs
    logs = await db.logs.find({"user_id": user_id}, {"_id": 0}).sort("created_at", -1).limit(50).to_list(50)
    
    # Get user payments
    payments = await db.payments.find({"user_id": user_id}, {"_id": 0}).sort("created_at", -1).limit(20).to_list(20)
    
    # Get user withdrawals
    withdrawals = await db.withdraws.find({"user_id": user_id}, {"_id": 0}).sort("created_at", -1).limit(20).to_list(20)
    
    return {"success": True, "user": user, "logs": logs, "payments": payments, "withdrawals": withdrawals}

@api_router.put("/admin/user")
async def admin_update_user(data: AdminUserUpdate, _: bool = Depends(verify_admin_token)):
    """Update user"""
    update_data = {}
    if data.balance is not None:
        update_data["balance"] = data.balance
    if data.is_admin is not None:
        update_data["is_admin"] = data.is_admin
    if data.is_ban is not None:
        update_data["is_ban"] = data.is_ban
    if data.is_ban_comment is not None:
        update_data["is_ban_comment"] = data.is_ban_comment
    if data.is_drain is not None:
        update_data["is_drain"] = data.is_drain
    if data.is_drain_chance is not None:
        update_data["is_drain_chance"] = data.is_drain_chance
    
    await db.users.update_one({"id": data.user_id}, {"$set": update_data})
    return {"success": True}

@api_router.get("/admin/promos")
async def admin_promos(page: int = 1, limit: int = 20, _: bool = Depends(verify_admin_token)):
    """Get promo codes"""
    skip = (page - 1) * limit
    promos = await db.promos.find({}, {"_id": 0}).sort("created_at", -1).skip(skip).limit(limit).to_list(limit)
    total = await db.promos.count_documents({})
    return {"success": True, "promos": promos, "total": total}

@api_router.post("/admin/promo")
async def admin_create_promo(data: PromoCreate, _: bool = Depends(verify_admin_token)):
    """Create promo code"""
    existing = await db.promos.find_one({"name": data.name})
    if existing:
        raise HTTPException(status_code=400, detail="Промокод с таким названием уже существует")
    
    promo = {
        "id": str(uuid.uuid4()),
        "name": data.name,
        "reward": data.reward,
        "limit": data.limit,
        "limited": 0,
        "type": data.type,
        "deposit_required": data.deposit_required,
        "status": False,
        "created_at": datetime.now(timezone.utc).isoformat()
    }
    await db.promos.insert_one(promo)
    return {"success": True, "promo": promo}

@api_router.get("/admin/withdraws")
async def admin_withdraws(status: str = "pending", page: int = 1, limit: int = 20, _: bool = Depends(verify_admin_token)):
    """Get withdrawal requests"""
    skip = (page - 1) * limit
    withdraws = await db.withdraws.find({"status": status}, {"_id": 0}).sort("created_at", -1).skip(skip).limit(limit).to_list(limit)
    
    # Add user info
    for w in withdraws:
        user = await db.users.find_one({"id": w["user_id"]}, {"_id": 0, "name": 1, "balance": 1})
        if user:
            w["user_name"] = user["name"]
            w["user_balance"] = user["balance"]
    
    total = await db.withdraws.count_documents({"status": status})
    return {"success": True, "withdraws": withdraws, "total": total}

@api_router.put("/admin/withdraw/{withdraw_id}")
async def admin_update_withdraw(withdraw_id: str, status: str, comment: Optional[str] = None, _: bool = Depends(verify_admin_token)):
    """Update withdrawal status"""
    withdraw = await db.withdraws.find_one({"id": withdraw_id}, {"_id": 0})
    if not withdraw:
        raise HTTPException(status_code=404, detail="Вывод не найден")
    
    if status == "rejected":
        # Return funds to user
        await db.users.update_one({"id": withdraw["user_id"]}, {"$inc": {"balance": withdraw["amount"]}})
    
    await db.withdraws.update_one({"id": withdraw_id}, {"$set": {"status": status, "comment": comment}})
    return {"success": True}

@api_router.get("/admin/bank")
async def admin_get_bank(_: bool = Depends(verify_admin_token)):
    """Get bank settings"""
    bank = await db.bank.find_one({"id": "main"}, {"_id": 0})
    return {"success": True, "bank": bank}

@api_router.put("/admin/bank")
async def admin_update_bank(data: BankUpdate, _: bool = Depends(verify_admin_token)):
    """Update bank settings"""
    update_data = {k: v for k, v in data.model_dump().items() if v is not None}
    await db.bank.update_one({"id": "main"}, {"$set": update_data})
    return {"success": True}

@api_router.get("/admin/settings")
async def admin_get_settings(_: bool = Depends(verify_admin_token)):
    """Get admin settings"""
    settings = await db.admin_settings.find_one({"id": "main"}, {"_id": 0})
    return {"success": True, "settings": settings}

@api_router.put("/admin/settings")
async def admin_update_settings(
    raceback_percent: Optional[float] = None,
    ref_percent: Optional[float] = None,
    min_withdraw: Optional[float] = None,
    _: bool = Depends(verify_admin_token)
):
    """Update admin settings"""
    update_data = {}
    if raceback_percent is not None:
        update_data["raceback_percent"] = raceback_percent
    if ref_percent is not None:
        update_data["ref_percent"] = ref_percent
    if min_withdraw is not None:
        update_data["min_withdraw"] = min_withdraw
    
    await db.admin_settings.update_one({"id": "main"}, {"$set": update_data})
    return {"success": True}

# ================== SOCIAL LINKS ==================

@api_router.get("/social")
async def get_social():
    """Get social links"""
    return {
        "success": True,
        "social": {
            "telegram": "https://t.me/easymoneycaspro",
            "bot": "https://t.me/Irjeukdnr_bot"
        }
    }

# ================== STARTUP ==================

@app.on_event("startup")
async def startup():
    await init_bank()
    await init_admin_settings()
    logger.info("EASY MONEY Gaming Platform started")

@app.on_event("shutdown")
async def shutdown():
    client.close()

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
