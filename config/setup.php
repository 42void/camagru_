<?php
require_once './config/database.php';

try{
    $pdo = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // ERRMODE_WARNING | ERRMODE_EXCEPTION | ERRMODE_SILENT
} catch(Exception $e) {
    echo "Impossible d'accéder à la base de données SQLite : ".$e->getMessage();
    die();
}

$prep = $pdo->prepare("CREATE TABLE IF NOT EXISTS tbl_users (
    userID       INTEGER         PRIMARY KEY AUTOINCREMENT NOT NULL,
    userName     VARCHAR( 100 )  NOT NULL,
    userEmail    VARCHAR( 100 )  NOT NULL UNIQUE,
    userPass     VARCHAR( 100 )  NOT NULL,
    userStatus   VARCHAR         NOT NULL DEFAULT 'N',
    tokenCode    VARCHAR( 100 )  NOT NULL
);");
$prep->execute();

$prep = $pdo->prepare("CREATE TABLE IF NOT EXISTS pictures (
    pictureID INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
    images TEXT UNIQUE,
    userID INTEGER,
    FOREIGN KEY(`userID`) REFERENCES `tbl_users`(`userID`) ON DELETE SET NULL
);");
$prep->execute();

$prep = $pdo->prepare("CREATE TABLE IF NOT EXISTS likes (
    pictureID INTEGER NOT NULL,
    userID INTEGER NOT NULL,
    FOREIGN KEY(`userID`) REFERENCES `tbl_users`(`userID`) ON DELETE SET NULL,
    FOREIGN KEY(`pictureID`) REFERENCES `picture`(`pictureID`) ON DELETE SET NULL
);");
$prep->execute();

$prep = $pdo->prepare("CREATE TABLE IF NOT EXISTS comments (
    pictureID INTEGER NOT NULL,
    userID INTEGER NOT NULL,
    comment TEXT,
    FOREIGN KEY(`userID`) REFERENCES `tbl_users`(`userID`) ON DELETE SET NULL,
    FOREIGN KEY(`pictureID`) REFERENCES `picture`(`pictureID`) ON DELETE SET NULL
);");
$prep->execute();
?>
