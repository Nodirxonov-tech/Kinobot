<?php
ob_start();
error_reporting(0);
date_Default_timezone_set('Asia/Tashkent');

/*
Ushbu kod @obito_us ga tegishlik.
manbani o'zgartirganlaringni ko'rib qolsam xafa qilaman!
@sadiy_dev kanali uchun
*/

define('API_KEY',"8674272812:AAGWbN0aW-9PchTb3zb9ciFE8Q59PxK898Y");

$TokhtasinovUz = "8256882953";
$admin = array($TokhtasinovUz); 
$bot = bot('getme',['bot'])->result->username;


function bot($method,$datas=[]){
$url = "https://api.telegram.org/bot".API_KEY."/".$method;
$ch = curl_init();
curl_setopt($ch,CURLOPT_URL,$url);
curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
curl_setopt($ch,CURLOPT_POSTFIELDS,$datas);
$res = curl_exec($ch);
if(curl_error($ch)){
var_dump(curl_error($ch));
}else{
return json_decode($res);
}}

function addstat($id){
$stat=file_get_contents("users");
$check=explode("\n",$stat);
if(!in_array($id,$check)){
file_put_contents("users","\n".$id,8);
}
}
function addblock($id){
$stat=file_get_contents("block");
$check=explode("\n",$stat);
if(!in_array($id,$check)){
file_put_contents("block","\n".$id,8);
}
}

$kanallar=file_get_contents("channel.txt");
function joinchat($id){
global $bot;
$array = array("inline_keyboard");
$kanallar=file_get_contents("channel.txt");
if($kanallar == null){
return true;
}else{
$ex = explode("\n",$kanallar);
for($i=0;$i<=count($ex) -1;$i++){
$first_line = $ex[$i];
$first_ex = explode("@",$first_line);
$url = $first_ex[1];
$ism=bot('getChat',['chat_id'=>"@".$url,])->result->title;
$ret = bot("getChatMember",[
"chat_id"=>"@$url",
"user_id"=>$id,
]);
$stat = $ret->result->status;
if((($stat=="creator" or $stat=="administrator" or $stat=="member"))){
$array['inline_keyboard']["$i"][0]['text'] = "✅ ". $ism;
$array['inline_keyboard']["$i"][0]['url'] = "https://t.me/$url";
}else{
$array['inline_keyboard']["$i"][0]['text'] = "❌ ". $ism;
$array['inline_keyboard']["$i"][0]['url'] = "https://t.me/$url";
$uns = true;
}
}
$array['inline_keyboard']["$i"][0]['text'] = "🔄 Tekshirish";
$array['inline_keyboard']["$i"][0]['callback_data'] = "checksuv";
if($uns == true){
bot('sendMessage',[
'chat_id'=>$id,
'text'=>"<b>⚠️ Botdan to'liq foydalanish uchun quyidagi kanallarimizga obuna bo'ling!</b>",
'parse_mode'=>'html',
'disable_web_page_preview'=>true,
'reply_markup'=>json_encode($array),
]);
return false;
}else{
return true;
}}}

$update = json_decode(file_get_contents('php://input'));
$message = $update->message;
$cid = $message->chat->id;
$name = $message->chat->first_name;
$tx = $message->text;
$step = file_get_contents("step/$cid.step");
$mid = $message->message_id;
$type = $message->chat->type;
$text = $message->text;
$uid= $message->from->id;
$name = $message->from->first_name;
$familya = $message->from->last_name;
$bio = $message->from->about;
$username = $message->from->username;
$chat_id = $message->chat->id;
$message_id = $message->message_id;
$reply = $message->reply_to_message->text;
$nameru = "<a href='tg://user?id=$uid'>$name $familya</a>";

$botdel = $update->my_chat_member->new_chat_member; 
$botdelid = $update->my_chat_member->from->id; 
$userstatus= $botdel->status; 

$contact = $message->contact;
$contact_id = $contact->user_id;
$contact_user = $contact->username;
$contact_name = $contact->first_name;
$phone = $contact->phone_number;


