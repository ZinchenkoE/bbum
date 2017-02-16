<?php
header('Access-Control-Allow-Origin: *');

$mysqli = @new mysqli('localhost', 'vccaeosp_baby_shop', 'cpsess2881803224', 'vccaeosp_ezi');
if (mysqli_connect_errno()) { echo 'Подключение невозможно: ' . mysqli_connect_error(); }
$mysqli->query("SET NAMES 'utf8'");



$p = $_POST;

$title          = $p['title'];
$description    = $p['description'];
$origin_url     = $p['origin_url'];
$origin_price   = $p['origin_price'];
$gender         = $p['gender'] == 'Одежда для мальчиков' ? 1 : 2;
$images         = $p['images'];


$mysqli->query("INSERT INTO bs_product (title, description, origin_url, origin_price, gender, images)
VALUES (
'$title',
'$description',
'$origin_url',
'$origin_price',
'$gender',
'$images'
);");

