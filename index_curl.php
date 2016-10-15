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
$config = new Config($username,$password,$url);
$run = new Signflow($config);
if($run->testURL()){
	//登入
	if(!$run->login()) exit();
	//簽到
	$run->signin();
	//結束
	$run->close();
};


//class
class Config{
	var $_username,$_password,$_url;
        var $_op = "loginVerify";
	function __construct($username,$password,$url){
		$this->_username = $username;
		$this->_password = $password;
		$this->_url = $url;
	}
	function username(){
		return $this->_username;
	}
	function password(){
		return $this->_password;
	}
	function op(){
		return $this->_op;
	}
	function URL(){
		return $this->_url;
	}
	function loginURL(){
		return $this->_url."index.php";
	}
	function signURL(){
		return $this->_url."oncall.php?op=sign";
	}
}


class Signflow{
	var $_config;
	var $_ckfile;
	var $_ch;
	function __construct(Config $config){
		$this->_config = $config;
	}
	//測試網站
	function testURL(){
		$file_headers = @get_headers($this->_config->URL());
		if(!$file_headers || $file_headers[0] == 'HTTP/1.1 404 Not Found'){
			print_r("URL $targetURL does not exist\n");
	        	return false;
		}
		return true;
	}
	//登入
	function login(){
		$this->_ckfile = tempnam ("/tmp", "CURLCOOKIE");
		$this->_ch = curl_init ();
		curl_setopt($this->_ch, CURLOPT_URL, $this->_config->loginURL());
		curl_setopt($this->_ch, CURLOPT_POST, true);
		curl_setopt($this->_ch, CURLOPT_POSTFIELDS, http_build_query( 
			array( "op"=>"loginVerify", 
				"portalid"=>$this->_config->username(),
				"pwd"=>$this->_config->password()
				) 
			)); 
		curl_setopt ($this->_ch, CURLOPT_COOKIEJAR, $this->_ckfile); 
		curl_setopt ($this->_ch, CURLOPT_RETURNTRANSFER, true);
		$content = curl_exec($this->_ch); 
		if(strpos($content,"location.href='index.php?op=index'")===false){
 		       print_r("登入失敗\n程式終止\n");
		       return false;
		}
		print_r("登入成功\n");
		return true;
	}
	//簽到
	function signin(){
		curl_setopt($this->_ch, CURLOPT_POST, 0);
		curl_setopt($this->_ch, CURLOPT_HTTPGET, 1);
		curl_setopt($this->_ch, CURLOPT_URL, $this->_config->signURL());
		$content = curl_exec($this->_ch);
		print_r("簽到完成\n");
	}
	//關閉連結
	function close(){
		curl_close($this->_ch);
	}

}

?>