//inline uchun metodlar
$data = $update->callback_query->data;
$qid = $update->callback_query->id;
$id = $update->inline_query->id;
$query = $update->inline_query->query;
$query_id = $update->inline_query->from->id;
$cid2 = $update->callback_query->message->chat->id;
$mid2 = $update->callback_query->message->message_id;
$callfrid = $update->callback_query->from->id;
$callname = $update->callback_query->from->first_name;
$calluser = $update->callback_query->from->username;
$surname = $update->callback_query->from->last_name;
$about = $update->callback_query->from->about;
$nameuz = "<a href='tg://user?id=$callfrid'>$callname $surname</a>";

$photo = $message->photo;
$file = $photo[count($photo)-1]->file_id;


mkdir("step");
mkdir("kino");

if(file_get_contents("kino/id.txt")==null){
file_put_contents("kino/id.txt",0);
}

$test1 = file_get_contents("step/test1.txt");
$test2 = file_get_contents("step/test2.txt");
$test3 = file_get_contents("step/test3.txt");
$test4 = file_get_contents("step/test4.txt");
$test5 = file_get_contents("step/test5.txt");
$test6 = file_get_contents("step/test6.txt");
$test7 = file_get_contents("step/test7.txt");
$test8 = file_get_contents("step/test8.txt");
$last_kino = file_get_contents("kino/id.txt");



if(file_get_contents("holat.txt")){
	}else{
		if(file_put_contents("holat.txt","Yoqilgan"));
}

if($botdel){ 
if($userstatus=="kicked"){ 
addblock($cid);
}}
if(isset($message)){
$block=file_get_contents("block");
$block=str_replace("\n".$cid,"",$block);
file_put_contents("block",$block);
}



$umum_d = date("d.m.Y H:i");
if(isset($message)){
addstat($cid);
}

$kanal_uz = file_get_contents("step/kanal.txt");
$kanalcha = file_get_contents("kino_ch.txt");
$holat = file_get_contents("holat.txt");

$menu = json_encode([
'inline_keyboard'=>[
[['text'=>"🔎 Kinolarni qidirish",'url'=>"https://t.me/".str_ireplace("@",null,$kanalcha)]],
]
]);

$panel = json_encode([
'resize_keyboard'=>true,
'keyboard'=>[
[['text'=>"📢 Kanallarni sozlash"]],
[['text'=>"📊 Statistika"],['text'=>"✉ Xabar Yuborish"]],
[['text'=>"📤 Kino Yuklash"]],
[['text'=>"🤖 Bot holati"],['text'=>"◀️ Orqaga"]],
]
]);

$back = json_encode([
'resize_keyboard'=>true,
'keyboard'=>[
[['text'=>"◀️ Orqaga"]],
]
]);

$boshqarish = json_encode([
'resize_keyboard'=>true,
'keyboard'=>[
[['text'=>"🗄 Boshqarish"]],
]
]);

$holat = file_get_contents("holat.txt");
if($text){
 if($holat == "O'chirilgan"){
	if(in_array($cid,$admin)){
}else{
	bot('sendMessage',[
	'chat_id'=>$cid,
	'text'=>"⛔️ <b>Bot vaqtinchalik o'chirilgan!</b>

<i>Botda ta'mirlash ishlari olib borilayotgan bo'lishi mumkin!</i>",
'parse_mode'=>'html',
]);
exit();
}
}
}

if($data){
 if($holat == "O'chirilgan"){
	if(in_array($cid2,$admin)){
}else{
	bot('answerCallbackQuery',[
		'callback_query_id'=>$qid,
		'text'=>"⛔️ Bot vaqtinchalik o'chirilgan!

Botda ta'mirlash ishlari olib borilayotgan bo'lishi mumkin!",
		'show_alert'=>true,
		]);
exit();
}
}
}

