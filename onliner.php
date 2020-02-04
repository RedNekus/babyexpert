<?php

$process = curl_init("https://b2bapi.onliner.by/oauth/token");
curl_setopt($process, CURLOPT_HTTPHEADER, array('Accept: application/json'));
curl_setopt($process, CURLOPT_USERPWD, "736dc40048d4c97f09ef:5847c63a35da8091577bce8ccd7f8923b1f9781f");
curl_setopt($process, CURLOPT_POST, 1);
curl_setopt($process, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($process, CURLOPT_POSTFIELDS, array('grant_type' => 'client_credentials'));
$result = curl_exec($process);
curl_close($process);

echo $result;