const TelegramBot = require('node-telegram-bot-api');
const mysql       = require('mysql');
const config      = require('./config')
const { Socket }  = require('./functions/socket')

const bot = new TelegramBot(config.token, {
    polling: {
        interval: 300,
        params: {
            timeout: 10
        }
    }
})
const client = mysql.createPool({
    connectionLimit: 50,
    ...config.database
});

bot.on('message', async msg => {
    let chat_id = msg.chat.id
    let text = msg.text

    if(text && text.toLowerCase().startsWith('/bind')) {
        try {
            let id = text.split("/bind ")[1] ? text.split("/bind ")[1] : 'undefined';
            id = id.replace(/[^A-Za-z0-9\s]/gi, "");

            let user = await db(`SELECT * FROM users WHERE unique_id = '${id}'`);
            let check = await db(`SELECT * FROM users WHERE tg_id = ${chat_id}`);

            if(user.length < 1) {
                return bot.sendMessage(chat_id, 'Неверный id');
            }

            if(check.length >= 1 || user[0].tg_id != 0) {
                return bot.sendMessage(chat_id, 'Ошибка! Данный Telegram аккаунт уже привязан к другому аккаунту на сайте!');
            }

            await db(`UPDATE users SET tg_id = ${chat_id} WHERE unique_id = '${id}'`);

            Socket.emit('connectTelegram', {
                user_id: user[0].id
            })

            return bot.sendMessage(chat_id, `Вы успешно привязали свой аккаунт`);
        } catch(e) {
            return bot.sendMessage(chat_id, `Что-то пошло не так. Попробуйте ещё раз`);
        }
    }

    if(text && text.toLowerCase().startsWith('/balance')) {
        try {

            let user = await db(`SELECT * FROM users WHERE tg_id = ${chat_id}`);

            if(user.length < 1) {
                return bot.sendMessage(chat_id, 'Вы не привязали аккаунт на сайте');
            }

            let databal = await db(`SELECT balance FROM users WHERE tg_id = '${chat_id}'`);
            let balance = parseFloat(databal[0].balance);

            return bot.sendMessage(chat_id, `Ваш баланс: ${balance}₽`);
        } catch(e) {
            return bot.sendMessage(chat_id, `Что-то пошло не так. Попробуйте ещё раз`);
        }
    }

    if(text && text.toLowerCase().startsWith('/wager')) {
        try {

            let user = await db(`SELECT * FROM users WHERE tg_id = ${chat_id}`);

            if(user.length < 1) {
                return bot.sendMessage(chat_id, 'Вы не привязали аккаунт на сайте');
            }

            let datawager = await db(`SELECT wager FROM users WHERE tg_id = '${chat_id}'`);
            let wager = parseFloat(datawager[0].wager);
            if(wager === 0) {
                return bot.sendMessage(chat_id, `Поздравляем! У Вас нет отыгрыша.`);
            }

            return bot.sendMessage(chat_id, `Вам осталось отыграть: ${wager}₽\nЕсли на Вашем балансе менее 10р, то сделав депозит Вы обнулите отыгрыш`);
        } catch(e) {
            return bot.sendMessage(chat_id, `Что-то пошло не так. Попробуйте ещё раз`);
        }
    }

    return bot.sendMessage(chat_id, `
Это официальный Telegram Бот сайта <b>Lucker</b>.

Наши официальные соцсети:
Актуальный домен luc1.fun
VK: vk.com/lucker_vk
TG: t.me/lucker_tg
    `, {
        parse_mode: "HTML",
        disable_web_page_preview: true
    })
});

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