if($data=="checksuv"){
bot('deleteMessage',[
	'chat_id'=>$cid2,
	'message_id'=>$mid2,
	]);
if(joinchat($cid2) == true){
$text=file_get_contents("step/$cid2.kino_ids");
if($text!==null){
$nomi=file_get_contents("kino/$text/nomi.txt");
$tili=file_get_contents("kino/$text/tili.txt");
$formati=file_get_contents("kino/$text/formati.txt");
$janri=file_get_contents("kino/$text/janri.txt");
$yosh=file_get_contents("kino/$text/yosh.txt");
$downcount=file_get_contents("kino/$text/downcount.txt");
$downcount=+1;
file_put_contents("kino/$text/downcount.txt",$downcount);
$video_id=file_get_contents("kino/$text/film.txt");
bot('sendVideo',[
'chat_id'=>$cid2,
'video'=>$video_id,
'caption'=>"<b>🍿| Kino Nomi: $nomi
➖➖➖➖➖➖➖➖➖➖➖➖
🇺🇿| Tili: $tili
💾| Sifati: $formati
🎞️| Janri:  $janri
⛔️| Ko'rish Kategoriyasi: $yosh

🔰| Kanal: $kanalcha
🗂 Yuklash: $downcount

🤖 Bizning bot: @$bot</b>",
'parse_mode'=>'html',
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"📋 Ulashish",'url'=>"https://t.me/share/url?url=https://t.me/$bot?start=$text"]],
]
])
]);
unlink("step/$cid2.kino_ids");
exit();
}else{
	bot('SendMessage',[
	'chat_id'=>$cid2,
	'text'=>"<b>✅ Obunangiz tasdiqlandi!</b>",
	'parse_mode'=>'html'
	]);
	bot('SendMessage',[
	'chat_id'=>$cid2,
	'text'=>"👋 Salom, $name!

Marhamat, kino kodini yuboring:",
	'parse_mode'=>'html',
	'disable_web_page_preview'=>true,
	'reply_markup'=>$menu
	]);
	exit();
}
}
}

if($text == "/start" and joinchat($cid) == true){	
bot('SendMessage',[
'chat_id'=>$cid,
'text'=>"👋 Salom, $name!

Marhamat, kino kodini yuboring:",
'parse_mode'=>'html',
'disable_web_page_preview'=>true,
'reply_markup'=>$menu
]);
exit();
}

if($text == "◀️ Orqaga" and joinchat($cid) == true){        
bot('SendMessage',[
'chat_id'=>$cid,
'text'=>"👋 Salom, $name!

Marhamat, kino kodini yuboring:",
'parse_mode'=>'html',
'disable_web_page_preview'=>true,
'reply_markup'=>$menu
]);
unlink("step/$cid.step");
exit();
}


if($text == "🗄 Boshqarish" or $text=="/panel"){
	if(in_array($cid,$admin)){
	bot('SendMessage',[
	'chat_id'=>$cid,
	'text'=>"<b>Admin paneliga xush kelibsiz!</b>",
	'parse_mode'=>'html',
	'reply_markup'=>$panel,
	]);
	unlink("step/$cid.step");
   unlink("step/test.txt");
   unlink("step/$cid.txt");
	exit();
}
}

if($data == "boshqarish"){
	bot('deleteMessage',[
	'chat_id'=>$cid2,
	'message_id'=>$mid2,
	]);
	bot('SendMessage',[
	'chat_id'=>$cid2,
	'text'=>"<b>Admin paneliga xush kelibsiz!</b>",
	'parse_mode'=>'html',
	'reply_markup'=>$panel,
	]);
	exit();
}


if($text == "📢 Kanallarni sozlash"){
if(in_array($cid,$admin)){
bot('SendMessage',[
'chat_id'=>$cid,
'text'=>"<b>Quyidagilardan birini tanlang:</b>",
'parse_mode'=>'html',
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"🔐 Majburiy obuna",'callback_data'=>"kqosh"]],
[['text'=>"*️⃣ Qo'shimcha kanallar",'callback_data'=>"qoshimchakanal"]],
]
])
]);
exit();
}
}

if($data == "kanalsozla"){
if(in_array($cid2,$admin)){
bot('editMessageText',[
'chat_id'=>$cid2,
'message_id'=>$mid2,
'text'=>"<b>Quyidagilardan birini tanlang:</b>",
'parse_mode'=>'html',
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"🔐 Majburiy obuna",'callback_data'=>"kqosh"]],
[['text'=>"*️⃣ Qo'shimcha kanallar",'callback_data'=>"qoshimchakanal"]],
]
])
]);
exit();
}
}

