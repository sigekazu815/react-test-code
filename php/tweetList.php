<?php
/**************************************************
	【参考】
	[GET statuses/home_timeline]のお試しプログラム

	認証方式: アクセストークン

	配布: SYNCER
	公式ドキュメント: https://dev.twitter.com/rest/reference/get/statuses/home_timeline
	日本語解説ページ: https://syncer.jp/Web/API/Twitter/REST_API/GET/statuses/home_timeline/

**************************************************/

	// 設定
	$api_key = 'SpS0nopzB2X3nM4XVD3zfaN77';		// APIキー
	$api_secret = 'nd5VmohTnpV6y8euxxiMVopBFHkCPdfgxlFa3f4q2urXaz8Wb7';		// APIシークレット
	$access_token = '896006708148883456-wi2ajCV23038c4HujODTlNyrzDK7wqP';		// アクセストークン
	$access_token_secret = 'bLc4leOnB6CMMiHyBASy9DGcdG2smoQPocqqhimYh01Ok';		// アクセストークンシークレット
//	$request_url = 'https://api.twitter.com/1.1/statuses/home_timeline.json';		// エンドポイント
	$request_url = 'https://api.twitter.com/1.1/search/tweets.json';		// エンドポイント
	$request_method = 'GET';
	
	// キーを作成する (URLエンコードする)
	$signature_key = rawurlencode($api_secret).'&'.rawurlencode($access_token_secret);
	
	date_default_timezone_set('Asia/Tokyo');
	
	// 検索用パラメータ
	$queryParams = array(
		"q" => "なう",
		"lang" => "ja",
		"result_type" => "mixed",
		"count" => "100",
		//"until" => date('Y-m-d'),
		"geocode" => "35.698548,139.7071583,150km",
		"include_entities" => "true",
	) ;

	// 認証用パラメータ
	$OAuthParams = array(
		'oauth_token' => $access_token,
		'oauth_consumer_key' => $api_key,
		'oauth_signature_method' => 'HMAC-SHA1',
		'oauth_timestamp' => time(),
		'oauth_nonce' => microtime(),
		'oauth_version' => '1.0'
	);
	
	$requestParams = array_merge($queryParams, $OAuthParams);

	// 連想配列をアルファベット順に並び替える
	ksort($requestParams);

	// パラメータの連想配列を[キー=値&キー=値...]の文字列に変換する
	$request_params = http_build_query($requestParams, '', '&');

	// 一部の文字列をフォロー
	$request_params = str_replace(array('+', '%7E'), array('%20', '~'), $request_params);

	// 変換した文字列をURLエンコードする
	$request_params = rawurlencode($request_params);

	// リクエストメソッドをURLエンコードする
	// ここでは、URL末尾の[?]以下は付けないこと
	$encoded_request_method = rawurlencode($request_method);
 
	// リクエストURLをURLエンコードする
	$encoded_request_url = rawurlencode($request_url);

	// リクエストメソッド、リクエストURL、パラメータを[&]で繋ぐ
	$signature_data = $encoded_request_method.'&'.$encoded_request_url.'&'.$request_params;

	// キー[$signature_key]とデータ[$signature_data]を利用して、HMAC-SHA1方式のハッシュ値に変換する
	$hash = hash_hmac('sha1', $signature_data, $signature_key, TRUE);

	// base64エンコードして、署名[$signature]が完成する
	$signature = base64_encode($hash);

	// パラメータの連想配列、[$requestParams]に、作成した署名を加える
	$requestParams['oauth_signature'] = $signature;

	// パラメータの連想配列を[キー=値,キー=値,...]の文字列に変換する
	$header_params = http_build_query($requestParams, '', ',');

	// リクエスト用のコンテキスト
	$context = array(
		'http' => array(
			'method' => $request_method,
			'header' => array(
				'Authorization: OAuth '.$header_params,
			),
		),
	);
	
	// パラメータがある場合、URLの末尾に追加
	if($queryParams) {
		$request_url.='?'.http_build_query($queryParams);
	}

	// cURLを使ってリクエスト
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, $request_url);
	curl_setopt($curl, CURLOPT_HEADER, 1); 
	curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $context['http']['method']);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_HTTPHEADER, $context['http']['header']);

	// タイムアウトまでの秒数
	curl_setopt($curl, CURLOPT_TIMEOUT, 10);
	$res1 = curl_exec($curl);
	$res2 = curl_getinfo($curl);
	curl_close($curl);

	// 取得したデータ
	$json = substr($res1, $res2['header_size']);
	echo $json;
