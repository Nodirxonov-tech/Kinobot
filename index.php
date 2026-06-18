<?php
ob_start();
error_reporting(0);
date_default_timezone_set('Asia/Tashkent');

/*
Ushbu kod @obito_us ga tegishlik.
manbani o'zgartirganlaringni ko'rib qolsam xafa qilaman!
@sadiy_dev kanali uchun
*/

define('API_KEY',"8674272812:AAGWbN0aW-9PchTb3zb9ciFE8Q59PxK898Y");

$TokhtasinovUz = "8256882953";
$admin = array($TokhtasinovUz); 

function bot($method, $datas=[]){
    $url = "https://api.telegram.org/bot".API_KEY."/".$method;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $datas);
    $res = curl_exec($ch);
    if(curl_error($ch)){
        return false;
    } else {
        return json_decode($res);
    }
}

// Global bot username (getMe so'rovini xavfsiz chaqirish)
$bot_get = bot('getMe');
$bot = isset($bot_get->result->username) ? $bot_get->result->username : "kinobot";

function addstat($id){
    $stat = file_get_contents("users");
    $check = explode("\n", $stat);
    if(!in_array($id, $check)){
        file_put_contents("users", "\n".$id, FILE_APPEND);
    }
}

function addblock($id){
    $stat = file_get_contents("block");
    $check = explode("\n", $stat);
    if(!in_array($id, $check)){
        file_put_contents("block", "\n".$id, FILE_APPEND);
    }
}

$kanallar = file_get_contents("channel.txt");

function joinchat($id){
    global $bot;
    $array = array("inline_keyboard" => []);
    $kanallar = file_get_contents("channel.txt");
    if(empty($kanallar)){
        return true;
    } else {
        $ex = explode("\n", $kanallar);
        $uns = false;
        for($i=0; $i<count($ex); $i++){
            $first_line = trim($ex[$i]);
            if(empty($first_line)) continue;
            $first_ex = explode("@", $first_line);
            $url = isset($first_ex[1]) ? $first_ex[1] : str_replace("@", "", $first_line);
            
            $getChat = bot('getChat', ['chat_id'=>"@".$url]);
            $ism = isset($getChat->result->title) ? $getChat->result->title : $url;
            
            $ret = bot("getChatMember", [
                "chat_id"=>"@$url",
                "user_id"=>$id,
            ]);
            $stat = isset($ret->result->status) ? $ret->result->status : "";
            
            if($stat=="creator" or $stat=="administrator" or $stat=="member"){
                $array['inline_keyboard'][$i][0]['text'] = "✅ ". $ism;
                $array['inline_keyboard'][$i][0]['url'] = "https://t.me/$url";
            } else {
                $array['inline_keyboard'][$i][0]['text'] = "❌ ". $ism;
                $array['inline_keyboard'][$i][0]['url'] = "https://t.me/$url";
                $uns = true;
            }
        }
        
        $array['inline_keyboard'][count($ex)][0]['text'] = "🔄 Tekshirish";
        $array['inline_keyboard'][count($ex)][0]['callback_data'] = "checksuv";
        
        if($uns == true){
            bot('sendMessage',[
                'chat_id'=>$id,
                'text'=>"<b>⚠️ Botdan to'liq foydalanish uchun quyidagi kanallarimizga obuna bo'ling!</b>",
                'parse_mode'=>'html',
                'disable_web_page_preview'=>true,
                'reply_markup'=>json_encode($array),
            ]);
            return false;
        } else {
            return true;
        }
    }
}

$update = json_decode(file_get_contents('php://input'));
if(!$update) exit; // Agar Telegramdan so'rov kelmasa, kodni to'xtatish (Render xato bermasligi uchun)

$message = $update->message;
$cid = $message->chat->id;
$tx = $message->text;
$step = file_get_contents("step/$cid.step");
$mid = $message->message_id;
$type = $message->chat->type;
$text = $message->text;
$uid = $message->from->id;
$name = $message->from->first_name;
$familya = $message->from->last_name;
$username = $message->from->username;
$chat_id = $message->chat->id;
$message_id = $message->message_id;

$botdel = $update->my_chat_member->new_chat_member; 
$userstatus = $botdel->status; 

//inline uchun metodlar
$data = $update->callback_query->data;
$qid = $update->callback_query->id;
$cid2 = $update->callback_query->message->chat->id;
$mid2 = $update->callback_query->message->message_id;

$photo = $message->photo;