if($data=="qoshimchakanal"){
bot('editMessageText',[
'chat_id'=>$cid2,
'message_id'=>$mid2,
'text'=>"<b>Quyidagilardan birini tanlang:</b>",
'parse_mode'=>'html',
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"📝 Kino kanal",'callback_data'=>"kinokanal"]],
[['text'=>"➡️ Orqafa",'callback_data'=>"kanalsozla"]],
]
])
]);
exit();
}

if($data=="kinokanal"){
bot('deleteMessage',[
'chat_id'=>$cid2,
'message_id'=>$mid2
]);
bot('sendMessage',[
'chat_id'=>$cid2,
'text'=>"<b>Kinolar yuboriladigan kanalni kiriting:</b>

<i>Namuna: @username</i>",
'parse_mode'=>'html',
'reply_markup'=>$boshqarish,
]);
file_put_contents("step/$cid2.step",'kinokanal');
exit();
}

if($step=="kinokanal" and in_array($cid,$admin)){
if(stripos($text,"@")!==false){
file_put_contents("kino_ch.txt",$text);
bot('sendMessage',[
'chat_id'=>$cid,
'text'=>"<b>✅ Saqlandi!</b>",
'parse_mode'=>'html',
'reply_markup'=>$panel,
]);
unlink("step/$cid.step");
exit();
}else{
bot('sendMessage',[
'chat_id'=>$cid,
'text'=>"<b>⛔️ Faqat kanalning foydalanuvchi nomini yuboring!</b>",
'parse_mode'=>'html'
]);
exit;
}
}

if($data=="kanallar"){
bot('editMessageText',[
'chat_id'=>$cid2,
'message_id'=>$mid2,
'text'=>"<b>Quyidagilardan birini tanlang:</b>",
'parse_mode'=>'html',
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"➕ Kanal Qo'shish",'callback_data'=>"kqosh"],['text'=>"🗑 Kanallarni O'chirish",'callback_data'=>"kochir"]],
[['text'=>"📑 Kanallar Ro'yxat",'callback_data'=>"mroyxat"]],
[['text'=>"➡️ Orqafa",'callback_data'=>"kanalsozla"]],
]
])
]);
exit();
}

/*Kanal obuna bo'lish*/
if($data == "kqosh"){
	if($text=="/start"){
unlink("step/$cid.step");
}else{
bot('editMessagetext',[
'chat_id'=>$cid2,
	'message_id'=>$mid2,
'text'=>"*📢 Kerakli kanalni manzilini yuboring:

Namuna: @LiveBuildersNews*",
'parse_mode'=>'MarkDown',
'reply_markup'=>$back1
]);
file_put_contents("step/$cid2.step",'qosh');
}}

if($step == "qosh"){
if($text=="/start"){
unlink("step/$cid.step");
}else{
if(stripos($text,"@")!==false){
if($kanallar == null){
file_put_contents("channel.txt",$text);
bot('SendMessage',[
'chat_id'=>$cid,
'text'=>"<b>$text - kanal qo'shildi</b>",
'parse_mode'=>'html',
'reply_markup'=>$panel,
]);
unlink("step/$cid.step");
}else{
file_put_contents("channel.txt","$kanallar\n$text");
bot('SendMessage',[
'chat_id'=>$cid,
'text'=>"<b>$text - kanal qo'shildi</b>",
'parse_mode'=>'html',
'reply_markup'=>$panel,
]);
unlink("step/$cid.step");
}}else{
bot('SendMessage',[
'chat_id'=>$cid,
'text'=>"<b>⚠️ Kanal manzili kiritishda xatolik:</b>

Masalan: @LiveBuildersNews",
'parse_mode'=>'html',
]);
}}}

if($data=="kochir"){
bot('deleteMessage',[
'chat_id'=>$cid2,
'message_id'=>$mid2,
]);
bot('sendMessage',[
'chat_id'=>$cid2,
'text'=>"<b>🗑 Kanallar o'chirildi!</b>",
'parse_mode'=>"html",
]);
unlink("channel.txt");
}

