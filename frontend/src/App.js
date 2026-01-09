import React, { useState, useEffect, createContext, useContext } from "react";
import { BrowserRouter, Routes, Route, Link, useNavigate, useLocation, Navigate } from "react-router-dom";
import axios from "axios";
import { Toaster, toast } from "sonner";
import "@/App.css";

const BACKEND_URL = process.env.REACT_APP_BACKEND_URL;
const API = `${BACKEND_URL}/api`;

// Auth Context
const AuthContext = createContext(null);

export const useAuth = () => useContext(AuthContext);

const api = axios.create({ baseURL: API });

api.interceptors.request.use((config) => {
  const token = localStorage.getItem("token");
  if (token) config.headers.Authorization = `Bearer ${token}`;
  return config;
});

// Components
const Header = () => {
  const { user, logout } = useAuth();
  const [menuOpen, setMenuOpen] = useState(false);
  const navigate = useNavigate();

  return (
    <header className="header" data-testid="header">
      <div className="header-content">
        <Link to="/" className="logo" data-testid="logo-link">
          <img src="/logo.png" alt="EASY MONEY" className="logo-img" />
          <span className="logo-text">EASY MONEY</span>
        </Link>
        
        <nav className={`nav ${menuOpen ? 'open' : ''}`} data-testid="nav-menu">
          <Link to="/" className="nav-link" data-testid="nav-home">Главная</Link>
          <Link to="/mines" className="nav-link" data-testid="nav-mines">Mines</Link>
          <Link to="/dice" className="nav-link" data-testid="nav-dice">Dice</Link>
          <Link to="/bubbles" className="nav-link" data-testid="nav-bubbles">Bubbles</Link>
          <Link to="/wheel" className="nav-link" data-testid="nav-wheel">Wheel</Link>
          {user && <Link to="/bonus" className="nav-link" data-testid="nav-bonus">Бонусы</Link>}
          {user && <Link to="/ref" className="nav-link" data-testid="nav-ref">Партнёрка</Link>}
          <a href="https://t.me/easymoneycaspro" target="_blank" rel="noopener noreferrer" className="nav-link tg-link" data-testid="nav-telegram">
            <i className="fa-brands fa-telegram"></i> Telegram
          </a>
        </nav>

        <div className="header-right">
          {user ? (
            <>
              <div className="balance-box" data-testid="balance-box">
                <span className="balance-amount">{user.balance?.toFixed(2)} ₽</span>
                <button className="btn-deposit" onClick={() => navigate('/wallet')} data-testid="deposit-btn">
                  <i className="fa-solid fa-wallet"></i>
                </button>
              </div>
              <div className="user-menu" data-testid="user-menu">
                <img src={user.img || "/logo.png"} alt="" className="user-avatar" />
                <div className="user-dropdown">
                  <span className="user-name">{user.name}</span>
                  <Link to="/wallet" className="dropdown-item">Кошелёк</Link>
                  <Link to="/ref" className="dropdown-item">Партнёрка</Link>
                  <button onClick={logout} className="dropdown-item logout" data-testid="logout-btn">Выход</button>
                </div>
              </div>
            </>
          ) : (
            <button className="btn-auth" onClick={() => navigate('/login')} data-testid="login-btn">
              <i className="fa-brands fa-telegram"></i> Войти
            </button>
          )}
          <button className="menu-toggle" onClick={() => setMenuOpen(!menuOpen)} data-testid="menu-toggle">
            <i className="fa-solid fa-bars"></i>
          </button>
        </div>
      </div>
    </header>
  );
};

const Footer = () => (
  <footer className="footer" data-testid="footer">
    <div className="footer-content">
      <div className="footer-logo">
        <img src="/logo.png" alt="EASY MONEY" />
        <span>EASY MONEY</span>
      </div>
      <div className="footer-links">
        <a href="https://t.me/easymoneycaspro" target="_blank" rel="noopener noreferrer">
          <i className="fa-brands fa-telegram"></i> Telegram канал
        </a>
      </div>
      <div className="footer-copy">© 2025 EASY MONEY. Все права защищены.</div>
    </div>
  </footer>
);

const GameHistory = () => {
  const [history, setHistory] = useState([]);

  useEffect(() => {
    const fetchHistory = async () => {
      try {
        const res = await api.get('/history/recent?limit=10');
        if (res.data.success) setHistory(res.data.history);
      } catch (e) {}
    };
    fetchHistory();
    const interval = setInterval(fetchHistory, 5000);
    return () => clearInterval(interval);
  }, []);

  const gameIcons = { mines: 'fa-bomb', dice: 'fa-dice', bubbles: 'fa-circle' };
  const gameNames = { mines: 'Mines', dice: 'Dice', bubbles: 'Bubbles' };

  return (
    <div className="game-history" data-testid="game-history">
      <h3><i className="fa-solid fa-clock-rotate-left"></i> История игр</h3>
      <div className="history-list">
        {history.map((h, i) => (
          <div key={i} className={`history-item ${h.status}`} data-testid={`history-item-${i}`}>
            <div className="history-game">
              <i className={`fa-solid ${gameIcons[h.game]}`}></i>
              <span>{gameNames[h.game]}</span>
            </div>
            <div className="history-user">{h.name?.split(' ')[0]}</div>
            <div className="history-bet">{h.bet?.toFixed(2)} ₽</div>
            <div className="history-coeff">x{h.coefficient}</div>
            <div className={`history-result ${h.status}`}>
              {h.status === 'win' ? `+${h.win?.toFixed(2)}` : '0.00'} ₽
            </div>
          </div>
        ))}
      </div>
    </div>
  );
};

