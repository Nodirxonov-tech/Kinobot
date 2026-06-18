import os
import asyncio
from aiogram import Bot, Dispatcher, types
from aiogram.filters import Command

# Telegram bot tokeningiz (PHP koddan olindi)
API_TOKEN = "8674272812:AAGWbN0aW-9PchTb3zb9ciFE8Q59PxK898Y"
ADMIN_ID = 825688253  # Sizning ID raqamingiz

bot = Bot(token=API_TOKEN)
dp = Dispatcher()

# /start buyrug'i kelganda ishlovchi funksiya
@dp.message(Command("start"))
async def send_welcome(message: types.Message):
    # Foydalanuvchiga salomlashish matni va rasm havolasi (Sizning botingiz matni)
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
    # Botingizdagi rasm havolasi
    photo_url = "https://images.unsplash.com/photo-1489599849927-2ee91cede3ba" # Bu yerga xohlagan rasm linkini qo'yishingiz mumkin
    
    await message.answer_photo(photo=photo_url, caption=caption_text)

# Render uchun kichik web-server (Render botni o'chirib qo'ymasligi uchun)
async def handle_web(request):
    from aiohttp import web
    return web.Response(text="Bot is running!")

async def main():
    # Render portini aniqlash (Render avtomatik PORT beradi)
    port = int(os.environ.get("PORT", 8080))
    
    from aiohttp import web
    app = web.Application()
    app.router.add_get("/", handle_web)
    
    runner = web.AppRunner(app)
    await runner.setup()
    site = web.TCPSite(runner, "0.0.0.0", port)
    
    # Web server va Botni bir vaqtda ishga tushirish
    await asyncio.gather(
        site.start(),
        dp.start_polling(bot)
    )

if __name__ == "__main__":
    asyncio.run(main())