if(!file_exists("step")) mkdir("step");
if(!file_exists("kino")) mkdir("kino");

if(file_get_contents("kino/id.txt")==null){
    file_put_contents("kino/id.txt", 0);
}

$last_kino = file_get_contents("kino/id.txt");

if(!file_exists("holat.txt")){
    file_put_contents("holat.txt", "Yoqilgan");
}

if($botdel && $userstatus=="kicked"){ 
    addblock($cid);
}

if(isset($message)){
    $block = file_get_contents("block");
    $block = str_replace("\n".$cid, "", $block);
    file_put_contents("block", $block);
    addstat($cid);
}

$kanalcha = file_get_contents("kino_ch.txt");
$holat = file_get_contents("holat.txt");

$menu = json_encode([
    'inline_keyboard'=>[
        [['text'=>"🔎 Kinolarni qidirish", 'url'=>"https://t.me/".str_ireplace("@", null, $kanalcha)]],
    ]
]);

$panel = json_encode([
    'resize_keyboard'=>true,
    'keyboard'=>[
        [['text'=>"📢 Kanallarni sozlash"]],
        [['text'=>"📊 Statistika"], ['text'=>"✉ Xabar Yuborish"]],
        [['text'=>"📤 Kino Yuklash"]],
        [['text'=>"🤖 Bot holati"], ['text'=>"◀️ Orqaga"]],
    ]
]);

$boshqarish = json_encode([
    'resize_keyboard'=>true,
    'keyboard'=>[
        [['text'=>"🗄 Boshqarish"]],
    ]
]);

if($text){
    if($holat == "O'chirilgan" && !in_array($cid, $admin)){
        bot('sendMessage',[
            'chat_id'=>$cid,
            'text'=>"⛔️ <b>Bot vaqtinchalik o'chirilgan!</b>\n\n<i>Botda ta'mirlash ishlari olib borilayotgan bo'lishi mumkin!</i>",
            'parse_mode'=>'html',
        ]);
        exit();
    }
}

if($data){
    if($holat == "O'chirilgan" && !in_array($cid2, $admin)){
        bot('answerCallbackQuery',[
            'callback_query_id'=>$qid,
            'text'=>"⛔️ Bot vaqtinchalik o'chirilgan!\n\nBotda ta'mirlash ishlari olib borilayotgan bo'lishi mumkin!",
            'show_alert'=>true,
        ]);
        exit();
    }
}

if($data=="checksuv"){
    bot('deleteMessage',[
        'chat_id'=>$cid2,
        'message_id'=>$mid2,
    ]);
    if(joinchat($cid2) == true){
        $kino_id_file = file_get_contents("step/$cid2.kino_ids");
        if($kino_id_file !== false && $kino_id_file !== ""){
            $text = $kino_id_file;
            $nomi = file_get_contents("kino/$text/nomi.txt");
            $tili = file_get_contents("kino/$text/tili.txt");
            $formati = file_get_contents("kino/$text/formati.txt");
            $janri = file_get_contents("kino/$text/janri.txt");
            $yosh = file_get_contents("kino/$text/yosh.txt");
            $downcount = (int)file_get_contents("kino/$text/downcount.txt") + 1;
            file_put_contents("kino/$text/downcount.txt", $downcount);
            $video_id = file_get_contents("kino/$text/film.txt");
            bot('sendVideo',[
                'chat_id'=>$cid2,
                'video'=>$video_id,
                'caption'=>"<b>🍿| Kino Nomi: $nomi\n➖➖➖➖➖➖➖➖➖➖➖➖\n🇺🇿| Tili: $tili\n💾| Sifati: $formati\n🎞️| Janri:  $janri\n⛔️| Ko'rish Kategoriyasi: $yosh\n\n🔰| Kanal: $kanalcha\n🗂 Yuklash: $downcount\n\n🤖 Bizning bot: @$bot</b>",
                'parse_mode'=>'html',
                'reply_markup'=>json_encode([
                    'inline_keyboard'=>[
                        [['text'=>"📋 Ulashish", 'url'=>"https://t.me/share/url?url=https://t.me/$bot?start=$text"]],
                    ]
                ])
            ]);
            unlink("step/$cid2.kino_ids");
            exit();
        } else {
            bot('SendMessage',[
                'chat_id'=>$cid2,
                'text'=>"<b>✅ Obunangiz tasdiqlandi!</b>",
                'parse_mode'=>'html'
            ]);
            bot('SendMessage',[
                'chat_id'=>$cid2,
                'text'=>"👋 Salom!\n\nMarhamat, kino kodini yuboring:",
                'parse_mode'=>'html',
                'disable_web_page_preview'=>true,
                'reply_markup'=>$menu
            ]);
            exit();
        }
    }
}

