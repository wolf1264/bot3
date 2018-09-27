<?php
ob_start();
define('TOKEN','579825326:AAH2F3eVjQRorsKQPB47UhJLAHGMuHJ3dVw');
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
$chat_id = file_get_contents("chat_id.txt");
$message_id = file_get_contents("message_id.txt");
$count = count(scandir("data/users/"));
$user = scandir("data/users/");
$start = file_get_contents("start.txt");
$send_to_all = file_get_contents("send_to_all.txt");

$end = $start+100;
if($send_to_all == "true"){
for($x=$start;$x<$end;$x++){
$user_id = $user["$x"];
 if($i>$count){
file_put_contents("send_to_all.txt","false");
request('sendMessage',[
'chat_id'=>$chat_id,
'reply_to_message_id'=>$message_id,
'text'=>'✅ پیام شما به '.$count.' نفر ارسال شد',
]);
unlink("message_id.txt");
unlink("chat_id.txt");
}else{
request('forwardMessage',[
'chat_id'=>$user_id,
'from_chat_id'=>$chat_id,
'message_id'=>$message_id,
]);
}
}

file_put_contents("start.txt",$end);
}else{
    echo 'قبلا ارسال شده';
}