// Pages
const Home = () => {
  const { user } = useAuth();
  const navigate = useNavigate();

  const games = [
    { id: 'mines', name: 'Mines', icon: 'fa-bomb', desc: 'Найди все алмазы и избегай бомб!', color: '#10b981' },
    { id: 'dice', name: 'Dice', icon: 'fa-dice', desc: 'Угадай число и выиграй!', color: '#3b82f6' },
    { id: 'bubbles', name: 'Bubbles', icon: 'fa-circle', desc: 'Поймай свой множитель!', color: '#8b5cf6' }
  ];

  return (
    <div className="page home-page" data-testid="home-page">
      <div className="hero">
        <img src="/logo.png" alt="EASY MONEY" className="hero-logo" />
        <h1>EASY MONEY</h1>
        <p>Играй и выигрывай! Лучшие игры с честным RTP</p>
        {!user && (
          <button className="btn-hero" onClick={() => navigate('/login')} data-testid="hero-login-btn">
            <i className="fa-brands fa-telegram"></i> Начать играть
          </button>
        )}
      </div>

      <div className="games-grid" data-testid="games-grid">
        {games.map(g => (
          <div key={g.id} className="game-card" onClick={() => navigate(`/${g.id}`)} data-testid={`game-card-${g.id}`}>
            <div className="game-icon" style={{ background: g.color }}>
              <i className={`fa-solid ${g.icon}`}></i>
            </div>
            <h3>{g.name}</h3>
            <p>{g.desc}</p>
            <button className="btn-play">Играть</button>
          </div>
        ))}
      </div>

      <GameHistory />
    </div>
  );
};

const Login = () => {
  const { login } = useAuth();
  const navigate = useNavigate();
  const location = useLocation();
  const [loading, setLoading] = useState(false);

  const refCode = new URLSearchParams(location.search).get('ref');

  useEffect(() => {
    // Define global callback for Telegram Widget
    window.onTelegramAuth = async (tgUser) => {
      setLoading(true);
      try {
        const res = await api.post('/auth/telegram', { 
          id: tgUser.id,
          first_name: tgUser.first_name,
          last_name: tgUser.last_name || '',
          username: tgUser.username || '',
          photo_url: tgUser.photo_url || '',
          auth_date: tgUser.auth_date,
          hash: tgUser.hash,
          ref_code: refCode 
        });
        if (res.data.success) {
          login(res.data.token, res.data.user);
          toast.success('Добро пожаловать!');
          navigate('/');
        }
      } catch (e) {
        toast.error(e.response?.data?.detail || 'Ошибка авторизации');
      }
      setLoading(false);
    };

    // Load Telegram Widget script
    const script = document.createElement('script');
    script.src = 'https://telegram.org/js/telegram-widget.js?22';
    script.setAttribute('data-telegram-login', 'Irjeukdnr_bot');
    script.setAttribute('data-size', 'large');
    script.setAttribute('data-radius', '10');
    script.setAttribute('data-onauth', 'onTelegramAuth(user)');
    script.setAttribute('data-request-access', 'write');
    script.async = true;

    const container = document.getElementById('telegram-login-container');
    if (container) {
      container.innerHTML = '';
      container.appendChild(script);
    }

    return () => {
      delete window.onTelegramAuth;
    };
  }, [refCode, login, navigate]);

  const handleDemoLogin = async () => {
    setLoading(true);
    try {
      const username = `player_${Math.random().toString(36).substr(2, 6)}`;
      const res = await api.post(`/auth/demo?username=${username}${refCode ? `&ref_code=${refCode}` : ''}`);
      if (res.data.success) {
        login(res.data.token, res.data.user);
        toast.success('Добро пожаловать!');
        navigate('/');
      }
    } catch (e) {
      toast.error('Ошибка входа');
    }
    setLoading(false);
  };

  return (
    <div className="page login-page" data-testid="login-page">
      <div className="login-card">
        <img src="/logo.png" alt="EASY MONEY" className="login-logo" />
        <h2>Вход в EASY MONEY</h2>
        <p>Авторизуйтесь через Telegram для начала игры</p>
        
        <div id="telegram-login-container" className="telegram-widget" data-testid="telegram-widget">
          {/* Telegram Widget will be inserted here */}
        </div>

        <div className="login-divider"><span>или</span></div>

        <button className="btn-demo" onClick={handleDemoLogin} disabled={loading} data-testid="demo-login-btn">
          {loading ? <i className="fa-solid fa-spinner fa-spin"></i> : <i className="fa-solid fa-play"></i>}
          Демо режим (с балансом 1000₽)
        </button>

        <p className="login-note">
          <i className="fa-solid fa-shield"></i> Безопасная авторизация через Telegram
        </p>
      </div>
    </div>
  );
};

