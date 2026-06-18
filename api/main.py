import os
from flask import Flask, request
import telebot

API_TOKEN = "8674272812:AAGWbN0aW-9PchTb3zb9ciFE8Q59PxK898Y"
bot = telebot.TeleBot(API_TOKEN, threaded=False)

app = Flask(__name__)

# Telegram'dan keladigan xabarlarni qabul qilish
@app.route('/', methods=['POST'])
def webhook():
    if request.headers.get('content-type') == 'application/json':
        json_string = request.get_data().decode('utf-8')
        update = telebot.types.Update.de_json(json_string)
        bot.process_new_updates([update])
        return ''
    else:
        return 'Ajoyib! Bot ishlamoqda.', 403

# /start buyrug'i uchun javob
@bot.message_handler(commands=['start'])
def send_welcome(message):
    caption_text = (
        "Assalomu alaykum! 🍿\n\n"
        "Ushbu bot orqali siz istalgan kinongizni tez va oson topishingiz mumkin.\n\n"
        "Botdan foydalanish uchun:\n"
        "'Start' tugmasini bosing.\n"
        "Kino kodini yoki nomini yuboring.\n"
        "Sifatni tanlang va yuklab oling!\n\n"
        "Kino kodlari: https://t.me/tarjimatvv\n\n"
        "Maroqli dam oling! ✨"
    )
    photo_url = "https://images.unsplash.com/photo-1489599849927-2ee91cede3ba"

    try:
        bot.send_photo(message.chat.id, photo_url, caption=caption_text)
    except Exception:
        bot.send_message(message.chat.id, caption_text)

# Brauzerda tekshirish uchun ochiq manzil
@app.route('/', methods=['GET'])
def index():
    return "Bot muvaffaqiyatli ishga tushdi!"
