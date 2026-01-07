const { Socket, io } = require('./functions/socket')
const RedisClient    = require('./functions/redis')
const config         = require('./config')
const bot            = require('./telegram')
const axios          = require('axios')

var slotsHistory = [];

let games = [],
    timerBot = null
    interval = null;

RedisClient.subscribe('newGame')
RedisClient.subscribe('setNewBotTimer');
RedisClient.subscribe('slotsHistory');

RedisClient.on('message', async (channel, message) => {
    if(channel == 'setNewBotTimer') {
        clearInterval(interval);
        interval = null;
        timerBot = message;

        startBot();
    }

    if(channel == 'newGame') {
        if(games.length >= 14) {
            games.pop()
        }
        games.unshift(JSON.parse(message))
        Socket.emit(channel, JSON.parse(message))
    }

    if(channel == "slotsHistory") {
        let data = JSON.parse(message);
        slotsHistory.unshift(data);
        if(slotsHistory.length > 7) slotsHistory.pop();
        io.sockets.emit("slotsHistory", data);
        return;
    }
})

io.on('connection', (socket) => {
    const updateOnline = () => {
        Socket.emit('online', Object.keys(io.sockets.adapter.rooms).length + 0);
    };

    socket.on('disconnect', () => {
        updateOnline();
    });

    socket.on("getHistory", () => {
        socket.emit("getHistory", slotsHistory);
    });

    socket.emit('history', games)
    updateOnline();
})

const startBot = () => {
    interval = setInterval(() => {
        axios.post(`${config.domain}/api/fake`)
            .then(res => {

            })
            .catch(err => {})
    }, timerBot);
};

const getTimer = () => {
    axios.post(`${config.domain}/api/getTimer`)
        .then(res => {
            timerBot = res.data;

            startBot();
        })
        .catch(err => {})
};

getTimer()
