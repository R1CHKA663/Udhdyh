const app = require("express")(),
    fs = require("fs"),
    options = {
        key: fs.readFileSync(
            "/etc/letsencrypt/live/kaifuli.cash/privkey.pem",
            "utf8"
        ),
        cert: fs.readFileSync(
            "/etc/letsencrypt/live/kaifuli.cash/fullchain.pem",
            "utf8"
        ),
    };

const server = require("https").createServer(options, app);
//server = require('http').createServer(app),
const io = require("socket.io")(server, {
        cors: {
            origin: "https://kaifuli.cash",
            //  credentials: true,
        },
    }),
    axios = require("axios");
const mysql = require("mysql");
const requestify = require("requestify");

server.listen(8443);
const client = mysql.createPool({
    connectionLimit: 50,
    host: "localhost",
    user: "root",
    database: "",
    password: "",
});

const { result } = require("lodash");
const Redis = require("redis");
var RedisClient = Redis.createClient();

RedisClient.subscribe("newGame");
RedisClient.subscribe("VKontakte");
RedisClient.subscribe("newBetJackpot");
RedisClient.subscribe("jackpotesWin");

var date = 0;
var history = [];
var i = 0;
var getJackpot = {
    listBet: [],
    user_chance: [],
    start: false,
    time: 0,
};
var bet = 0;

RedisClient.on("message", async (channel, message) => {
    var messages = JSON.parse(message);
    if (channel == "jackpotesWin") {
        io.sockets.emit("jackpotesWin", messages);
    }
    if (channel == "newBetJackpot") {
        bet++;
        messages.id = bet;
        var name = messages.name;
        name = name.split(" ");
        if (name[0].length > 5) name[0] = name[0].substring(0, 5);
        messages.name = `${name[0]} ${name[1][0]}.`;
        messages.chance = 0;

        getJackpot.listBet.push(messages);
        getJackpot.user_chance = messages.user_chance;

        io.sockets.emit("newBetJackpot", messages);
    }
    if (channel == "newGame") {
        i++;
        messages.id = i;
        var name = messages.name;
        name = name.split(" ");
        messages.name = `${name[0]} ${name[1][0]}.`;
        if (messages.result == "win" && Date.now() > date) {
            history.unshift(messages);
            history.splice(10, 1);
            date = Date.now() + 600;
            io.sockets.emit("newHistory", messages);
        }
    }
    if (channel == "VKontakte") {
        console.log(JSON.parse(message));
        return;
    }
});

var online = 30;
var $ipsConnected = [];
setInterval(() => {
    io.sockets.emit("online", online);
}, 1000);

io.on("connection", (socket) => {
    socket.emit("getHistory", { history });

    socket.on("getHistory", () => {
        socket.emit("getHistory", { history });
    });
    socket.on("getJackpot", () => {
        socket.emit("getJackpot", { getJackpot });
    });
    var $ipAddress = socket.handshake.address;
    if (!$ipsConnected.hasOwnProperty($ipAddress)) {
        $ipsConnected[$ipAddress] = 1;

        online++;

        socket.emit("online", online);
    }
    socket.on("disconnect", () => {
        if ($ipsConnected.hasOwnProperty($ipAddress)) {
            delete $ipsConnected[$ipAddress];

            online--;

            socket.emit("online", online);
        }
    });
});
RedisClient.on("connect", function () {
    console.log("Connected!");
});
function db(databaseQuery) {
    return new Promise((data) => {
        client.query(databaseQuery, function (error, result) {
            if (error) {
                console.log(error);
                throw error;
            }
            try {
                data(result);
            } catch (error) {
                data({});
                throw error;
            }
        });
    });
    client.end();
}
const config = {
    domain: "https://kaifuli.cash",
};
function log(e) {
    console.log(e);
}
function endTime() {
    var seconds = 25;
    const gg = setInterval(async () => {
        await db("UPDATE jackpot_status SET status = 1");
        seconds -= 1;
        getJackpot.time = 20;
        if (seconds < 20) getJackpot.time = seconds;
        if (seconds <= 0) {
            getJackpot = {
                listBet: [],
                user_chance: [],
                start: false,
                time: 0,
            };
            clearInterval(gg);
            startSliderJackpot();
            return (game.jackpot = false);
        }
        var title = "Игра идет";
        if (seconds < 10) seconds = "0" + seconds;
        if (seconds <= 5) {
            title = "Победитель определен";
        }
        io.sockets.emit("jackTime", {
            time: String(seconds),
            title: title,
        });
    }, 1000);
}
function startTime() {
    var seconds = 25;
    const gg = setInterval(async () => {
        seconds -= 1;
        if (seconds <= 3) await db("UPDATE jackpot_status SET status = 1");
        if (seconds <= 0) {
            getSliderJackpot();
            clearInterval(gg);
            db("UPDATE jackpot_status SET status = 1");
        }
        if (seconds < 10) seconds = "0" + seconds;
        console.log(seconds);
        io.sockets.emit("jackTime", {
            time: String(seconds),
            title: "Прием ставок",
        });
    }, 1000);
}
async function addMoneyWinner(winUser) {
    setTimeout(() => {
        requestify
            .post(config.domain + "/api/jackpot/addCash", { winUser })
            .then(
                function (res) {
                    res = JSON.parse(res.body);
                    io.sockets.emit("winner", {
                        win: winUser.bank,
                        user_id: winUser.user_id,
                    });
                },
                function (res) {
                    console.log(res);
                    log("Ошибка в функции addCash");
                }
            );
    }, 25000);
}
async function getSliderJackpot() {
    requestify.post(config.domain + "/api/jackpot/getSlider").then(
        function (res) {
            res = JSON.parse(res.body);
            getJackpot.start = res;
            io.sockets.emit("startSliderJackpot", res);
            addMoneyWinner(res.winUser);
            endTime();
        },
        function (res) {
            console.log(res);
            startSliderJackpot();
            log("Ошибка в функции getSlider");
        }
    );
}
var game = {
    jackpot: false,
};
async function startSliderJackpot() {
    const eblan = setInterval(async () => {
        await db("UPDATE jackpot_status SET status = 0");
        if (game.jackpot) return;
        await requestify.post(config.domain + "/api/jackpot/startGame").then(
            function (res) {
                res = JSON.parse(res.body);
                console.log(res.game);
                if (game.jackpot) return;
                if (res.game) {
                    startTime();
                    clearInterval(eblan);
                    return (game.jackpot = true);
                }
                return;
                io.sockets.emit("startSliderJackpot", res);
                endTime();
            },
            function (res) {
                console.log(res);
                startSliderJackpot();
                log("Ошибка в функции startSlider");
            }
        );
    }, 1000);
}
startSliderJackpot();

setInterval(async () => {
    const result = await db("SELECT * FROM gameJackpotes WHERE id = 1");

    io.sockets.emit("jackpotGameSum", result[0].sum);
}, 500);
