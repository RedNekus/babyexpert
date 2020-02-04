<?php

error_reporting( E_ALL);

ini_set('display_errors', 1);

$id_api = '18aceee7f8d04733b6a6002db4255dbb';

$token = '5ac9fa3242844dae8b3cc78613f21fe7';

$text = ' Мой новый текст....'; 

$str_len = strlen($text); 

$headers= array(

        'POST' => '/api/v2/hosts/20606333/original-texts/ HTTP/1.1',

        'Host'=> 'webmaster.yandex.ru', 

        'Authorization'=> 'OAuth '.$token, 

        'Content-Length'=> '"'.$str_len.'"',

'Content-type'=> 'application/x-www-form-urlencoded');

$url = "https://oauth.yandex.ru/";

$data = '<original-text><content>'.urlencode($text).'</content></original-text>';

 

    $handle=curl_init() ;

    curl_setopt($handle, CURLOPT_URL, $url);

    curl_setopt($handle, CURLOPT_HTTPHEADER, $headers);

    curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false);

    curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, false);

    curl_setopt($handle, CURLOPT_POST, true);

    curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);

    curl_setopt($handle, CURLOPT_POSTFIELDS, $data);

    curl_setopt($handle, CURLOPT_USERAGENT, 'babyexpert.by');

    $response=curl_exec($handle);

    $code=curl_getinfo($handle, CURLINFO_HTTP_CODE);

    return array("code"=>$code,"response"=>$response);

   // var_dump($response);

 

?>