if($text == "/start" && joinchat($cid) == true){   
    bot('SendMessage',[
        'chat_id'=>$cid,
        'text'=>"👋 Salom, $name!\n\nMarhamat, kino kodini yuboring:",
        'parse_mode'=>'html',
        'disable_web_page_preview'=>true,
        'reply_markup'=>$menu
    ]);
    exit();
}

if($text == "◀️ Orqaga" && joinchat($cid) == true){        
    bot('SendMessage',[
        'chat_id'=>$cid,
        'text'=>"👋 Salom, $name!\n\nMarhamat, kino kodini yuboring:",
        'parse_mode'=>'html',
        'disable_web_page_preview'=>true,
        'reply_markup'=>$menu
    ]);
    unlink("step/$cid.step");
    exit();
}

if($text == "🗄 Boshqarish" || $text=="/panel"){
    if(in_array($cid, $admin)){
        bot('SendMessage',[
            'chat_id'=>$cid,
            'text'=>"<b>Admin paneliga xush kelibsiz!</b>",
            'parse_mode'=>'html',
            'reply_markup'=>$panel,
        ]);
        unlink("step/$cid.step");
        exit();
    }
}

if($text == "📢 Kanallarni sozlash" && in_array($cid, $admin)){
    bot('SendMessage',[
        'chat_id'=>$cid,
        'text'=>"<b>Quyidagilardan birini tanlang:</b>",
        'parse_mode'=>'html',
        'reply_markup'=>json_encode([
            'inline_keyboard'=>[
                [['text'=>"🔐 Majburiy obuna", 'callback_data'=>"kqosh"]],
                [['text'=>"*️⃣ Qo'shimcha kanallar", 'callback_data'=>"qoshimchakanal"]],
            ]
        ])
    ]);
    exit();
}

if($data=="qoshimchakanal" && in_array($cid2, $admin)){
    bot('editMessageText',[
        'chat_id'=>$cid2,
        'message_id'=>$mid2,
        'text'=>"<b>Quyidagilardan birini tanlang:</b>",
        'parse_mode'=>'html',
        'reply_markup'=>json_encode([
            'inline_keyboard'=>[
                [['text'=>"📝 Kino kanal", 'callback_data'=>"kinokanal"]],
            ]
        ])
    ]);
    exit();
}

if($data=="kinokanal" && in_array($cid2, $admin)){
    bot('deleteMessage',['chat_id'=>$cid2, 'message_id'=>$mid2]);
    bot('sendMessage',[
        'chat_id'=>$cid2,
        'text'=>"<b>Kinolar yuboriladigan kanalni kiriting:</b>\n\n<i>Namuna: @username</i>",
        'parse_mode'=>'html',
        'reply_markup'=>$boshqarish,
    ]);
    file_put_contents("step/$cid2.step", 'kinokanal');
    exit();
}

if($step=="kinokanal" && in_array($cid, $admin)){
    if(stripos($text, "@") !== false){
        file_put_contents("kino_ch.txt", $text);
        bot('sendMessage',[
            'chat_id'=>$cid,
            'text'=>"<b>✅ Saqlandi!</b>",
            'parse_mode'=>'html',
            'reply_markup'=>$panel,
        ]);
        unlink("step/$cid.step");
        exit();
    }
}

if($data == "kqosh" && in_array($cid2, $admin)){
    bot('editMessageText',[
        'chat_id'=>$cid2,
        'message_id'=>$mid2,
        'text'=>"<b>📢 Kerakli kanalni manzilini yuboring:\n\nNamuna: @KanalUsername</b>",
        'parse_mode'=>'html'
    ]);
    file_put_contents("step/$cid2.step", 'qosh');
    exit();
}

if($step == "qosh" && in_array($cid, $admin)){
    if(stripos($text, "@") !== false){
        if(empty($kanallar)){
            file_put_contents("channel.txt", $text);
        } else {
            file_put_contents("channel.txt", "$kanallar\n$text");
        }
        bot('SendMessage',[
            'chat_id'=>$cid,
            'text'=>"<b>$text - kanal qo'shildi</b>",
            'parse_mode'=>'html',
            'reply_markup'=>$panel,
        ]);
        unlink("step/$cid.step");
        exit();
    }
}

