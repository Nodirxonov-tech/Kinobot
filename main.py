import os
import asyncio
from aiogram import Bot, Dispatcher, executor, types

API_TOKEN = "8674272812:AAGWbN0aW-9PchTb3zb9ciFE8Q59PxK898Y"

bot = Bot(token=API_TOKEN)
dp = Dispatcher(bot)

# /start buyrug'i uchun handler
@dp.message_handler(commands=['start'])
async def send_welcome(message: types.Message):
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
    # Rasm havolasi
    photo_url = "https://images.unsplash.com/photo-1489599849927-2ee91cede3ba"
    
    try:
        await message.answer_photo(photo=photo_url, caption=caption_text)
    except Exception:
        await message.answer(caption_text)

# Render server o'chib qolmasligi uchun fonli web-server (aiohttp)
async def on_startup(dp):
    from aiohttp import web
    async def handle(request):
        return web.Response(text="Bot is running smoothly!")
    
    app = web.Application()
    app.router.add_get('/', handle)
    runner = web.AppRunner(app)
    await runner.setup()
    port = int(os.environ.get("PORT", 8080))
    site = web.TCPSite(runner, '0.0.0.0', port)
    asyncio.create_task(site.start())

if __name__ == '__main__':
    executor.start_polling(dp, skip_updates=True, on_startup=on_startup)