const MinesGame = () => {
  const { user, updateBalance } = useAuth();
  const navigate = useNavigate();
  const [bet, setBet] = useState(10);
  const [bombs, setBombs] = useState(5);
  const [game, setGame] = useState(null);
  const [loading, setLoading] = useState(false);
  const [cells, setCells] = useState(Array(25).fill({ status: 'hidden', type: null }));

  useEffect(() => {
    if (user) checkActiveGame();
  }, [user]);

  const checkActiveGame = async () => {
    try {
      const res = await api.get('/games/mines/current');
      if (res.data.success && res.data.active) {
        setGame(res.data);
        const newCells = Array(25).fill({ status: 'hidden', type: null });
        res.data.clicked?.forEach(c => {
          newCells[c - 1] = { status: 'opened', type: 'safe' };
        });
        setCells(newCells);
      }
    } catch (e) {}
  };

  const startGame = async () => {
    if (!user) return navigate('/login');
    if (user.balance < bet) return toast.error('Недостаточно средств');
    
    setLoading(true);
    try {
      const res = await api.post('/games/mines/play', { bet, bombs });
      if (res.data.success) {
        setGame({ active: true, bet, bombs, win: 0, clicked: [] });
        setCells(Array(25).fill({ status: 'hidden', type: null }));
        updateBalance(res.data.balance);
        toast.success('Игра началась!');
      }
    } catch (e) {
      toast.error(e.response?.data?.detail || 'Ошибка');
    }
    setLoading(false);
  };

  const pressCell = async (index) => {
    if (!game?.active || cells[index].status !== 'hidden') return;
    
    setLoading(true);
    try {
      const res = await api.post('/games/mines/press', { cell: index + 1 });
      if (res.data.success) {
        const newCells = [...cells];
        
        if (res.data.status === 'lose') {
          newCells[index] = { status: 'opened', type: 'bomb' };
          res.data.mines?.forEach(m => {
            if (m !== index + 1) newCells[m - 1] = { status: 'revealed', type: 'bomb' };
          });
          res.data.win_positions?.forEach(p => {
            newCells[p - 1] = { status: 'revealed', type: 'safe' };
          });
          setGame(null);
          toast.error('Бум! Вы проиграли');
        } else if (res.data.status === 'finish') {
          newCells[index] = { status: 'opened', type: 'safe' };
          res.data.mines?.forEach(m => {
            newCells[m - 1] = { status: 'revealed', type: 'bomb' };
          });
          setGame(null);
          updateBalance(res.data.balance);
          toast.success(`Победа! +${res.data.win?.toFixed(2)}₽`);
        } else {
          newCells[index] = { status: 'opened', type: 'safe' };
          setGame(prev => ({ ...prev, win: res.data.win, clicked: res.data.clicked }));
        }
        setCells(newCells);
      }
    } catch (e) {
      toast.error(e.response?.data?.detail || 'Ошибка');
    }
    setLoading(false);
  };

  const takeWin = async () => {
    if (!game?.active || game.win <= 0) return;
    
    setLoading(true);
    try {
      const res = await api.post('/games/mines/take');
      if (res.data.success) {
        const newCells = [...cells];
        res.data.mines?.forEach(m => {
          newCells[m - 1] = { status: 'revealed', type: 'bomb' };
        });
        res.data.win_positions?.forEach(p => {
          if (newCells[p - 1].status === 'hidden') newCells[p - 1] = { status: 'revealed', type: 'safe' };
        });
        setCells(newCells);
        setGame(null);
        updateBalance(res.data.balance);
        toast.success(`Вы забрали ${res.data.win?.toFixed(2)}₽!`);
      }
    } catch (e) {
      toast.error(e.response?.data?.detail || 'Ошибка');
    }
    setLoading(false);
  };

  return (
    <div className="page game-page mines-page" data-testid="mines-page">
      <div className="game-container">
        <div className="game-board mines-board" data-testid="mines-board">
          {cells.map((cell, i) => (
            <button
              key={i}
              className={`mines-cell ${cell.status} ${cell.type || ''}`}
              onClick={() => pressCell(i)}
              disabled={!game?.active || cell.status !== 'hidden' || loading}
              data-testid={`mines-cell-${i}`}
            >
              {cell.status !== 'hidden' && (
                cell.type === 'bomb' ? <i className="fa-solid fa-bomb"></i> : <i className="fa-solid fa-gem"></i>
              )}
            </button>
          ))}
        </div>

        <div className="game-controls" data-testid="mines-controls">
          <h2><i className="fa-solid fa-bomb"></i> Mines</h2>
          
          {!game?.active ? (
            <>
              <div className="control-group">
                <label>Ставка</label>
                <div className="bet-input">
                  <button onClick={() => setBet(Math.max(1, bet / 2))}>½</button>
                  <input type="number" value={bet} onChange={e => setBet(Math.max(1, +e.target.value))} data-testid="mines-bet-input" />
                  <button onClick={() => setBet(Math.min(user?.balance || 1000, bet * 2))}>×2</button>
                </div>
              </div>

              <div className="control-group">
                <label>Бомб: {bombs}</label>
                <input type="range" min="2" max="24" value={bombs} onChange={e => setBombs(+e.target.value)} data-testid="mines-bombs-slider" />
              </div>

              <button className="btn-start" onClick={startGame} disabled={loading} data-testid="mines-start-btn">
                {loading ? <i className="fa-solid fa-spinner fa-spin"></i> : 'Начать игру'}
              </button>
            </>
          ) : (
            <>
              <div className="game-info">
                <div className="info-item">
                  <span>Ставка</span>
                  <strong>{game.bet?.toFixed(2)} ₽</strong>
                </div>
                <div className="info-item">
                  <span>Бомб</span>
                  <strong>{game.bombs}</strong>
                </div>
                <div className="info-item highlight">
                  <span>Выигрыш</span>
                  <strong>{game.win?.toFixed(2)} ₽</strong>
                </div>
              </div>

              <button className="btn-take" onClick={takeWin} disabled={loading || game.win <= 0} data-testid="mines-take-btn">
                {loading ? <i className="fa-solid fa-spinner fa-spin"></i> : `Забрать ${game.win?.toFixed(2)} ₽`}
              </button>
            </>
          )}
        </div>
      </div>
    </div>
  );
};