if($data=="mroyxat"){
if($kanallar==null){
bot('deleteMessage',[
'chat_id'=>$cid2,
'message_id'=>$mid2,
]);
bot('sendMessage',[
'chat_id'=>$cid2,
'text'=>"<b>Kanallar ulanmagan!</b>",
'parse_mode'=>"html",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"🏡 Bosh menyu",'callback_data'=>"profil"],['text'=>"◀️ Orqaga",'callback_data'=>"panel"]],
]])
]);
}else{
$soni = substr_count($kanallar,"@");
bot('editMessageText',[
'chat_id'=>$cid2,
'message_id'=>$mid2,
'text'=>"<b>Ulangan kanallar ro'yxati ⤵️</b>
➖➖➖➖➖➖➖➖

<i>$kanallar</i>

<b>Ulangan kanallar soni:</b> $soni ta",
'parse_mode'=>"html",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"🏡 Bosh menyu",'callback_data'=>"profil"],['text'=>"◀️ Orqaga",'callback_data'=>"panel"]],
]])
]);
}}

if($text == "📊 Statistika"){
if(in_array($cid,$admin)){
$ping=sys_getloadavg();
$stat=substr_count(file_get_contents("users"),"\n");
$nostat=substr_count(file_get_contents("block"),"\n")??0;
bot('SendMessage',[
'chat_id'=>$cid,
'text'=>"💡 <b>O'rtacha yuklanish:</b> <code>$ping[0]</code>

👥 <b>Foydalanuvchilar:</b> $stat ta 
⛔️ <b>Nofaol:</b> $nostat ta ",
'parse_mode'=>'html'
]);
exit();
}
}



if($text == "✉ Xabar Yuborish"){
if(in_array($cid,$admin)){
bot('sendMessage',[
'chat_id'=>$cid,
'text'=>"<b>Yuboriladigan xabar turini tanlang:</b>",
'parse_mode'=>'html',
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"👤 Userga",'callback_data'=>"user"]],
[['text'=>"🗣️ Oddiy",'callback_data'=>"send"]],
[['text'=>"Orqaga",'callback_data'=>"boshqarish"]],	
]
])
]);
exit();
}
}

if($data == "user"){
bot('deleteMessage',[
'chat_id'=>$cid2,
'message_id'=>$mid2,
]);
bot('sendMessage',[
'chat_id'=>$cid2,
'text'=>"<b>Foydalanuvchi iD raqamini kiriting:</b>",
'parse_mode'=>'html',
'reply_markup'=>$boshqarish,
]);
file_put_contents("step/$cid2.step",'user');
exit();
}

if($step == "user"){
if(in_array($cid,$admin)){
if(is_numeric($text)=="true"){
bot('SendMessage',[
'chat_id'=>$cid,
'text'=>"<b>Foydalanuvchiga yubormoqchi bo'lgan xabaringizni kiriting:</b>",
'parse_mode'=>'html',
]);
file_put_contents("step/$cid.step","xabar-$text");
exit();
}else{
bot('sendMessage',[
'chat_id'=>$cid,
'text'=>"<b>Faqat raqamlardan foydalaning!</b>",
'parse_mode'=>'html',
]);
exit();
}
}
}

if(mb_stripos($step, "xabar-") !== false){
if(in_array($cid,$admin)){
$id = explode("-", $step)[1];
$get = bot('getChat',[
'chat_id'=>$id,
]);
$first = $get->result->first_name;
$users = $get->result->username;
bot('copyMessage',[
'chat_id'=>$id,
'message_id'=>$mid,
'from_chat_id'=>$cid,
]);
bot('SendMessage',[
'chat_id'=>$cid,
'text'=>"✅ <b>Foydalanuvchiga xabaringiz yuborildi!</b>",
'parse_mode'=>'html',
'reply_markup'=>$panel,
]);
unlink("step/$cid.step");
exit();
}
}



