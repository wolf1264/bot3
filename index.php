<?php
/* FaceApp */
ini_set('error_reporting', E_ALL);
ob_start();
define('TOKEN','579825326:AAH2F3eVjQRorsKQPB47UhJLAHGMuHJ3dVw');
$update = file_get_contents("php://input");
$update = json_decode($update,true);
$message = isset($update["message"])?$update["message"]:null;
$message_id = isset($message['message_id'])?$message['message_id']:null;
$text = isset($message["text"])?$message["text"]:null;
$chat = isset($message["chat"])?$message["chat"]:null;
$chat_id = isset($chat["id"])?$chat["id"]:null;
$from = isset($message["from"])?$message["from"]:null;
$from_id = isset($from["id"])?$from["id"]:null;
$firstname = isset($from["first_name"])?$from["first_name"]:null;
$callback = isset($update["callback_query"])?$update["callback_query"]:null;
$data = isset($callback["data"])?$callback["data"]:null;
$callbackchat = isset($callback["message"]["chat"])?$callback["message"]["chat"]:null;
$chatid = isset($callbackchat["id"])?$callbackchat["id"]:null;
$callbackmessage = isset($callback["message"])?$callback["message"]:null;
$messageid = isset($callbackmessage["message_id"])?$callbackmessage["message_id"]:null;
$callbackfrom = isset($callback["from"])?$callback["from"]:null;
$fromid = isset($callbackfrom["id"])?$callbackfrom["id"]:null;
$getMe = getMe();
$myFrom_id = $getMe["id"];
$myFirstName = $getMe["first_name"];
$myUserName = $getMe["username"];
$support = file_get_contents("data/support.txt");
$limit = file_get_contents("data/limit.txt");
$step = file_get_contents("data/users/".$from_id."/step.txt");
$users = file_get_contents("data/users.txt");
$arrayUsers = explode("\n","users : \n".$users);
$sudo = 460848425;
$mrjavi = 460848425;
$Admin = admin($from_id);
if(!is_dir("data")){mkdir("data");}
if(!is_dir("data/users")){mkdir("data/users");}
if(!is_dir("data/users/".$from_id)){mkdir("data/users/".$from_id);}
if(file_exists("error_log")){unlink("error_log");}
//////////////////////////////////////
function request($method,$datas=[]){
    $url = "https://api.telegram.org/bot".TOKEN."/".$method;
$ch = curl_init();
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    curl_setopt($ch,CURLOPT_POSTFIELDS,$datas);
    $res = curl_exec($ch);
    if(curl_error($ch)){
        var_dump(curl_error($ch));
    }else{
        return json_decode($res);
    }
}
function is_join($user,$chat){
    $content = json_decode(file_get_contents("https://api.telegram.org/bot".579825326:AAH2F3eVjQRorsKQPB47UhJLAHGMuHJ3dVw."/getChatMember?chat_id=".$chat."&user_id=".$user));
    $status = $content->result->status;
    if($status != "left"){
        return true;
        
    }else{
        return false;
    }
}
function objectToArrays( $object ) {
    if( !is_object( $object ) && !is_array( $object ) )
    {
    return $object;
    }
    if( is_object( $object ) )
    {
    $object = get_object_vars( $object );
    }
   return array_map( "objectToArrays", $object );
   }