const DiceGame = () => {
  const { user, updateBalance } = useAuth();
  const navigate = useNavigate();
  const [bet, setBet] = useState(10);
  const [chance, setChance] = useState(50);
  const [direction, setDirection] = useState('down');
  const [loading, setLoading] = useState(false);
  const [result, setResult] = useState(null);

  const coefficient = (100 / chance).toFixed(2);
  const threshold = direction === 'down' ? Math.floor((chance / 100) * 999999) : Math.floor(999999 - (chance / 100) * 999999);

  const play = async () => {
    if (!user) return navigate('/login');
    if (user.balance < bet) return toast.error('Недостаточно средств');
    
    setLoading(true);
    setResult(null);
    
    try {
      const res = await api.post('/games/dice/play', { bet, chance, direction });
      if (res.data.success) {
        setResult(res.data);
        updateBalance(res.data.balance);
        if (res.data.status === 'win') {
          toast.success(`Победа! +${res.data.win?.toFixed(2)}₽`);
        } else {
          toast.error('Не повезло!');
        }
      }
    } catch (e) {
      toast.error(e.response?.data?.detail || 'Ошибка');
    }
    setLoading(false);
  };

  return (
    <div className="page game-page dice-page" data-testid="dice-page">
      <div className="game-container">
        <div className="game-board dice-board" data-testid="dice-board">
          <div className="dice-display">
            <div className="dice-bar">
              <div className={`dice-zone ${direction === 'down' ? 'active' : ''}`} style={{ width: `${chance}%` }}>
                {direction === 'down' && <span>WIN</span>}
              </div>
              <div className={`dice-zone ${direction === 'up' ? 'active' : ''}`} style={{ width: `${100 - chance}%` }}>
                {direction === 'up' && <span>WIN</span>}
              </div>
              {result && (
                <div className={`dice-marker ${result.status}`} style={{ left: `${result.result / 10000}%` }}>
                  {result.result}
                </div>
              )}
            </div>
            <div className="dice-labels">
              <span>0</span>
              <span>{threshold.toLocaleString()}</span>
              <span>999999</span>
            </div>
          </div>
          
          {result && (
            <div className={`dice-result ${result.status}`} data-testid="dice-result">
              <div className="result-number">{result.result}</div>
              <div className="result-text">{result.status === 'win' ? `+${result.win?.toFixed(2)} ₽` : 'Проигрыш'}</div>
            </div>
          )}
        </div>

        <div className="game-controls" data-testid="dice-controls">
          <h2><i className="fa-solid fa-dice"></i> Dice</h2>
          
          <div className="control-group">
            <label>Ставка</label>
            <div className="bet-input">
              <button onClick={() => setBet(Math.max(1, bet / 2))}>½</button>
              <input type="number" value={bet} onChange={e => setBet(Math.max(1, +e.target.value))} data-testid="dice-bet-input" />
              <button onClick={() => setBet(Math.min(user?.balance || 1000, bet * 2))}>×2</button>
            </div>
          </div>

          <div className="control-group">
            <label>Шанс: {chance}% (x{coefficient})</label>
            <input type="range" min="1" max="95" value={chance} onChange={e => setChance(+e.target.value)} data-testid="dice-chance-slider" />
          </div>

          <div className="control-group direction-btns">
            <button className={direction === 'down' ? 'active' : ''} onClick={() => setDirection('down')} data-testid="dice-down-btn">
              <i className="fa-solid fa-arrow-down"></i> Меньше {threshold}
            </button>
            <button className={direction === 'up' ? 'active' : ''} onClick={() => setDirection('up')} data-testid="dice-up-btn">
              <i className="fa-solid fa-arrow-up"></i> Больше {999999 - threshold}
            </button>
          </div>

          <button className="btn-start" onClick={play} disabled={loading} data-testid="dice-play-btn">
            {loading ? <i className="fa-solid fa-spinner fa-spin"></i> : 'Крутить'}
          </button>
        </div>
      </div>
    </div>
  );
};

const BubblesGame = () => {
  const { user, updateBalance } = useAuth();
  const navigate = useNavigate();
  const [bet, setBet] = useState(10);
  const [target, setTarget] = useState(2);
  const [loading, setLoading] = useState(false);
  const [result, setResult] = useState(null);
  const [animating, setAnimating] = useState(false);

  const play = async () => {
    if (!user) return navigate('/login');
    if (user.balance < bet) return toast.error('Недостаточно средств');
    
    setLoading(true);
    setResult(null);
    setAnimating(true);
    
    try {
      const res = await api.post('/games/bubbles/play', { bet, target });
      
      setTimeout(() => {
        setAnimating(false);
        if (res.data.success) {
          setResult(res.data);
          updateBalance(res.data.balance);
          if (res.data.status === 'win') {
            toast.success(`Победа! +${res.data.win?.toFixed(2)}₽`);
          } else {
            toast.error(`Лопнул на x${res.data.result}`);
          }
        }
        setLoading(false);
      }, 1500);
    } catch (e) {
      setAnimating(false);
      toast.error(e.response?.data?.detail || 'Ошибка');
      setLoading(false);
    }
  };

  return (
    <div className="page game-page bubbles-page" data-testid="bubbles-page">
      <div className="game-container">
        <div className="game-board bubbles-board" data-testid="bubbles-board">
          <div className={`bubble ${animating ? 'growing' : ''} ${result?.status || ''}`}>
            <div className="bubble-inner">
              {animating ? (
                <div className="bubble-growing">
                  <i className="fa-solid fa-circle fa-beat-fade"></i>
                </div>
              ) : result ? (
                <div className="bubble-result">
                  <div className="result-mult">x{result.result}</div>
                  <div className="result-target">Цель: x{target}</div>
                </div>
              ) : (
                <div className="bubble-target">
                  <div className="target-mult">x{target.toFixed(2)}</div>
                  <div className="target-win">Выигрыш: {(bet * target).toFixed(2)} ₽</div>
                </div>
              )}
            </div>
          </div>
        </div>

        <div className="game-controls" data-testid="bubbles-controls">
          <h2><i className="fa-solid fa-circle"></i> Bubbles</h2>
          
          <div className="control-group">
            <label>Ставка</label>
            <div className="bet-input">
              <button onClick={() => setBet(Math.max(1, bet / 2))}>½</button>
              <input type="number" value={bet} onChange={e => setBet(Math.max(1, +e.target.value))} data-testid="bubbles-bet-input" />
              <button onClick={() => setBet(Math.min(user?.balance || 1000, bet * 2))}>×2</button>
            </div>
          </div>

          <div className="control-group">
            <label>Цель: x{target.toFixed(2)}</label>
            <input type="range" min="1.05" max="100" step="0.05" value={target} onChange={e => setTarget(+e.target.value)} data-testid="bubbles-target-slider" />
          </div>

          <div className="quick-targets">
            {[1.5, 2, 3, 5, 10].map(t => (
              <button key={t} onClick={() => setTarget(t)} className={target === t ? 'active' : ''}>x{t}</button>
            ))}
          </div>

          <button className="btn-start" onClick={play} disabled={loading} data-testid="bubbles-play-btn">
            {loading ? <i className="fa-solid fa-spinner fa-spin"></i> : 'Запустить'}
          </button>
        </div>
      </div>
    </div>
  );
};

