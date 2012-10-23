<?php

// 各種設定ここから
define("config_callurlmethod", "curl"); // APIを呼び出す方法を指定。現在はcurlを指定した場合はcurlを使用し、それ以外が指定された場合はfile_get_contentsを使用
define("config_cloudstack_api_baseurl", "http://examples.com/"); // baseURLを設定
define("config_cloudstack_api_apipath", "/client/api?"); // APIパスを設定（デフォルトでは/client/api?）
define("config_cloudstack_api_key", ""); // APIキーを設定
define("config_cloudstack_api_secret", ""); // APIシークレットを設定
// 各種設定ここまで

foreach($_GET as $key => $val){
	$command["{$key}"] = $val;	// URL引数をパース
}
CloudStackAPICall($command);	// API呼び出し関数を実行

// API呼び出し関数…引数：実行したいAPIのコマンドやパラメータを連想配列に格納したもの
function CloudStackAPICall($command){
	switch(config_callurlmethod){	// APIを呼び出す方法に応じて処理を切り替え
		case "curl":	// curlが指定された場合
			$ch = curl_init();	// curlを初期化
			curl_setopt($ch, CURLOPT_URL, CloudStackAPICreateURL($command));	// APIを呼び出すURLを作成する関数を実行し、結果をcurlで取得するURLに設定
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);						// curlのオプション設定
			$result = curl_exec($ch);											// curlを実行し、結果を取得
			curl_close();														// curlを閉じる
			break;
			
		default:	// 何も指定されなかった場合
			$result = file_get_contents(CloudStackAPICreateURL($command));		// APIを呼び出すURLを作成する関数を実行し、結果をfile_get_contentsで取得
			break;
	}
	if(strcmp($command["response"], "json") == 0){	// 結果の受け取りにJSON形式が指定されている場合
		header('Content-Type: application/json; charset=utf-8');	// JSON用のヘッダを出力
	}
	else{	// 結果の受け取り形式が指定されていない場合
		header('Content-type: application/xml; charset=UTF-8');		// XML用のヘッダを出力
	}
	echo $result;	// 結果を表示
}

// CloudStackのAPIを呼び出すURLを作成する関数…引数：$command=実行したいAPIのコマンドやパラメータを連想配列に格納したもの、（以降は省略可能であり、省略した場合は設定から読み込む）$baseurl=baseURL、$api_key=CloudStackのAPIキー、$api_secret=CloudStackのAPIシークレット
function CloudStackAPICreateURL($command, $baseurl = config_cloudstack_api_baseurl, $apipath = config_cloudstack_api_apipath, $api_key = config_cloudstack_api_key, $api_secret = config_cloudstack_api_secret){
	$callurl = "";
	foreach($command as $key => $val){	// 実行したいAPIのコマンドやパラメータを走査し、整形
		if(strcmp($key, "apiKey") != 0){	// APIキーは最後に追記するため無視
			if(strcmp($callurl, "") != 0)	$callurl .= "&";	// 2つ目以降のコマンドやパラメータを追記する際にはアンパサンドも追記
			$callurl .= $key."=".$val;	// キーと値を追記
		}
	}
	return $baseurl.$apipath.$callurl."&apikey=".$api_key."&signature=".CloudStackAPICreateSignature($command);	// シグネチャを作成し、APIを呼び出すURLを生成して返す
}

// CloudStackのAPIを呼び出す際に必要なシグネチャを生成する関数…引数：CloudStackAPICreateURLと同様のため省略
function CloudStackAPICreateSignature($command, $baseurl = config_cloudstack_api_baseurl, $apipath = config_cloudstack_api_apipath, $api_key = config_cloudstack_api_key, $api_secret = config_cloudstack_api_secret){
	$command["apiKey"] = $api_key;	// APIキーをコマンドやパラメータが格納された配列に格納
	uksort($command, "strnatcmp");	// ソート
	
	$signature_originaltext = "";
	foreach($command as $key => $val){	// 実行したいAPIのコマンドやパラメータを走査し、整形
		if(strcmp($signature_originaltext, "") != 0)	$signature_originaltext .= "&";
		$signature_originaltext .= $key."=".urlencodeRFC3986($val);
	}
	return  urlencodeRFC3986(base64_encode(hash_hmac("sha1", strtolower($signature_originaltext), $api_secret, true)));
}
function urlencodeRFC3986($str){
	return str_replace('+',' ',str_replace('%7E', '~', rawurlencode($str)));
}

?>