if($text == "📊 Statistika" && in_array($cid, $admin)){
    $stat = substr_count(file_get_contents("users"), "\n");
    $nostat = substr_count(file_get_contents("block"), "\n");
    bot('SendMessage',[
        'chat_id'=>$cid,
        'text'=>"👥 <b>Foydalanuvchilar:</b> $stat ta\n⛔️ <b>Nofaol:</b> $nostat ta",
        'parse_mode'=>'html'
    ]);
    exit();
}

if($text == "📤 Kino Yuklash" && in_array($cid, $admin)){
    bot('SendMessage',[
        'chat_id'=>$cid,
        'text'=>"🍿 Kino nomini kiriting:",
        'parse_mode'=>'html',
        'reply_markup'=>$boshqarish,
    ]);
    file_put_contents("step/$cid.step", 'kinostep1');
    exit();
}

if($step=="kinostep1" && isset($text) && in_array($cid, $admin)){
    $new_id = (int)$last_kino + 1;
    mkdir("kino/$new_id");
    file_put_contents("kino/id.txt", $new_id);
    file_put_contents("step/$cid.new_kino", $new_id);
    file_put_contents("kino/$new_id/nomi.txt", $text);
    bot('SendMessage',[
        'chat_id'=>$cid,
        'text'=>"🏞 Kino uchun banner yuboring:",
        'parse_mode'=>'html',
        'reply_markup'=>$boshqarish,
    ]);
    file_put_contents("step/$cid.step", 'kinostep20');
    exit();
}

$newkino = file_get_contents("step/$cid.new_kino");

if($step=="kinostep20" && isset($message->photo) && in_array($cid, $admin)){
    $photo_id = $message->photo[count($message->photo)-1]->file_id;
    file_put_contents("kino/$newkino/rasm.txt", $photo_id);
    bot('SendMessage',[
        'chat_id'=>$cid,
        'text'=>"🇺🇿 Kinoni qaysi tilga tarjima qilingan:",
        'parse_mode'=>'html',
        'reply_markup'=>$boshqarish,
    ]);
    file_put_contents("step/$cid.step", 'kinostep2');
    exit();
}

if($step=="kinostep2" && isset($text) && in_array($cid, $admin)){
    file_put_contents("kino/$newkino/tili.txt", $text);
    bot('sendMessage',[
        'chat_id'=>$cid,
        'text'=>"📹 Kino formatini kiriting:\n\n<i>Namuna: 720p, 1080p</i>",
        'parse_mode'=>'html',
        'reply_markup'=>$boshqarish,
    ]);
    file_put_contents("step/$cid.step", 'kinostep3');
    exit();
}

if($step=="kinostep3" && isset($text) && in_array($cid, $admin)){
    file_put_contents("kino/$newkino/formati.txt", $text);
    bot('sendMessage',[
        'chat_id'=>$cid,
        'text'=>"Cassette Kino janrini kiriting:\n\n<i>Namuna: Melodrama, Bozyevik</i>",
        'parse_mode'=>'html',
        'reply_markup'=>$boshqarish,
    ]);
    file_put_contents("step/$cid.step", 'kinostep4');
    exit();
}

if($step=="kinostep4" && isset($text) && in_array($cid, $admin)){
    file_put_contents("kino/$newkino/janri.txt", $text);
    bot('sendMessage',[
        'chat_id'=>$cid,
        'text'=>"🛑 Kino yosh chegarasini kiriting:\n\n<i>Namuna: 16+, 18+</i>",
        'parse_mode'=>'html',
        'reply_markup'=>$boshqarish,
    ]);
    file_put_contents("step/$cid.step", 'kinostep5');
    exit();
}

if($step=="kinostep5" && isset($text) && in_array($cid, $admin)){
    file_put_contents("kino/$newkino/yosh.txt", $text);
    file_put_contents("kino/$newkino/downcount.txt", 0);
    bot('sendMessage',[
        'chat_id'=>$cid,
        'text'=>"📺 Endi esa filmning o'zini (Video holatida) yuboring:",
        'parse_mode'=>'html',
        'reply_markup'=>$boshqarish,
    ]);
    file_put_contents("step/$cid.step", 'kino');
    exit();
}