const Wallet = () => {
  const { user, updateBalance } = useAuth();
  const [tab, setTab] = useState('deposit');
  const [amount, setAmount] = useState(100);
  const [wallet, setWallet] = useState('');
  const [loading, setLoading] = useState(false);
  const [history, setHistory] = useState({ payments: [], withdraws: [] });

  useEffect(() => {
    fetchHistory();
  }, []);

  const fetchHistory = async () => {
    try {
      const [payments, withdraws] = await Promise.all([
        api.get('/payment/history'),
        api.get('/withdraw/history')
      ]);
      setHistory({
        payments: payments.data.payments || [],
        withdraws: withdraws.data.withdraws || []
      });
    } catch (e) {}
  };

  const createPayment = async () => {
    setLoading(true);
    try {
      const res = await api.post('/payment/create', { amount, system: 'freekassa' });
      if (res.data.success) {
        toast.info('Платёжная система в режиме тестирования');
        // Mock complete payment
        await api.post(`/payment/mock/complete/${res.data.payment_id}`);
        const me = await api.get('/auth/me');
        if (me.data.success) updateBalance(me.data.user.balance);
        toast.success(`Баланс пополнен на ${amount}₽`);
        fetchHistory();
      }
    } catch (e) {
      toast.error(e.response?.data?.detail || 'Ошибка');
    }
    setLoading(false);
  };

  const createWithdraw = async () => {
    if (!wallet) return toast.error('Введите кошелёк');
    setLoading(true);
    try {
      const res = await api.post('/withdraw/create', { amount, wallet, system: 'qiwi' });
      if (res.data.success) {
        const me = await api.get('/auth/me');
        if (me.data.success) updateBalance(me.data.user.balance);
        toast.success('Заявка на вывод создана');
        fetchHistory();
      }
    } catch (e) {
      toast.error(e.response?.data?.detail || 'Ошибка');
    }
    setLoading(false);
  };

  return (
    <div className="page wallet-page" data-testid="wallet-page">
      <div className="wallet-card">
        <h2><i className="fa-solid fa-wallet"></i> Кошелёк</h2>
        <div className="wallet-balance">
          <span>Баланс</span>
          <strong>{user?.balance?.toFixed(2)} ₽</strong>
        </div>

        <div className="wallet-tabs">
          <button className={tab === 'deposit' ? 'active' : ''} onClick={() => setTab('deposit')} data-testid="wallet-deposit-tab">Пополнить</button>
          <button className={tab === 'withdraw' ? 'active' : ''} onClick={() => setTab('withdraw')} data-testid="wallet-withdraw-tab">Вывести</button>
        </div>

        {tab === 'deposit' ? (
          <div className="wallet-form" data-testid="deposit-form">
            <div className="form-group">
              <label>Сумма (мин. 50₽)</label>
              <input type="number" value={amount} onChange={e => setAmount(+e.target.value)} min="50" data-testid="deposit-amount" />
            </div>
            <div className="quick-amounts">
              {[100, 500, 1000, 5000].map(a => (
                <button key={a} onClick={() => setAmount(a)}>{a}₽</button>
              ))}
            </div>
            <button className="btn-submit" onClick={createPayment} disabled={loading || amount < 50} data-testid="deposit-submit">
              {loading ? <i className="fa-solid fa-spinner fa-spin"></i> : 'Пополнить'}
            </button>
            <p className="wallet-note"><i className="fa-solid fa-info-circle"></i> Платежи в тестовом режиме</p>
          </div>
        ) : (
          <div className="wallet-form" data-testid="withdraw-form">
            <div className="form-group">
              <label>Сумма (мин. 100₽)</label>
              <input type="number" value={amount} onChange={e => setAmount(+e.target.value)} min="100" data-testid="withdraw-amount" />
            </div>
            <div className="form-group">
              <label>Кошелёк</label>
              <input type="text" value={wallet} onChange={e => setWallet(e.target.value)} placeholder="Номер карты/кошелька" data-testid="withdraw-wallet" />
            </div>
            {user?.wager > 0 && (
              <p className="wallet-warning"><i className="fa-solid fa-exclamation-triangle"></i> Отыграйте вейджер: {user.wager?.toFixed(2)}₽</p>
            )}
            <button className="btn-submit" onClick={createWithdraw} disabled={loading || amount < 100 || user?.wager > 0} data-testid="withdraw-submit">
              {loading ? <i className="fa-solid fa-spinner fa-spin"></i> : 'Вывести'}
            </button>
          </div>
        )}

        <div className="wallet-history">
          <h3>История</h3>
          {(tab === 'deposit' ? history.payments : history.withdraws).map((item, i) => (
            <div key={i} className={`history-item ${item.status}`}>
              <span>{item.amount?.toFixed(2)}₽</span>
              <span className="status">{item.status === 'completed' ? 'Выполнен' : item.status === 'pending' ? 'Ожидание' : 'Отклонён'}</span>
            </div>
          ))}
        </div>
      </div>
    </div>
  );
};

