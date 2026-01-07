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
  },
  server = require("https").createServer(options, app),
  //server = require('http').createServer(app),
  io = require("socket.io")(server, {
    cors: {
      origin: "https://kaifuli.cash",
    },
  }),
  axios = require("axios");
const TelegramBot = require("node-telegram-bot-api");
const mysql = require("mysql");
const { isObject } = require("lodash");
const Redis = require("redis");
var RedisClient = Redis.createClient();
RedisClient.subscribe("newPayment");

const bot = new TelegramBot("токен_бота_тг", {
  polling: {
    interval: 300,
    autoStart: true,
    params: {
      timeout: 10,
    },
  },
});
const client = mysql.createPool({
  connectionLimit: 50,
  host: "localhost",
  user: "root",
  database: "kaif",
  password: "OIOIERDKFSKDS",
});
RedisClient.on("message", async (channel, message) => {
  var messages = JSON.parse(message);
  if (channel == "newPayment") {
    bot.sendMessage(
      1087322058,
      "🔥 Новый депозит -" + messages.amount + " рублей"
    );
    return bot.sendMessage(
      5532004247,
      "🔥 Новый депозит -" + messages.amount + " рублей"
    );
  }
});
bot.on("message", async (msg) => {
  let chat_id = msg.chat.id,
    text = msg.text ? msg.text : "";
  if (text === "/top") {
    let check = await db(`SELECT * FROM users WHERE tg_id = ${chat_id}`);
    if (check.length == 0)
      return bot.sendMessage(chat_id, "Привяжите телеграм к аккаунту на сайте");
    const top = await db(
      `select * FROM users order by contest_ref desc limit 10`
    );
    return bot.sendMessage(
      chat_id,
      `💎 ТОП РЕФЕВОДОВ

🥇 ${top[0].name} - 1000 💰Рефералов - ${top[0].contest_ref}
🥈${top[1].name} - 750 💰 Рефералов - ${top[1].contest_ref}
🥉${top[2].name} - 500 💰 Рефералов - ${top[2].contest_ref}
4️⃣ ${top[3].name}  - 400 💰 Рефералов - ${top[3].contest_ref}
5️⃣ ${top[4].name}  - 300 💰 Рефералов - ${top[4].contest_ref}
6️⃣ ${top[5].name}  - 200 💰 Рефералов - ${top[5].contest_ref}
7️⃣ ${top[6].name}  - 100 💰 Рефералов - ${top[6].contest_ref}
8️⃣ ${top[7].name}  - 100 💰 Рефералов - ${top[7].contest_ref}
9️⃣  ${top[8].name}  - 100 💰 Рефералов - ${top[8].contest_ref}
🔟 ${top[9].name}  - 100 💰 Рефералов - ${top[9].contest_ref}

📌 У вас рефералов - ${check[0].contest_ref}
📎 Учитываются только новые рефералы
❓ Конкурс завершен, победили определены.`,
      {
        parse_mode: "HTML",
        disable_web_page_preview: true,
      }
    );
  }
  const bonus = await db(`SELECT * FROM users WHERE tg_id = '${chat_id}'`);
  const textEtap = `Для привязки Telegram аккаунта, требуется следующее:\n1.Подписаться на наш <a href="https://t.me/kaifuli_play">канал</a>\n2. Ввести команду -- пример: /link id`;
  if (bonus.length) {
    return bot.sendMessage(
      chat_id,
      "✅ Вы уже привязывали свой аккаунт, приятных игр!"
    );
  }

  if (text.toLowerCase() === "/start") {
    return bot.sendMessage(chat_id, textEtap, {
      parse_mode: "HTML",
      disable_web_page_preview: true,
    });
  } else if (text.toLowerCase().startsWith("/link")) {
    var id = text.split("/link ")[1] ? text.split("/link ")[1] : "undefined";
    id = String(id);
    let user = await db(`SELECT * FROM users WHERE ref_link = '${id}'`);
    let check = await db(`SELECT * FROM users WHERE tg_id = ${chat_id}`);
    let subs = await bot
      .getChatMember("@kaifuli_play", chat_id)
      .catch((err) => {});

    if (!subs || subs.status == "left" || subs.status == undefined) {
      return bot.sendMessage(
        chat_id,
        `Вы не подписаны на <a href="https://t.me/kaifuli_play">канал</a>`,
        {
          parse_mode: "HTML",
          disable_web_page_preview: true,
        }
      );
    }
    if (user.length < 1)
      return bot.sendMessage(chat_id, "Мы не нашли этого пользователя", {
        parse_mode: "HTML",
      });
    if (check.length >= 1)
      return bot.sendMessage(chat_id, "Этот аккаунт уже привязан");
    if (user[0].tg_bonus_use == 1)
      return bot.sendMessage(chat_id, "Пользователь уже получил награду");

    //io.sockets.emit("bindTg", { user_id: id });
    await db(`UPDATE users SET tg_id = ${chat_id} WHERE ref_link = '${id}'`);
    return bot.sendMessage(chat_id, `✅ Ваш аккаунт успешно привязан`);
  }
  return bot.sendMessage(chat_id, textEtap, {
    parse_mode: "HTML",
    disable_web_page_preview: true,
  });
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