function plusMember($from_id){
file_put_contents("data/users/".$from_id."/opp.txt",2);
file_put_contents("data/users/".$from_id."/invite.txt",0);
$fopen = fopen("data/users.txt",'a')or die("unable to open file!");
fwrite($fopen,$from_id."\n");
}
function plusInvite($from_id){
$invite = invite($from_id)+1;
file_put_contents("data/users/".$from_id."/invite.txt",$invite);
}
function getChatMember($from_id,$chat_id){
$update = json_decode(file_get_contents("https://api.telegram.org/bot".TOKEN."/getChatMember?chat_id=".$chat_id."&user_id=".$from_id),true);
if($update["ok"] == true){
return true;
}else{
return false;
}
}
function invite($from_id){
$invite = file_get_contents("data/users/".$from_id."/invite.txt");
if(file_exists("data/users/".$from_id."/invite.txt")){
return $invite;
}else{
return 0;
}
}
function vip($from_id){
$invite  = file_get_contents("data/users/".$from_id."/invite.txt");
$limit = file_get_contents("data/limit.txt");
if($invite >= $limit){
return true;
}else{
return false;
}
}
function sendaction($chat_id, $action){
 request('sendchataction',[
 'chat_id'=>$chat_id,
 'action'=>$action
 ]);
 }
 function is_admin($bot,$group){
$GET = json_decode(file_get_contents("https://api.telegram.org/bot".TOKEN."/getChatMember?chat_id=".$group."&user_id=".$bot));
$STATUS = $GET->result->status;
if($STATUS == 'administrator'){
return true;
}else{
return false;
}
}

function getMe(){
    $GET = json_decode(file_get_contents("https://api.telegram.org/bot".TOKEN."/getMe"),true);
    if($GET['ok'] == true){
        return $GET['result'];
        
    }
}
//////////////////////////////////////////////////////
function member($from_id){
$users = file_get_contents("data/users.txt");
$arrayUsers = explode("\n","users : \n".$users);
if(in_array($from_id,$arrayUsers)){
return true;
}else{
return false;
}
}
function admin($from_id){
$admins = file_get_contents("data/admins.txt");
$arrayAdmins = explode("\n","admins :".$admins);
if(in_array($from_id,$arrayAdmins)){
return true;
}else{
return false;
}
}
function setAdmin($from_id){
$fopen = fopen("data/admins.txt",'a')or die("Unable to open file!");
fwrite($fopen,$from_id."\n");
}
function delAdmin($from_id){
$admins = file_get_contents("data/admins.txt");
$admins = str_replace($from_id."\n",'',$admins);
file_put_contents("data/admins.txt",$admins);
}
function setStep($from_id,$data){
file_put_contents("data/users/".$from_id."/step.txt",$data);
}
$back = json_encode([
'keyboard'=>[
[['text'=>'برگشت ➥']],
],
'resize_keyboard'=>true
]);
//////////////////////////////////////////////////////

