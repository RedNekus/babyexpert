<?php
$to = "zakaz@babyexpert.by";
$subject = "Test mail";
$message = "Test message";
$from = "zakaz@babyexpert.by";
$headers = "From: $from";
mail($to,$subject,$message,$headers);
echo "Mail Sent.";
?>