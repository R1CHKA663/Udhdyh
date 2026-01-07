# EASY MONEY - Gaming Platform

## Original Problem Statement
Создание игровой платформы EASY MONEY на основе PHP проекта, переписанного на FastAPI + React + MongoDB для работы на Emergent. 
- Название: EASY MONEY
- Год: 2025
- Telegram канал: https://t.me/easymoneycaspro
- Telegram бот для авторизации: @Irjeukdnr_bot
- Админ панель: /apminpannelonlyadmins (пароль: easymoney2025admin)

## User Personas
1. **Игрок** - пользователь, играющий в Mines, Dice, Bubbles
2. **Реферер** - пользователь, приглашающий других (получает 50% от депозитов)
3. **Администратор** - управление платформой через админ-панель

## Core Requirements (Static)
- Авторизация через Telegram
- 3 игры: Mines, Dice, Bubbles с честным RTP
- Реферальная система (50% от депозита реферала)
- Кешбэк 10% при нулевом балансе
- Админ панель с защитой паролем
- Платежные системы (MOCK - FreeKassa, LinePay, QIWI)

## What's Been Implemented (2025-01-07)
- ✅ Полный бэкенд на FastAPI с MongoDB
- ✅ Игры Mines, Dice, Bubbles с RTP и банком
- ✅ Авторизация через Telegram (+ демо-режим)
- ✅ Реферальная система 50%
- ✅ Кешбэк система 10%
- ✅ Админ панель с паролем (/apminpannelonlyadmins)
- ✅ Статистика в админке (депозиты, выводы, пользователи, банк игр)
- ✅ Создание промокодов
- ✅ Управление выводами
- ✅ Логотип EASY MONEY
- ✅ Ссылка на Telegram канал

## Prioritized Backlog
### P0 (Critical)
- [x] Базовые игры с RTP
- [x] Авторизация
- [x] Админ панель

### P1 (High)
- [ ] Реальная интеграция платежных систем (FreeKassa/LinePay/QIWI)
- [ ] Реальная Telegram авторизация (настроить домен в BotFather)

### P2 (Medium)
- [ ] Jackpot игра
- [ ] X100 игра (crash)
- [ ] Более детальная статистика в админке
- [ ] История входов пользователей

## Next Tasks
1. Настроить реальный домен для Telegram Login Widget
2. Интегрировать реальные платежные системы с merchant ID
3. Добавить игру Jackpot
4. Расширить функционал админ-панели