if($data == "send"){
bot('deleteMessage',[
'chat_id'=>$cid2,
'message_id'=>$mid2,
]);
bot('SendMessage',[
'chat_id'=>$cid2,
'text'=>"*Xabar matnini kiriting:*",
'parse_mode'=>'MarkDown',
'reply_markup'=>$boshqarish,
]);
file_put_contents("step/$cid2.step","sendpost");
exit();
}

if($step == "sendpost"){
if(in_array($cid,$admin)){
unlink("step/$chat_id.step");
$users=file_get_contents("users");
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"*Xabar Yuborish Boshlandi* ✅",
'parse_mode'=>'MarkDown',
]);
$a=explode("\n",$users);
$x=0;
$y=0;
foreach($a as $id){
$key=$message->reply_markup;
$keyboard=json_encode($key);
$ok=bot('copyMessage',[
'from_chat_id'=>$cid,
'chat_id'=>$id,
'message_id'=>$mid,
])->ok;
if($ok==true){
}else{
$okk=bot('copyMessage',[
'from_chat_id'=>$cid,
'chat_id'=>$id,
'message_id'=>$mid,
])->ok;
}
if($okk==true or $ok==true){
$x=$x+1;
bot('editMessageText',[
'chat_id'=>$cid,
'message_id'=>$mid,
'text'=>"✅ <b>Yuborildi:</b> $x

❌ <b>Yuborilmadi:</b> $y",
'parse_mode'=>'html',
'reply_markup'=>$panel
]);
}elseif($okk==false){
$y=$y+1;
bot('editmessagetext',[
'chat_id'=>$cid,
'message_id'=>$mid + 1,
'text'=>"✅ <b>Yuborildi:</b> $x

❌ <b>Yuborilmadi:</b> $y",
'parse_mode'=>'html',
]);
}
}
bot('editmessagetext',[
'chat_id'=>$cid,
'message_id'=>$mid + 1,
'text'=>"✅ <b>Yuborildi:</b> $x

❌ <b>Yuborilmadi:</b> $y",
'parse_mode'=>'html',
]);
}
}

if($text == "🤖 Bot holati"){
	if(in_array($cid,$admin)){
	if($holat == "Yoqilgan"){
		$xolat = "O'chirish";
	}
	if($holat == "O'chirilgan"){
		$xolat = "Yoqish";
	}
	bot('SendMessage',[
	'chat_id'=>$cid,
	'text'=>"<b>Hozirgi holat:</b> $holat",
	'parse_mode'=>'html',
	'reply_markup'=>json_encode([
	'inline_keyboard'=>[
[['text'=>"$xolat",'callback_data'=>"bot"]],
[['text'=>"Orqaga",'callback_data'=>"boshqarish"]]
]
])
]);
exit();
}
}

if($data == "xolat"){
	if($holat == "Yoqilgan"){
		$xolat = "O'chirish";
	}
	if($holat == "O'chirilgan"){
		$xolat = "Yoqish";
	}
	bot('deleteMessage',[
	'chat_id'=>$cid2,
	'message_id'=>$mid2,
	]);
	bot('SendMessage',[
	'chat_id'=>$cid2,
	'text'=>"<b>Hozirgi holat:</b> $holat",
	'parse_mode'=>'html',
	'reply_markup'=>json_encode([
	'inline_keyboard'=>[
[['text'=>"$xolat",'callback_data'=>"bot"]],
[['text'=>"Orqaga",'callback_data'=>"boshqarish"]]
]
])
]);
exit();
}

if($data == "bot"){
if($holat == "Yoqilgan"){
file_put_contents("holat.txt","O'chirilgan");
     bot('editMessageText',[
        'chat_id'=>$cid2,
       'message_id'=>$mid2,
       'text'=>"<b>Muvaffaqiyatli o'zgartirildi!</b>",
'parse_mode'=>'html',
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"◀️ Orqaga",'callback_data'=>"xolat"]],
]
])
]);
}else{
file_put_contents("holat.txt","Yoqilgan");
     bot('editMessageText',[
        'chat_id'=>$cid2,
       'message_id'=>$mid2,
       'text'=>"<b>Muvaffaqiyatli o'zgartirildi!</b>",
'parse_mode'=>'html',
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"◀️ Orqaga",'callback_data'=>"xolat"]],
]
])
]);
}
}