const Bonus = () => {
  const { user, updateBalance } = useAuth();
  const [raceback, setRaceback] = useState(0);
  const [promoCode, setPromoCode] = useState('');
  const [loading, setLoading] = useState(false);

  useEffect(() => {
    fetchRaceback();
  }, []);

  const fetchRaceback = async () => {
    try {
      const res = await api.get('/bonus/raceback');
      if (res.data.success) setRaceback(res.data.raceback);
    } catch (e) {}
  };

  const claimRaceback = async () => {
    setLoading(true);
    try {
      const res = await api.post('/bonus/raceback/claim');
      if (res.data.success) {
        updateBalance(res.data.balance);
        setRaceback(0);
        toast.success(`Получено ${res.data.claimed?.toFixed(2)}₽`);
      }
    } catch (e) {
      toast.error(e.response?.data?.detail || 'Ошибка');
    }
    setLoading(false);
  };

  const activatePromo = async () => {
    if (!promoCode) return;
    setLoading(true);
    try {
      const res = await api.post(`/promo/activate?code=${promoCode}`);
      if (res.data.success) {
        updateBalance(res.data.balance);
        toast.success(`Промокод активирован! +${res.data.reward?.toFixed(2)}₽`);
        setPromoCode('');
      }
    } catch (e) {
      toast.error(e.response?.data?.detail || 'Промокод недействителен');
    }
    setLoading(false);
  };

  return (
    <div className="page bonus-page" data-testid="bonus-page">
      <h2><i className="fa-solid fa-gift"></i> Бонусы</h2>

      <div className="bonus-cards">
        <div className="bonus-card raceback" data-testid="raceback-card">
          <div className="bonus-icon"><i className="fa-solid fa-rotate-left"></i></div>
          <h3>Кешбэк 10%</h3>
          <p>Получите 10% от проигранных ставок при нулевом балансе</p>
          <div className="bonus-amount">{raceback?.toFixed(2)} ₽</div>
          <button onClick={claimRaceback} disabled={loading || raceback < 1 || user?.balance > 0} data-testid="claim-raceback-btn">
            {user?.balance > 0 ? 'Доступно при нулевом балансе' : 'Забрать'}
          </button>
        </div>

        <div className="bonus-card promo" data-testid="promo-card">
          <div className="bonus-icon"><i className="fa-solid fa-ticket"></i></div>
          <h3>Промокод</h3>
          <p>Введите промокод для получения бонуса</p>
          <input type="text" value={promoCode} onChange={e => setPromoCode(e.target.value)} placeholder="Введите промокод" data-testid="promo-input" />
          <button onClick={activatePromo} disabled={loading || !promoCode} data-testid="activate-promo-btn">
            Активировать
          </button>
        </div>

        <div className="bonus-card telegram" data-testid="telegram-card">
          <div className="bonus-icon"><i className="fa-brands fa-telegram"></i></div>
          <h3>Telegram канал</h3>
          <p>Подпишитесь на наш канал для получения эксклюзивных промокодов</p>
          <a href="https://t.me/easymoneycaspro" target="_blank" rel="noopener noreferrer" className="btn-telegram">
            Подписаться
          </a>
        </div>
      </div>
    </div>
  );
};

const Referral = () => {
  const { user, updateBalance } = useAuth();
  const [stats, setStats] = useState(null);
  const [loading, setLoading] = useState(false);

  useEffect(() => {
    fetchStats();
  }, []);

  const fetchStats = async () => {
    try {
      const res = await api.get('/ref/stats');
      if (res.data.success) setStats(res.data);
    } catch (e) {}
  };

  const withdrawRef = async () => {
    setLoading(true);
    try {
      const res = await api.post('/ref/withdraw');
      if (res.data.success) {
        updateBalance(res.data.balance);
        fetchStats();
        toast.success(`Выведено ${res.data.withdrawn?.toFixed(2)}₽`);
      }
    } catch (e) {
      toast.error(e.response?.data?.detail || 'Ошибка');
    }
    setLoading(false);
  };

  const copyLink = () => {
    navigator.clipboard.writeText(`${window.location.origin}/?ref=${stats?.ref_link}`);
    toast.success('Ссылка скопирована!');
  };

  return (
    <div className="page ref-page" data-testid="ref-page">
      <h2><i className="fa-solid fa-users"></i> Партнёрская программа</h2>
      <p className="ref-desc">Приглашайте друзей и получайте 50% от их депозитов!</p>

      <div className="ref-link-box" data-testid="ref-link-box">
        <label>Ваша реферальная ссылка:</label>
        <div className="ref-link">
          <input type="text" value={`${window.location.origin}/?ref=${stats?.ref_link || ''}`} readOnly />
          <button onClick={copyLink}><i className="fa-solid fa-copy"></i></button>
        </div>
      </div>

      <div className="ref-stats">
        <div className="ref-stat">
          <i className="fa-solid fa-user-plus"></i>
          <div className="stat-value">{stats?.referalov || 0}</div>
          <div className="stat-label">Рефералов</div>
        </div>
        <div className="ref-stat">
          <i className="fa-solid fa-coins"></i>
          <div className="stat-value">{stats?.income?.toFixed(2) || '0.00'} ₽</div>
          <div className="stat-label">Доступно</div>
        </div>
        <div className="ref-stat">
          <i className="fa-solid fa-chart-line"></i>
          <div className="stat-value">{stats?.income_all?.toFixed(2) || '0.00'} ₽</div>
          <div className="stat-label">Всего заработано</div>
        </div>
      </div>

      <button className="btn-withdraw-ref" onClick={withdrawRef} disabled={loading || (stats?.income || 0) < 10} data-testid="withdraw-ref-btn">
        {loading ? <i className="fa-solid fa-spinner fa-spin"></i> : `Вывести ${stats?.income?.toFixed(2) || '0.00'} ₽`}
      </button>
      <p className="ref-note">Минимум для вывода: 10₽</p>
    </div>
  );
};