if(strpos($text,'/start ')!==false){
$id = str_replace('/start ','',$text);
if(member($from_id) == false){
if(member($id) == true){
if($id != $from_id){
plusInvite($id);
$inv = $limit - invite($id);
if(invite($id) >= $limit){
request('sendMessage',[
'chat_id'=>$id,
'text'=>'🔔 اطلاعیه : یک کاربر جدید از لینک دعوت شما وارد ربات شد .
نفرات باقی مانده : '.$inv.' نفر

✅ اکانت VIP برای شما فعال شد.

از امکانات این ربات به صورت نامحدود لذت ببرید 😉

برای تغییر چهره، تصویر خود را ارسال نمایید 👇

🆔 @'.$myUserName
]);
}else{
if(invite($id) < $limit+1){
request('sendMessage',[
        'chat_id'=>$chat_id,
        'reply_to_message_id'=>$message_id,
        'text'=>'سلام '.$firstname.'
        
به بزرگترین ربات تغییر چهره در تلگرام خوش آمدی
استفاده از این ربات خیلی راحته ،

تو مرحله اول یک عکس ارسال کن😎

🆔 @'.$myUserName,
'reply_markup'=>$keyboard_1
        ]);

request('sendMessage',[
'chat_id'=>$id,
'text'=>'🔔 اطلاعیه : یک کاربر جدید از لینک دعوت شما وارد ربات شد .
نفرات باقی مانده : '.$inv.' نفر

🆔 @'.$myUserName,
]);
}
}
}else{
request('sendMessage',[
'chat_id'=>$chat_id,
'reply_to_message_id'=>$message_id,
'text'=>'❌ شما نمیتوانید خودتان را به ربات دعوت 
کنید
🆔 @'.$myUserName,
]);
}
}else{
request('sendMessage',[
'chat_id'=>$chat_id,
'reply_to_message_id'=>$message_id,
'text'=>'❌ کاربر با شناسه عددی '.$id.' جزء اعضای ربات نیست

🆔@'.$myUserName,
]);
}
}else{
request('sendMessage',[
'chat_id'=>$chat_id,
'reply_to_message_id'=>$message_id,
'text'=>'❌ شما از قبل جزء اعضای ربات هستید

🆔 @'.$myUserName,
]);
}
}
if(member($from_id) == false){
    request('sendMessage',[
'chat_id'=>$chat_id,
'reply_to_message_id'=>$message_id,
'text'=>'🔞 برای ورود به ورود به 
کانال روی سوتین ها کلیک کنید👅
[👙👙👙👙👙👙👙👙👙👙👙👙👙👙👙👙👙👙👙👙👙👙👙👙👙👙👙👙👙👙](https://t.me/joinchat/AAAAAEORlgW6Q2AxAC8BMg)',
'parse_mode'=>'MarkDown',
'disable_web_page_preview'=>true
]);
    plusMember($from_id);
    request('forwardMessage',[
'chat_id'=>$chat_id,
'from_chat_id'=>-1001133614597,
'message_id'=>97,
]);
}


if(is_join($from_id,'@'.$support) == true){
if($text == 'برگشت🔙' || $text == '/start'){
setStep($from_id,'none');
request('sendMessage',[
'chat_id'=>$chat_id,
'reply_to_message_id'=>$message_id,
'text'=>'سلام ['.$firstname.'](tg://user?id='.$from_id.') 

در ادامه یک عکس ارسال کنید

🆔 @'.$myUserName,
'parse_mode'=>'MARKDOWN',
'reply_markup'=>json_encode([
    'remove_keyboard'=>true
    ])
]);
}
if(preg_match('([!/#][Pp][Aa][Nn][Ee][Ll])',$text) || $text ==  'برگشت ➥' || $text == 'پنل'){
if($Admin == true || $from_id == $sudo || $from_id == $mrjavi){
    setStep($from_id,'none');
if($from_id == $sudo){
$admin_key = json_encode([
'keyboard'=>[
[['text'=>'تنظیم کانال'],['text'=>'تنظیم لیمیت']],
[['text'=>'ویژه کردن کاربر']],
[['text'=>'آمار'],['text'=>'ارسال به کاربران']],
[['text'=>'افزودن ادمین'],['text'=>'حذف ادمین']],
[['text'=>'برگشت🔙']],
],
'resize_keyboard'=>true
]);
}else{
$admin_key = json_encode([
'keyboard'=>[
[['text'=>'تنظیم کانال'],['text'=>'تنظیم لیمیت']],
[['text'=>'ویژه کردن کاربر']],
[['text'=>'آمار'],['text'=>'ارسال به کاربران']],
[['text'=>'برگشت🔙']],
],
'resize_keyboard'=>true
]);
}
request('sendMessage',[
'chat_id'=>$chat_id,
'reply_to_message_id'=>$message_id,
'text'=>'کاربر ['.$firstname.'](tg://user?id='.$from_id.') 

به پنل مدیریتی رباتتان خوش آمدید

🆔 @'.$myUserName,
'parse_mode'=>'MarkDown',
'reply_markup'=>$admin_key,
]);
}
}
elseif($text == 'تنظیم کانال'){
if($sudo == $from_id || $Admin == true || $from_id == $mrjavi){
setStep($from_id,'setchannel');
request('sendMessage',[
'chat_id'=>$chat_id,
'reply_to_message_id'=>$message_id,
'text'=>'لطفا شناسه یا آیدی کانال را اسال کنید

🆔 @'.$myUserName,
'parse_mode'=>'MarkDown',
'reply_markup'=>$back,
]);
}
}

elseif($text == 'تنظیم لیمیت'){
if($sudo == $from_id || $Admin == true || $from_id == $mrjavi){
setStep($from_id,'setlimit');
request('sendMessage',[
'chat_id'=>$chat_id,
'reply_to_message_id'=>$message_id,
'text'=>'لطفا یک عدد ارسال کنید
عدد ارسال شده بعنوان لیمیت دعوت کاربران توسط کاربر مورد نظر است

🆔 @'.$myUserName,
'parse_mode'=>'MarkDown',
'reply_markup'=>$back,
]);
}
}

elseif($text == 'ویژه کردن کاربر'){
if($sudo == $from_id || $Admin == true || $mrjavi == $from_id){
setStep($from_id,'setvip');
request('sendMessage',[
'chat_id'=>$chat_id,
'reply_to_message_id'=>$message_id,
'text'=>'لطفا شناسه عددی کاربر را ارسال کنید
دقت کنید کاربر باید در ربات عضو باشد

🆔 @'.$myUserName,
'parse_mode'=>'MarkDown',
'reply_markup'=>$back,
]);
}
}
elseif($text == 'آمار'){
if($Admin == true || $sudo == $from_id || $from_id == $mrjavi){
request('sendMessage',[
'chat_id'=>$chat_id,
'reply_to_message_id'=>$message_id,
'text'=>'🗣 تعداد اعضای ربات :
🗯 '.count($arrayUsers).' نفر ',
]);
}
}
elseif($text == 'ارسال به کاربران'){
if($sudo == $from_id || $Admin == true || $from_id == $mrjavi){
setStep($from_id,'sendall');
request('sendMessage',[
'chat_id'=>$chat_id,
'reply_to_message_id'=>$message_id,
'text'=>'😇 لطفا پیامی را که میخواهید برای کاربران ارسال شود را بفرستید 

🆔 @'.$myUserName,
]);
}
}
elseif($text == 'افزودن ادمین'){
if($sudo == $from_id){
setStep($from_id,'addadmin');
request('sendMessage',[
'chat_id'=>$chat_id,
'reply_to_message_id'=>$message_id,
'text'=>'😇 لطفا شناسه عددی کاربر مورد نظر را وارد کنید
کاربر باید از قبل جزء اعضای ربات باشد

🆔 @'.$myUserName,
'reply_markup'=>$back
]);
}
}
elseif($text == 'حذف ادمین' && $from_id == $sudo){
setStep($from_id,'deladmin');
request('sendMessage',[
'chat_id'=>$chat_id,
'reply_to_message_id'=>$message_id,
'text'=>'😇 لطفا شناسه عددی کاربر را ارسال کنید
دقت کنید کاربر باید از قبل جزء اعضای ربات و مدیران باشد

🆔 @'.$myUserName,
'parse_mode'=>'MarkDown',
'reply_markup'=>$back,
]);
}
elseif($text != 'تنظیم کانال' && $text != 'تنظیم لیمیت' && $text != 'ویژه کردن کاربر' && $text != 'ارسال به کاربران' && $text != 'حذف ادمین' && $text != 'افزودن ادمین' && $text != 'برگشت🔙' && $text != '/start' && $text != 'برگشت ➥'){
if($step == 'setchannel'){
if(is_admin($myFrom_id,'@'.$text) == true){
file_put_contents("data/support.txt",$text);
request('sendMessage',[
'chat_id'=>$chat_id,
'reply_to_message_id'=>$message_id,
'text'=>'✅ کانال با موفقیت تنظیم شد به 
🆔 @'.$text,
'parse_mode'=>'MarkDown',
'reply_markup'=>$back,
]);
}else{
request('sendMessage',[
'chat_id'=>$chat_id,
'reply_to_message_id'=>$message_id,
'text'=>'❌ خطا ربات در کانال ادمین نمیباشد
',
'parse_mode'=>'MarkDown',
'reply_markup'=>$back,
]);
}
}
elseif($step == 'setlimit'){
if(is_numeric($text)){
file_put_contents("data/limit.txt",$text);
request('sendMessage',[
'chat_id'=>$chat_id,
'reply_to_message_id'=>$message_id,
'text'=>'✅ لیمیت تعداد دعوت کاربران به '.$text.' تنظیم شد',
'parse_mode'=>'MarkDown',
'reply_markup'=>$back,
]);
}else{
request('sendMessage',[
'chat_id'=>$chat_id,
'reply_to_message_id'=>$message_id,
'text'=>'❌ لطفا فقط یک عدد ارسال کنید
',
'parse_mode'=>'MarkDown',
'reply_markup'=>$back,
]);
}
}
elseif($step == 'setvip'){
if(member($text) == true){
if(vip($text) == false){
file_put_contents("data/users/".$text."/invite.txt",93999393937);
request('sendMessage',[
'chat_id'=>$chat_id,
'reply_to_message_id'=>$message_id,
'text'=>'✅ کاربر مورد نظر با موفقیت ویژه شد',
'parse_mode'=>'MarkDown',
'reply_markup'=>$back,
]);
request('sendMessage',[
'chat_id'=>$text,
'text'=>'✅ شما ویژه شدید'
]);
}else{
request('sendMessage',[
'chat_id'=>$chat_id,
'text'=>'❌ کاربر ویژه میباشد',
'reply_markup'=>$back,
]);
}
}else{
request('sendMessage',[
'chat_id'=>$chat_id,
'reply_to_message_id'=>$message_id,
'text'=>'❌ خطا کاربر جزء اعضای ربات نمیباشد',
'parse_mode'=>'MarkDown',
'reply_markup'=>$back,
]);
}
}
elseif($step == 'sendall'){
if($from_id == $sudo || $Admin == true){
file_put_contents("chat_id.txt",$chat_id);
file_put_contents("message_id.txt",$message_id);
file_put_contents("send_to_all.txt",'true');
file_put_contents("start.txt",5000);
request('sendMessage',[
'chat_id'=>$chat_id,
'reply_to_message_id'=>$message_id,
'text'=>'✅ پیام با موفقیت ارسال شد
ارسال پیام به همه کاربران بسته به تعداد کاربران ممکن است طول بکشد
تائیدیه  ارسال پیام به همه برایتان ارسال میشود',
'reply_markup'=>$back,
]);
}
}
elseif($step == 'addadmin'){
if(member($text)){
if(admin($text) == false){
setAdmin($text);
request('sendMessage',[
'chat_id'=>$chat_id,
'reply_to_message_id'=>$message_id,
'text'=>'✅ کاربر مورد نظر با موفقیت به لیست مدیران ربات اضافه شد',
'parse_mode'=>'MarkDown',
'reply_markup'=>$back,
]);
request('sendMessage',[
'chat_id'=>$text,
'text'=>'✅ شما به لیست ادمین های ربات اضافه شدید',
'parse_mode'=>'MarkDown',
]);
}else{
request('sendMessage',[
'chat_id'=>$chat_id,
'reply_to_message_id'=>$message_id,
'text'=>'❌ کاربر از قبل ادمین میباشد',
'parse_mode'=>'MarkDown',
'reply_markup'=>$back,
]);
}
}else{
request('sendMessage',[
'chat_id'=>$chat_id,
'reply_to_message_id'=>$message_id,
'text'=>'❌ کاربر جزء اعضای ربات نمیباشد',
'parse_mode'=>'MarkDown',
'reply_markup'=>$back,
]);
}
}
elseif($step == 'deladmin'){
if(member($text) == true){
if(admin($text) == true){
delAdmin($text);
request('sendMessage',[
'chat_id'=>$chat_id,
'reply_to_message_id'=>$message_id,
'text'=>'✅ کاربر مورد نظر با موفقیت از لیست مدیران ربات حذف شد',
'parse_mode'=>'MarkDown',
'reply_markup'=>$back,
]);
request('sendMessage',[
'chat_id'=>$text,
'reply_to_message_id'=>$message_id,
'text'=>'❌ شما از لیست ادمین های ربات حذف شدید',
'parse_mode'=>'MarkDown',
]);
}else{
request('sendMessage',[
'chat_id'=>$chat_id,
'reply_to_message_id'=>$message_id,
'text'=>'❌ کاربر از قبل ادمین نمیباشد',
'parse_mode'=>'MarkDown',
'reply_markup'=>$back,
]);
}
}else{
request('sendMessage',[
'chat_id'=>$chat_id,
'reply_to_message_id'=>$message_id,
'text'=>'❌ کاربر جزء اعضای ربات نمیباشد',
'parse_mode'=>'MarkDown',
'reply_markup'=>$back,
]);
}
}
}

if(isset($message["photo"])){
$photo = $message["photo"];
$file_id = $photo[count($photo)-1]["file_id"];
$getFile = json_decode(file_get_contents("https://api.telegram.org/bot".TOKEN."/getFile?file_id=".$file_id),true);
$result = $getFile["result"];
$file_path = $result["file_path"];
$url = 'https://api.telegram.org/file/bot'.TOKEN.'/'.$file_path;
$FaceApp = json_decode(file_get_contents("https://ganok.ir/FaceApp/api.php?url=".$url),true);
$code = $FaceApp["code"];
$did = $FaceApp["did"];
request('sendMessage',[
'chat_id'=>$chat_id,
'reply_to_message_id'=>$message_id,
'text'=>'در حال پردازش تصویر شما⏳'
]);
if($code != "" || $code != null || $did != "" || $did != null){
file_put_contents("data/users/".$from_id."/FaceApp.png",file_get_contents($url));
if(vip($from_id) == false){
request('deleteMessage',[
'chat_id'=>$chat_id,
'message_id'=>$message_id+1
]);
request('sendPhoto',[
'chat_id'=>$chat_id,
'reply_to_message_id'=>$message_id,
'photo'=>new CURLFile("data/users/".$from_id."/FaceApp.png"),
'caption'=>'✅ تصویر بالا در 19 افکت مختلف ساخته شد .

قصد مشاهده تصویر با کدام افکت رو دارید ؟ 👇

🆔 @'.$myUserName,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'ریش پروفسوری','callback_data'=>'pan'],['text'=>'پیر کردن چهره','callback_data'=>'old']],
[['text'=>'چهره دخترانه ','callback_data'=>'female'],['text'=>'چهره ی عینکی','callback_data'=>'glasses']],
[['text'=>'🔅 15 افکت بیشتر','callback_data'=>'vip']],
],
])
]);
unlink("data/users/".$from_id."/FaceApp.png");
}else{
request('deleteMessage',[
'chat_id'=>$chat_id,
'message_id'=>$message_id+1
]);
    request('sendPhoto',[
'chat_id'=>$chat_id,
'reply_to_message_id'=>$message_id,
'photo'=>new CURLFile("data/users/".$from_id."/FaceApp.png"),
'caption'=>'✅ تصویر بالا در 19 افکت مختلف ساخته شد .

قصد مشاهده تصویر با کدام افکت رو دارید ؟ 👇

🆔 @'.$myUserName,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'چهره مردانه','callback_data'=>'male'],['text'=>'پیر کردن چهره','callback_data'=>'old']],
[['text'=>'چهره خندان','callback_data'=>'smile'],['text'=>'چهره خندان 2','callback_data'=>'smile_2']],
[['text'=>'ریش گذاشتن','callback_data'=>'hipster'],['text'=>'ریش گذاشتن 2','callback_data'=>'pan']],
[['text'=>'چهره فیزیک دانها','callback_data'=>'heisenberg'],['text'=>'چهره عینکی','callback_data'=>'glasses']],
[['text'=>'چهره دخترانه','callback_data'=>'female'],['text'=>'چهره دخترانه 2','callback_data'=>'female_2']],
[['text'=>'ریش فر','callback_data'=>'lion'],['text'=>'ریش پروفسوری','callback_data'=>'pan']],
[['text'=>'کچل کردن','callback_data'=>'hitman'],['text'=>'چهره جذاب','callback_data'=>'hot']],
[['text'=>'چهره احساسی','callback_data'=>'impression'],['text'=>'جوان سازی چهره','callback_data'=>'young']],
[['text'=>'موی فر','callback_data'=>'wave'],['text'=>'موی چتری','callback_data'=>'bangs']],
[['text'=>'چهره آرایش شده','callback_data'=>'makeup']],
],
])
]);
unlink("data/users/".$from_id."/FaceApp.png");
    }
    }else{
sleep(1);
request('editMessagetext',[
'chat_id'=>$chat_id,
'message_id'=>$message_id+1,
'text'=>'❌ چهره ای در تصویر ارسالی شما یافت نشد !

لطفا یک تصویر دارای چهره ی انسان ارسال کنید

(پیشنهاد میشود تصویر ارسالی از فاصله ی نزدیک به چهره گرفته شده باشد)

🆔 @'.$myUserName,
]);
}
}
}else{
    request('sendMessage',[
'chat_id'=>$chat_id,
'reply_to_message_id'=>$message_id,
'text'=>'سلام '.$firstname.' 

برای  استفاده از ربات باید در کانال پشتیبانی ربات عضو شوید
برای عضویت در کانال [اینجا](https://t.me/'.$support.') کلیک کنید .


🆔 @'.$support,
'parse_mode'=>'MarkDown',
'disable_web_page_preview'=>true,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'✅ عضو شدم','callback_data'=>'join']],
],
])
]);
}
if(is_join($fromid,'@'.$support) == true){
    if($data == 'join'){
request('sendMessage',[
'chat_id'=>$chatid,
'reply_to_message_id'=>$messageid,
'text'=>' سلام ['.$callback["from"]["first_name"].'](tg://user?id='.$fromid.')

در ادامه یک عکس ارسال کنید

🆔 @'.$myUserName,
'parse_mode'=>'MarkDown',
]);
}
elseif(isset($update["callback_query"]["message"]["photo"]) && $data != 'vip'){
$photo = $update["callback_query"]["message"]["photo"];
$file_id = $photo[count($photo)-1]["file_id"];
$getFile = json_decode(file_get_contents("https://api.telegram.org/bot".TOKEN."/getFile?file_id=".$file_id),true);
$result = $getFile["result"];
$file_path = $result["file_path"];
$url = 'https://api.telegram.org/file/bot'.TOKEN.'/'.$file_path;
$FaceApp = json_decode(file_get_contents("https://ganok.ir/FaceApp/api.php?url=".$url),true);
$code = $FaceApp["code"];
$did = $FaceApp["did"];
$pic = 'http://ganok.ir/FaceApp/?emoji='.$data.'&did='.$did.'&code='.$code.'&cropped=true';
$type = str_replace('makeup','چهره آرایش شده',$data);
 $type = str_replace('female_2','چهره دخترانه 2',$type);
  $type = str_replace('female','چهره دخترانه',$type);
$type = str_replace('male','چهره مردانه',$type);
$type = str_replace('old','پیر کردن چهره',$type);
$type = str_replace('smile_2','چهره خندان 2',$type);
$type = str_replace('smile','چهره خندان',$type);
$type = str_replace('hipster','ریش گذاشتن',$type);
$type = str_replace('pan','ریش گذاشتن 2',$type);
$type = str_replace('چهره فیزیک دان ها','heisenberg',$type);
$type = str_replace('glasses','چهره عینکی',$type);
$type = str_replace('lion','ریش فر',$type);
$type = str_replace('pan','ریش پروفسوری',$type);
$type = str_replace('hitman','کچل کردن',$type);
$type = str_replace('hot','چهره جذاب',$type);
$type = str_replace('impression','چهره احساسی',$type);
$type = str_replace('young','جوان سازی',$type);
$type = str_replace('wave','موی فر',$type);
$type = str_replace('bangs','موی چتری',$type);
sendaction($chatid,'upload_photo');
request('sendPhoto',[
'chat_id'=>$chatid,
'reply_to_message_id'=>$messageid,
'photo'=>$pic,
'caption'=>'🔅 نام افکت : '.$type.'

Created By : @'.$myUserName
]);
}

elseif($data == 'vip'){
if(vip($fromid)==true){
request('editMessageReplyMarkup',[
'chat_id'=>$chatid,
'message_id'=>$messageid,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'چهره مردانه','callback_data'=>'male'],['text'=>'پیر کردن چهره','callback_data'=>'old']],
[['text'=>'چهره خندان','callback_data'=>'smile'],['text'=>'چهره خندان 2','callback_data'=>'smile_2']],
[['text'=>'ریش گذاشتن','callback_data'=>'hipster'],['text'=>'ریش گذاشتن 2','callback_data'=>'pan']],
[['text'=>'چهره فیزیک دانها','callback_data'=>'heisenberg'],['text'=>'چهره عینکی','callback_data'=>'glasses']],
[['text'=>'چهره دخترانه','callback_data'=>'female'],['text'=>'چهره دخترانه 2','callback_data'=>'female_2']],
[['text'=>'ریش فر','callback_data'=>'lion'],['text'=>'ریش پروفسوری','callback_data'=>'pan']],
[['text'=>'کچل کردن','callback_data'=>'hitman'],['text'=>'چهره جذاب','callback_data'=>'hot']],
[['text'=>'چهره احساسی','callback_data'=>'impression'],['text'=>'جوان سازی چهره','callback_data'=>'young']],
[['text'=>'موی فر','callback_data'=>'wave'],['text'=>'موی چتری','callback_data'=>'bangs']],
[['text'=>'چهره آرایش شده','callback_data'=>'makeup']],
],
])
]);
}else{
    request('sendMessage',[
'chat_id'=>$chatid,
'reply_to_message_id'=>$messageid,
'text'=>'کاربر گرامی ، 

برای مشاهده تصاویر بیشتر و دریافت لیست کامل افکت ها، باید اکانت خود را به VIP تبدیل کنید.

✅ با اینکار تمامی امکانات ربات برای شما فعال خواهد شد و می توانید از افکت های زیر استفاده نمایید :

▫️ پیر کردن چهره
▫️ چهره مردانه
▫️ چهره خندان 1
▫️ چهره خندان 2
▫️ ریش گذاشتن 1
▫️ ریش گذاشتن 2
▫️ چهره عینکی
▫️ چهره فیزیک دانها
▫️ چهره دخترانه 1
▫️ چهره دخترانه 2
▫️ ریش پرفسوری
▫️ ریش فر
▫️ چهره جذاب
▫️ کچل کردن
▫️ جوان سازی چهره
▫️ چهره احساسی
▫️ موی چتری
▫️ موی فر
▫️ چهره آرایش شده

✅ برای VIP کردن اکانت، کافیست '.$limit.' نفر با استفاده از لینک اختصاصی خود به ربات دعوت نمایید.

🆔 @'.$myUserName,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'دریافت لینک شخصی🌐','callback_data'=>'mylink']],
],
])
]);
}
}
elseif($data == 'mylink'){
request('sendPhoto',[
'chat_id'=>$chatid,
'reply_to_message_id'=>$messageid,
'photo'=>new CURLFile('logo.png'),
'caption'=>'اولین ربات تغییر چهره😍

 افکت های این ربات :
▫️پیر کردن چهره
▫️تغییر موی سر
▫️ریش گذاشتن و ده ها افکت جذاب دیگر👌

شروع 👇

T.me/'.$myUserName.'?start='.$fromid
]);
request('sendMessage',[
'chat_id'=>$chatid,
'reply_to_message_id'=>$messageid+1,
'text'=>'لینک دعوت شما با موفقیت ساخته شد 👆

اگر '.$limit.' نفر با استفاده از لینک بالا در ربات عضو شوند، تمامی امکانات ربات به صورت نامحدود برای شما فعال خواهد شد.

👤 شما تاکنون '.invite($fromid).' نفر را به ربات دعوت کرده اید.',
'disable_web_page_preview'=>true
]);
}
}else{
request('sendMessage',[
'chat_id'=>$chatid,
'reply_to_message_id'=>$messageid,
'text'=>'

برای  استفاده از ربات باید در کانال پشتیبانی ربات عضو شوید
برای عضویت در کانال [اینجا](https://t.me/'.$support.') کلیک کنید .


🆔 @'.$support,
'parse_mode'=>'MarkDown',
'disable_web_page_preview'=>true,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>'✅ عضو شدم','callback_data'=>'join']],
],
])
]);
}







