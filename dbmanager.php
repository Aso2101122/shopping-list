<?php
function getDb()
{
    // データベースへの接続を確認
    $dsn = 'mysql:host=mysql153.phy.lolipop.lan;
    dbname=LAA1290588-school;
    charset=utf8';
    $username = 'LAA1290588';
    $password = 'phpdb5312';
    $pdo = new PDO($dsn, $username, $password);
    return $pdo;
}