// Admin Panel
const AdminLogin = () => {
  const [password, setPassword] = useState('');
  const [loading, setLoading] = useState(false);
  const navigate = useNavigate();

  const login = async () => {
    setLoading(true);
    try {
      const res = await api.post('/admin/login', { password });
      if (res.data.success) {
        localStorage.setItem('adminToken', res.data.token);
        navigate('/apminpannelonlyadmins/dashboard');
      }
    } catch (e) {
      toast.error('Неверный пароль');
    }
    setLoading(false);
  };

  return (
    <div className="admin-login" data-testid="admin-login">
      <div className="admin-login-card">
        <img src="/logo.png" alt="EASY MONEY" />
        <h2>Админ панель</h2>
        <input type="password" value={password} onChange={e => setPassword(e.target.value)} placeholder="Пароль" data-testid="admin-password" />
        <button onClick={login} disabled={loading} data-testid="admin-login-btn">
          {loading ? <i className="fa-solid fa-spinner fa-spin"></i> : 'Войти'}
        </button>
      </div>
    </div>
  );
};

const AdminDashboard = () => {
  const [stats, setStats] = useState(null);
  const [users, setUsers] = useState([]);
  const [withdraws, setWithdraws] = useState([]);
  const [promos, setPromos] = useState([]);
  const [tab, setTab] = useState('stats');
  const [search, setSearch] = useState('');
  const [newPromo, setNewPromo] = useState({ name: '', reward: 100, limit: 100, type: 0, deposit_required: false });
  const navigate = useNavigate();

  const adminApi = axios.create({ baseURL: API });
  adminApi.interceptors.request.use((config) => {
    const token = localStorage.getItem('adminToken');
    if (token) config.headers.Authorization = `Bearer ${token}`;
    return config;
  });

  useEffect(() => {
    const token = localStorage.getItem('adminToken');
    if (!token) navigate('/apminpannelonlyadmins');
    fetchData();
  }, [tab]);

  const fetchData = async () => {
    try {
      if (tab === 'stats') {
        const res = await adminApi.get('/admin/stats');
        if (res.data.success) setStats(res.data);
      } else if (tab === 'users') {
        const res = await adminApi.get(`/admin/users?search=${search}`);
        if (res.data.success) setUsers(res.data.users);
      } else if (tab === 'withdraws') {
        const res = await adminApi.get('/admin/withdraws');
        if (res.data.success) setWithdraws(res.data.withdraws);
      } else if (tab === 'promos') {
        const res = await adminApi.get('/admin/promos');
        if (res.data.success) setPromos(res.data.promos);
      }
    } catch (e) {
      if (e.response?.status === 401) {
        localStorage.removeItem('adminToken');
        navigate('/apminpannelonlyadmins');
      }
    }
  };

  const updateWithdraw = async (id, status) => {
    try {
      await adminApi.put(`/admin/withdraw/${id}?status=${status}`);
      toast.success('Обновлено');
      fetchData();
    } catch (e) {
      toast.error('Ошибка');
    }
  };

  const createPromo = async () => {
    try {
      await adminApi.post('/admin/promo', newPromo);
      toast.success('Промокод создан');
      setNewPromo({ name: '', reward: 100, limit: 100, type: 0, deposit_required: false });
      fetchData();
    } catch (e) {
      toast.error(e.response?.data?.detail || 'Ошибка');
    }
  };

  const logout = () => {
    localStorage.removeItem('adminToken');
    navigate('/apminpannelonlyadmins');
  };

  return (
    <div className="admin-dashboard" data-testid="admin-dashboard">
      <div className="admin-sidebar">
        <div className="admin-logo">
          <img src="/logo.png" alt="EASY MONEY" />
          <span>Admin</span>
        </div>
        <nav>
          <button className={tab === 'stats' ? 'active' : ''} onClick={() => setTab('stats')}><i className="fa-solid fa-chart-pie"></i> Статистика</button>
          <button className={tab === 'users' ? 'active' : ''} onClick={() => setTab('users')}><i className="fa-solid fa-users"></i> Пользователи</button>
          <button className={tab === 'withdraws' ? 'active' : ''} onClick={() => setTab('withdraws')}><i className="fa-solid fa-money-bill-transfer"></i> Выводы</button>
          <button className={tab === 'promos' ? 'active' : ''} onClick={() => setTab('promos')}><i className="fa-solid fa-ticket"></i> Промокоды</button>
          <button onClick={logout}><i className="fa-solid fa-sign-out"></i> Выход</button>
        </nav>
      </div>

      <div className="admin-content">
        {tab === 'stats' && stats && (
          <div className="admin-stats" data-testid="admin-stats">
            <h2>Статистика</h2>
            <div className="stats-grid">
              <div className="stat-card">
                <h4>Депозиты сегодня</h4>
                <div className="stat-value">{stats.payments.today?.toFixed(2)} ₽</div>
              </div>
              <div className="stat-card">
                <h4>Депозиты за неделю</h4>
                <div className="stat-value">{stats.payments.week?.toFixed(2)} ₽</div>
              </div>
              <div className="stat-card">
                <h4>Депозиты всего</h4>
                <div className="stat-value">{stats.payments.all?.toFixed(2)} ₽</div>
              </div>
              <div className="stat-card">
                <h4>Выводы сегодня</h4>
                <div className="stat-value">{stats.withdrawals.today?.toFixed(2)} ₽</div>
              </div>
              <div className="stat-card">
                <h4>Ожидающие выводы</h4>
                <div className="stat-value">{stats.withdrawals.pending_count} ({stats.withdrawals.pending_sum?.toFixed(2)} ₽)</div>
              </div>
              <div className="stat-card">
                <h4>Пользователей сегодня</h4>
                <div className="stat-value">{stats.users.today}</div>
              </div>
              <div className="stat-card">
                <h4>Всего пользователей</h4>
                <div className="stat-value">{stats.users.all}</div>
              </div>
            </div>

            <h3>Банк игр</h3>
            <div className="bank-stats">
              <div className="bank-item">
                <span>Dice</span>
                <strong>{stats.bank?.dice?.toFixed(2)} ₽</strong>
              </div>
              <div className="bank-item">
                <span>Mines</span>
                <strong>{stats.bank?.mines?.toFixed(2)} ₽</strong>
              </div>
              <div className="bank-item">
                <span>Bubbles</span>
                <strong>{stats.bank?.bubbles?.toFixed(2)} ₽</strong>
              </div>
            </div>
          </div>
        )}

        {tab === 'users' && (
          <div className="admin-users" data-testid="admin-users">
            <h2>Пользователи</h2>
            <input type="text" value={search} onChange={e => setSearch(e.target.value)} onKeyUp={e => e.key === 'Enter' && fetchData()} placeholder="Поиск..." />
            <table>
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Имя</th>
                  <th>Баланс</th>
                  <th>Депозит</th>
                  <th>Рефералы</th>
                  <th>Дата</th>
                </tr>
              </thead>
              <tbody>
                {users.map(u => (
                  <tr key={u.id}>
                    <td>{u.id.slice(0, 8)}</td>
                    <td>{u.name}</td>
                    <td>{u.balance?.toFixed(2)} ₽</td>
                    <td>{u.deposit?.toFixed(2)} ₽</td>
                    <td>{u.referalov}</td>
                    <td>{new Date(u.created_at).toLocaleDateString()}</td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        )}

        {tab === 'withdraws' && (
          <div className="admin-withdraws" data-testid="admin-withdraws">
            <h2>Заявки на вывод</h2>
            <table>
              <thead>
                <tr>
                  <th>Пользователь</th>
                  <th>Сумма</th>
                  <th>Кошелёк</th>
                  <th>Баланс</th>
                  <th>Действия</th>
                </tr>
              </thead>
              <tbody>
                {withdraws.map(w => (
                  <tr key={w.id}>
                    <td>{w.user_name}</td>
                    <td>{w.amount?.toFixed(2)} ₽</td>
                    <td>{w.wallet}</td>
                    <td>{w.user_balance?.toFixed(2)} ₽</td>
                    <td>
                      <button className="btn-approve" onClick={() => updateWithdraw(w.id, 'completed')}>✓</button>
                      <button className="btn-reject" onClick={() => updateWithdraw(w.id, 'rejected')}>✗</button>
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        )}

        {tab === 'promos' && (
          <div className="admin-promos" data-testid="admin-promos">
            <h2>Промокоды</h2>
            <div className="promo-form">
              <input type="text" value={newPromo.name} onChange={e => setNewPromo({...newPromo, name: e.target.value})} placeholder="Название" />
              <input type="number" value={newPromo.reward} onChange={e => setNewPromo({...newPromo, reward: +e.target.value})} placeholder="Награда" />
              <input type="number" value={newPromo.limit} onChange={e => setNewPromo({...newPromo, limit: +e.target.value})} placeholder="Лимит" />
              <button onClick={createPromo}>Создать</button>
            </div>
            <table>
              <thead>
                <tr>
                  <th>Название</th>
                  <th>Награда</th>
                  <th>Использовано</th>
                  <th>Лимит</th>
                </tr>
              </thead>
              <tbody>
                {promos.map(p => (
                  <tr key={p.id}>
                    <td>{p.name}</td>
                    <td>{p.reward} ₽</td>
                    <td>{p.limited}</td>
                    <td>{p.limit}</td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        )}
      </div>
    </div>
  );
};

// Protected Route
const ProtectedRoute = ({ children }) => {
  const { user, loading } = useAuth();
  if (loading) return <div className="loading"><i className="fa-solid fa-spinner fa-spin"></i></div>;
  if (!user) return <Navigate to="/login" />;
  return children;
};

// Main App
function App() {
  const [user, setUser] = useState(null);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    checkAuth();
  }, []);

  const checkAuth = async () => {
    const token = localStorage.getItem('token');
    if (token) {
      try {
        const res = await api.get('/auth/me');
        if (res.data.success) setUser(res.data.user);
      } catch (e) {
        localStorage.removeItem('token');
      }
    }
    setLoading(false);
  };

  const login = (token, userData) => {
    localStorage.setItem('token', token);
    setUser(userData);
  };

  const logout = () => {
    localStorage.removeItem('token');
    setUser(null);
  };

  const updateBalance = (newBalance) => {
    setUser(prev => prev ? { ...prev, balance: newBalance } : null);
  };

  return (
    <AuthContext.Provider value={{ user, loading, login, logout, updateBalance }}>
      <BrowserRouter>
        <div className="App">
          <Toaster position="top-right" richColors />
          <Routes>
            <Route path="/apminpannelonlyadmins" element={<AdminLogin />} />
            <Route path="/apminpannelonlyadmins/dashboard" element={<AdminDashboard />} />
            <Route path="/*" element={
              <>
                <Header />
                <main>
                  <Routes>
                    <Route path="/" element={<Home />} />
                    <Route path="/login" element={<Login />} />
                    <Route path="/mines" element={<MinesGame />} />
                    <Route path="/dice" element={<DiceGame />} />
                    <Route path="/bubbles" element={<BubblesGame />} />
                    <Route path="/wallet" element={<ProtectedRoute><Wallet /></ProtectedRoute>} />
                    <Route path="/bonus" element={<ProtectedRoute><Bonus /></ProtectedRoute>} />
                    <Route path="/ref" element={<ProtectedRoute><Referral /></ProtectedRoute>} />
                  </Routes>
                </main>
                <Footer />
              </>
            } />
          </Routes>
        </div>
      </BrowserRouter>
    </AuthContext.Provider>
  );
}

export default App;
