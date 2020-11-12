<?php

$consumerKey = 'xxx';
$consumerSecret = 'xxx';
$authToken = 'xxx';
$authSecret = 'xxx';


$page = 0;
$limit = 100;
do {

    $curl = curl_init();

    $ts = time();
    $nonce = sha1($ts);
    $baseUrl = "http://b2b.cifrotech.ua/api/rest/getstock";
    $queryUrl = "?limit=$limit&page=$page";
    $base = 'GET' . '&' . rawurlencode($baseUrl) . '&'
            . rawurlencode("limit=$limit")
            . rawurlencode("&oauth_consumer_key=$consumerKey")
            . rawurlencode("&oauth_nonce=$nonce")
            . rawurlencode("&oauth_signature_method=HMAC-SHA1")
            . rawurlencode("&oauth_timestamp=$ts")
            . rawurlencode("&oauth_token=$authToken")
            . rawurlencode("&oauth_version=1.0")
            . rawurlencode("&page=$page");
    $key = rawurlencode($consumerSecret) . '&' . rawurlencode($authSecret);
    $sign = rawurlencode(base64_encode(hash_hmac("sha1", $base, $key, true)));
    $url = $baseUrl . $queryUrl;
    curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
            "Authorization: OAuth oauth_consumer_key=\"$consumerKey\",oauth_nonce=\"$nonce\",oauth_signature_method=\"HMAC-SHA1\",oauth_timestamp=\"$ts\",oauth_token=\"$authToken\",oauth_version=\"1.0\",oauth_signature=\"$sign\"",
        ),
    ));

    $response = curl_exec($curl);
    $out = json_decode($response, true);
    //print_r($out);
    foreach ($out as $key => $item) {
        $name = $item['name'];
        $price = $item['price'];
        $stock = $item['stock_status_label'];
        echo "\n$name|$price|$stock";
    }
    curl_close($curl);
    $page++;
} while (count($out) == $limit);

