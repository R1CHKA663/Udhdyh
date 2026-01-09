const TelegramBot = require('node-telegram-bot-api');
const mysql = require('mysql');
const nodeCron = require("node-cron");
const request = require('requestify');

const bot = new TelegramBot("6682977057:AAGzYFHGkCBhTgMKl0m-x6fDH-3qEO5Qx-o", {
    polling: {
        interval: 300,
        autoStart: true,
        params: {
            timeout: 10
        }
    }
})
const client = mysql.createPool({
    connectionLimit: 50,
    host: "localhost",
    user: "root",
    database: "baza",
    password: "62q81K"
});

bot.on('message', async msg => {

    let chat_id = msg.chat.id,
        text = msg.text ? msg.text : '',
        settings = await db('SELECT * FROM settings ORDER BY id DESC');

    if(!text) return bot.sendMessage(chat_id, 'Сообщение не должно содержать картинки / смайлики / стикеры');

    if(text.toLowerCase() === '/start') {
        return bot.sendMessage(chat_id, `Привет!\nДля того, чтобы получить бонус, необходимо:\n\n1. 👉 Подписаться на <a href="https://t.me/goldenxvip">канал</a>\n2. 👉 Ввести команду, полученную на сайте`, {
            parse_mode: "HTML"
        });
    }

    else if(text.toLowerCase().startsWith('/bind')) {
        let id = text.split("/bind ")[1] ? text.split("/bind ")[1]  : 'undefined';
        id = id.replace(/[^a-z0-9\s]/gi);
        let user = await db(`SELECT * FROM users WHERE id = '${id}'`);
        let check = await db(`SELECT * FROM users WHERE tg_id = ${chat_id}`);
        let subs = await bot.getChatMember('@goldenxvip', chat_id).catch((err) => {});

        if (!subs || subs.status == 'left' || subs.status == undefined) {
            return bot.sendMessage(chat_id, `Вы не подписались на <a href="https://t.me/goldenxvip">канал</a>`, {
                parse_mode: "HTML",
                disable_web_page_preview: true
            });
        }
        if(user.length < 1) return bot.sendMessage(chat_id, 'Пользователь не найден', {
            parse_mode: "HTML"
        });
        if(check.length >= 1) return bot.sendMessage(chat_id, 'Этот Telegram аккаунт уже привязан');
        if(user[0].bonus_2 == 1) return bot.sendMessage(chat_id, 'Пользователю уже было начислено вознаграждение');
        console.log(user);

        await db(`UPDATE users SET tg_id = ${chat_id}, bonus_2 = 2 WHERE id = '${id}'`);

        return bot.sendMessage(chat_id, `😎 Спасибо за подписку, ${user[0].name}!\n\nТеперь вы можете получить бонус на сайте.`);
    }

    return bot.sendMessage(chat_id, 'Команда не распознана', {
        reply_to_message_id: msg.message_id
    });
});

function makeIdentify(length) {
    var result = "";
    var characters = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
    var charactersLength = characters.length;
    for (var i = 0; i < length; i++) {
        result += characters.charAt(Math.floor(Math.random() * charactersLength));
    }
    return result;
}

function db(databaseQuery) {
    return new Promise(data => {
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
    client.end()
}