if($step=="kino" && isset($message->video) && in_array($cid, $admin)){
    $file_id = $message->video->file_id;
    file_put_contents("kino/$newkino/film.txt", $file_id);
    bot('sendmessage',[
        'chat_id'=>$cid,
        'text'=>"✅ Kino tizimga muvaffaqiyatli yuklandi!",
        'reply_markup'=>$panel,
    ]);
    
    $nomi = file_get_contents("kino/$newkino/nomi.txt");
    $tili = file_get_contents("kino/$newkino/tili.txt");
    $formati = file_get_contents("kino/$newkino/formati.txt");
    $janri = file_get_contents("kino/$newkino/janri.txt");
    $yosh = file_get_contents("kino/$newkino/yosh.txt");
    $rasm = file_get_contents("kino/$newkino/rasm.txt");
    
    if(!empty($kanalcha)){
        bot('sendPhoto',[
            'chat_id'=>$kanalcha,
            'photo'=>$rasm,
            'caption'=>"<b>🆕 Yangi kino yuklandi!\n➖➖➖➖➖➖➖➖➖➖➖➖\n💥| Kino nomi: $nomi\n🇺🇿| Tili: $tili\n💾| Sifati: $formati\n🎞️| Janri:  $janri\n⛔️| Ko'rish Kategoriyasi: $yosh\n➖➖➖➖➖➖➖➖➖➖➖➖\n🤖 Bizning bot: @$bot</b>",
            'parse_mode'=>'html',
            'reply_markup'=>json_encode([
                'inline_keyboard'=>[
                    [['text'=>"🎥 Kinoni yuklab olish", 'url'=>"https://t.me/$bot?start=$newkino"]],
                ]
            ])
        ]);
    }
    unlink("step/$cid.step");
    unlink("step/$cid.new_kino");
    exit();
}

if(mb_stripos($text, "/start") !== false){
    $exp = explode(" ", $text);
    if(isset($exp[1])){
        $kino_kod = $exp[1];
        if(joinchat($cid) == true){
            $nomi = file_get_contents("kino/$kino_kod/nomi.txt");
            if($nomi){
                $tili = file_get_contents("kino/$kino_kod/tili.txt");
                $formati = file_get_contents("kino/$kino_kod/formati.txt");
                $janri = file_get_contents("kino/$kino_kod/janri.txt");
                $yosh = file_get_contents("kino/$kino_kod/yosh.txt");
                $downcount = (int)file_get_contents("kino/$kino_kod/downcount.txt") + 1;
                file_put_contents("kino/$kino_kod/downcount.txt", $downcount);
                $video_id = file_get_contents("kino/$kino_kod/film.txt");
                bot('sendVideo',[
                    'chat_id'=>$cid,
                    'video'=>$video_id,
                    'caption'=>"<b>🍿| Kino Nomi: $nomi\n➖➖➖➖➖➖➖➖➖➖➖➖\n🇺🇿| Tili: $tili\n💾| Sifati: $formati\n🎞️| Janri:  $janri\n⛔️| Ko'rish Kategoriyasi: $yosh\n\n🔰| Kanal: $kanalcha\n🗂 Yuklash: $downcount\n\n🤖 Bizning bot: @$bot</b>",
                    'parse_mode'=>'html'
                ]);
            }
            exit();
        } else {
            file_put_contents("step/$cid.kino_ids", $kino_kod);
            exit();
        }
    }
}

if(is_numeric($text) == true && empty($step)){
    if(joinchat($cid) == true){
        $nomi = file_get_contents("kino/$text/nomi.txt");
        if($nomi){
            $tili = file_get_contents("kino/$text/tili.txt");
            $formati = file_get_contents("kino/$text/formati.txt");
            $janri = file_get_contents("kino/$text/janri.txt");
            $yosh = file_get_contents("kino/$text/yosh.txt");
            $downcount = (int)file_get_contents("kino/$text/downcount.txt") + 1;
            file_put_contents("kino/$text/downcount.txt", $downcount);
            $video_id = file_get_contents("kino/$text/film.txt");
            bot('sendVideo',[
                'chat_id'=>$cid,
                'video'=>$video_id,
                'caption'=>"<b>🍿| Kino Nomi: $nomi\n➖➖➖➖➖➖➖➖➖➖➖➖\n🇺🇿| Tili: $tili\n💾| Sifati: $formati\n🎞️| Janri:  $janri\n⛔️| Ko'rish Kategoriyasi: $yosh\n\n🔰| Kanal: $kanalcha\n🗂 Yuklash: $downcount\n\n🤖 Bizning bot: @$bot</b>",
                'parse_mode'=>'html'
            ]);
        }
        exit();
    }
}
?>