if($text == "📤 Kino Yuklash"){
bot('SendMessage',[
'chat_id'=>$cid,
'text'=>"🍿 Kino nomini kiriting:",
'parse_mode'=>'html',
'reply_markup'=>$boshqarish,
]);
file_put_contents("step/$cid.step",'kinostep1');
exit();
}

if($step=="kinostep1" and isset($text)){
if(in_array($cid,$admin)){
$new_id=$last_kino+1;
mkdir("kino/$new_id");
file_put_contents("kino/id.txt",$new_id);
file_put_contents("step/new_kino",$new_id);
file_put_contents("kino/$new_id/nomi.txt",$text);
bot('SendMessage',[
'chat_id'=>$cid,
'text'=>"🏞 Kino uchun banner yuboring:",
'parse_mode'=>'html',
'reply_markup'=>$boshqarish,
]);
file_put_contents("step/$cid.step",'kinostep20');
exit();
}
}

$newkino=file_get_contents("step/new_kino");
if($step=="kinostep20" and isset($message->photo)){
if(in_array($cid,$admin)){
$photo_id=$message->photo[count($message->photo)-1]->file_id;
file_put_contents("kino/$newkino/rasm.txt",$photo_id);
bot('SendMessage',[
'chat_id'=>$cid,
'text'=>"🇺🇿 Kinoni qaysi tilga tarjima qilingan:",
'parse_mode'=>'html',
'reply_markup'=>$boshqarish,
]);
file_put_contents("step/$cid.step",'kinostep2');
exit();
}
}

if($step=="kinostep2" and isset($text)){
if(in_array($cid,$admin)){
file_put_contents("kino/$newkino/tili.txt",$text);
bot('sendMessage',[
'chat_id'=>$cid,
'text'=>"📹 Kino formatini kiriting:

<i>Namuna: 144p,240p,360p,720p,1080p</i>",
'parse_mode'=>'html',
'reply_markup'=>$boshqarish,
]);
file_put_contents("step/$cid.step",'kinostep3');
exit();
}
}

if($step=="kinostep3" and isset($text)){
if(in_array($cid,$admin)){
file_put_contents("kino/$newkino/formati.txt",$text);
bot('sendMessage',[
'chat_id'=>$cid,
'text'=>"📼 Kino janrini kiriting:

<i>Namuna: Drama, Romantik</i>",
'parse_mode'=>'html',
'reply_markup'=>$boshqarish,
]);
file_put_contents("step/$cid.step",'kinostep4');
exit();
}
}

if($step=="kinostep4" and isset($text)){
if(in_array($cid,$admin)){
file_put_contents("kino/$newkino/janri.txt",$text);
bot('sendMessage',[
'chat_id'=>$cid,
'text'=>"🛑 Kino yosh chegarasini kiriting:

<i>Namuna: 0+, 12+, 16+, 18+</i>",
'parse_mode'=>'html',
'reply_markup'=>$boshqarish,
]);
file_put_contents("step/$cid.step",'kinostep5');
exit();
}
}

if($step=="kinostep5" and isset($text)){
if(in_array($cid,$admin)){
file_put_contents("kino/$newkino/yosh.txt",$text);
file_put_contents("kino/$newkino/downcount.txt",0);
bot('sendMessage',[
'chat_id'=>$cid,
'text'=>"📺 Endi esa filmmi yuboring:",
'parse_mode'=>'html',
'reply_markup'=>$boshqarish,
]);
file_put_contents("step/$cid.step",'kino');
exit();
}
}

