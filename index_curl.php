<?php
//////////////////////////////////////////
//這隻程是純粹用來交流 php curl 技術的
//請勿用在線上環境
//違者自行負責
//////////////////////////////////////////


// var
$url = 'http://西斯部落格.123.com.tw/';
$username = '我的名字';
$password = '神秘密碼';



//main
print_r("ready...\n");
print_r("connect...$url\n");

//test url exist
if(!testURL($url)) exit();


//登入開始
$loginurl = $url."index.php";
$ckfile = tempnam ("/tmp", "CURLCOOKIE");
$ch = curl_init ($loginurl);
curl_setopt($ch, CURLOPT_URL, $loginurl);
curl_setopt($ch, CURLOPT_POST, true); // 啟用POST
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query( array( "op"=>"loginVerify", "portalid"=>$username,"pwd"=>$password) )); 
curl_setopt ($ch, CURLOPT_COOKIEJAR, $ckfile); 
curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
$content = curl_exec($ch); 

//驗證登入
if(strpos($content,"location.href='index.php?op=index'")===false){
	print_r("登入失敗\n程式終止\n");
        exit();
}
print_r("登入成功\n");

//簽到開始
curl_setopt($ch, CURLOPT_HTTPGET, 1);
curl_setopt($ch, CURLOPT_POST, 0);
$signurl = $url."oncall.php?op=sign";
curl_setopt($ch, CURLOPT_URL, $signurl);
$content = curl_exec($ch);
print_r("簽到成功\n");

curl_close($ch);




function testURL($targetURL){
	$file_headers = @get_headers($targetURL);
	if(!$file_headers || $file_headers[0] == 'HTTP/1.1 404 Not Found'){
		print_r("URL $targetURL does not exist\n");
        	return false;
		}
	return true;
}

?>
