<?php
	require 'TwistOAuth.phar';
	require_once (dirname(__FILE__).'/twitterkey.php');
	require_once (dirname(__FILE__).'/tweet.php');
	$hashtag = "gochiusa";

	function h($str){
		return htmlspecialchars($str,ENT_QUOTES,'UTF-8');
	}


	function free_tweet(){
		global $CK,$CS,$AT,$AS,$hashtag;
		print "input you want to tweet\n";
		print "gochiusa/free_tweet > ";
		$stweet = trim(fgets(STDIN));
		if(!is_null($stweet)){
			try{
				$to = new TwistOAuth($CK,$CS,$AT,$AS);
				$to->post('statuses/update',array('status' => $stweet." #".$hashtag));
			}catch (TwistException $e){
				//set error message
				$error = $e->getMessage();

				$code = $e->getCode() ?: 500;
			}	
		}

	}
	function sethashtag($hashtag){	
		print "input hashtag default hashtag is #".$hashtag."\n";
		print "type 'x <ENTER>' to back menu\n";
		print "gochiusa/sethashtag> ";
		$tmp = trim(fgets(STDIN));
		if($tmp === "x"){
			return $hashtag;
		}else{
			return $tmp;
		}			
	}
	function printstr($array){
		print "type number you want to tweet\n";
		print "type 'exit <ENTER>' to back menu\n";
		$i=0;	
		foreach($array as $val){
			print $i.":".$val."\n";
			$i++;
		}
	}

	date_default_timezone_set('Asia/Tokyo');
	function template_tweet(){
		global $array,$CK,$CS,$AT,$AS,$hashtag;
		printstr($array);
		print "gochiusa/temp > ";
		$stdin = trim(fgets(STDIN));
		try{
			$to = new TwistOAuth($CK,$CS,$AT,$AS);
			while(!($stdin === "exit")){
				echo "you tweet ";
				echo $array[$stdin]." #".$hashtag."\n";
				$to->post('statuses/update',array('status' => $array[$stdin]." #".$hashtag));
				printstr($array);
				print "gochiusa/temp > ";
				$stdin = trim(fgets(STDIN));
			}
		}catch (TwistException $e){
			//set error message
			$error = $e->getMessage();

			$code = $e->getCode() ?: 500;
		}
	}

	function menu(){
		global $hashtag;
		print "type 'h <ENTER>' to get help\n";
		$stdin = "first";
		while(!($stdin === "exit")){
			print "gochiusa > ";
			$stdin = trim(fgets(STDIN));
			switch ($stdin) {
				#free_tweet
				case "tweet":
					# code...
					free_tweet();
					break;

				case "set":
					$hashtag = sethashtag($hashtag);
					break;

				case "temp":
					template_tweet();
					break;

				case "h":
					print "command:\n    h このヘルプを表示する\n    tweet ツイートする画面へ移行する
    set ハッシュタグを設定する\n    temp テンプレートツイート画面へ移行する\n    exit gochiusa_tweetを終了する\n";
						break;
				default:
					# code...
					print $stdin.": Command not found.\n";
					break;

			}
		}

	}
	menu();
?>