if($step=="kino" and isset($message->video)){
$video = $message->video;
$file_id = $message->video->file_id;
file_put_contents("kino/$newkino/film.txt",$file_id);
bot('sendmessage',[
'chat_id'=>$cid,
'text'=>"✅ Kino kanal va botga joylandi $kanalcha",
'reply_markup'=>$panel,
]);
$nomi=file_get_contents("kino/$newkino/nomi.txt");
$tili=file_get_contents("kino/$newkino/tili.txt");
$formati=file_get_contents("kino/$newkino/formati.txt");
$janri=file_get_contents("kino/$newkino/janri.txt");
$yosh=file_get_contents("kino/$newkino/yosh.txt");
$downcount=file_get_contents("kino/$newkino/downcount.txt");
$downcount=+1;
file_put_contents("kino/$newkino/downcount.txt",$downcount);
$rasm=file_get_contents("kino/$newkino/rasm.txt");
bot('sendPhoto',[
'chat_id'=>$kanalcha,
'photo'=>$rasm,
'caption'=>"<b>🆕 Yangi kino yuklandi!
➖➖➖➖➖➖➖➖➖➖➖➖
💥| Kino nomi: $nomi
🇺🇿| Tili: $tili
💾| Sifati: $formati
🎞️| Janri:  $janri
⛔️| Ko'rish Kategoriyasi: $yosh
➖➖➖➖➖➖➖➖➖➖➖➖
🤖 Bizning bot: @$bot</b>",
'parse_mode'=>'html',
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"🎥 Kinoni yuklab olish",'url'=>"https://t.me/$bot?start=$newkino"]],
[['text'=>"📋 Ulashish",'url'=>"https://t.me/share/url?url=https://t.me/$bot?start=$newkino"]],
]
])
]);
unlink("step/$cid.step");
exit();
}

if(mb_stripos($text,"/start")!==false){
$exp=explode(" ",$text);
$text=$exp[1];
if(joinchat($cid)==1){
$nomi=file_get_contents("kino/$text/nomi.txt");
$tili=file_get_contents("kino/$text/tili.txt");
$formati=file_get_contents("kino/$text/formati.txt");
$janri=file_get_contents("kino/$text/janri.txt");
$yosh=file_get_contents("kino/$text/yosh.txt");
$downcount=file_get_contents("kino/$text/downcount.txt");
$downcount=+1;
file_put_contents("kino/$text/downcount.txt",$downcount);
$video_id=file_get_contents("kino/$text/film.txt");
bot('sendVideo',[
'chat_id'=>$cid,
'video'=>$video_id,
'caption'=>"<b>🍿| Kino Nomi: $nomi
➖➖➖➖➖➖➖➖➖➖➖➖
🇺🇿| Tili: $tili
💾| Sifati: $formati
🎞️| Janri:  $janri
⛔️| Ko'rish Kategoriyasi: $yosh

🔰| Kanal: $kanalcha
🗂 Yuklash: $downcount

🤖 Bizning bot: @$bot</b>",
'parse_mode'=>'html',
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"📋 Ulashish",'url'=>"https://t.me/share/url?url=https://t.me/$bot?start=$text"]],
]
])
]);
exit();
}else{
file_put_contents("step/$cid.kino_ids",$text);
exit();
}
}

if(is_numeric($text)==true and empty($step)){
if(joinchat($cid)==1){
$nomi=file_get_contents("kino/$text/nomi.txt");
$tili=file_get_contents("kino/$text/tili.txt");
$formati=file_get_contents("kino/$text/formati.txt");
$janri=file_get_contents("kino/$text/janri.txt");
$yosh=file_get_contents("kino/$text/yosh.txt");
$downcount=file_get_contents("kino/$text/downcount.txt")+1;
file_put_contents("kino/$text/downcount.txt",$downcount);
$video_id=file_get_contents("kino/$text/film.txt");
bot('sendVideo',[
'chat_id'=>$cid,
'video'=>$video_id,
'caption'=>"<b>🍿| Kino Nomi: $nomi
➖➖➖➖➖➖➖➖➖➖➖➖
🇺🇿| Tili: $tili
💾| Sifati: $formati
🎞️| Janri:  $janri
⛔️| Ko'rish Kategoriyasi: $yosh

🔰| Kanal: $kanalcha
🗂 Yuklash: $downcount


🤖 Bizning bot: @$bot</b>",
'parse_mode'=>'html',
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"📋 Ulashish",'url'=>"https://t.me/share/url?url=https://t.me/$bot?start=$text"]],
]
])
]);
}
}

