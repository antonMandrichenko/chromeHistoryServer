<?php

// function console_log($data)
// {
//     echo '<script>';
//     echo 'console.log(' . $data . ')';
//     echo '</script>';
// }

// console_log($_POST["user"]["email"]);


include_once 'send.php';
$_POST = json_decode(file_get_contents('php://input'), true);
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
    `tabsId` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `dateLoaded` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `url` varchar(255) NOT NULL,
    `userId` int,
        FOREIGN KEY (userId) 
            REFERENCES $users(id)
        )";
$bookmarksTable = "CREATE TABLE IF NOT EXISTS `bookmarks` (
    `bookmarksId` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `dateAdded` TIMESTAMP NOT NULL,
    `url` varchar(255) NOT NULL,
    `userId` int,
        FOREIGN KEY (userId) 
            REFERENCES $users(id)
        )";
$historyTable = "CREATE TABLE IF NOT EXISTS `history` (
    `historyId` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `lastVisitTime` TIMESTAMP NOT NULL,
    `url` varchar(255) NOT NULL,
    `visitCount` varchar(10) NOT NULL,
    `userId` int,
        FOREIGN KEY (userId) 
            REFERENCES $users(id)
        )";
$iPTable = "CREATE TABLE IF NOT EXISTS `IP` (
    `ipId` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `dateLoaded` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `externalIpAdress` varchar(20) NOT NULL,
    `localIpAdress` varchar(20) NOT NULL,
    `userId` int,
        FOREIGN KEY (userId) 
            REFERENCES $users(id)
        )";
$positionTable = "CREATE TABLE IF NOT EXISTS `position` (
    `positionId` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `dateLoaded` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `lat` varchar(50) NOT NULL,
    `lon` varchar(50) NOT NULL,
    `country` varchar(50) NOT NULL,
    `county` varchar(255) NOT NULL,
    `postCode` varchar(20) NOT NULL,
    `state` varchar(255) NOT NULL,
    `city` varchar(100) NOT NULL,
    `userId` int,
        FOREIGN KEY (userId) 
            REFERENCES $users(id)
        )";
$isConnectDB = $conn->query($tabsTable) === TRUE && $conn->query($users) === TRUE &&
    $conn->query($bookmarksTable) === TRUE && $conn->query($historyTable) === TRUE &&
    $conn->query($iPTable) === TRUE && $conn->query($positionTable) === TRUE;

if ($isConnectDB) {
    $tabs_url_text = mysqli_real_escape_string($conn, $tabs['url']);
    $bookmarks_dateAdded_text = mysqli_real_escape_string($conn, $bookmarks['dateAdded']);
    $bookmarks_url_text = mysqli_real_escape_string($conn, $bookmarks['url']);
    $history_lastVisitTime_text = mysqli_real_escape_string($conn, $history['lastVisitTime']);
    $history_url_text = mysqli_real_escape_string($conn, $history['url']);
    $history_visitCount_text = mysqli_real_escape_string($conn, $history['visitCount']);
    $IP_externalIpAdress_text = mysqli_real_escape_string($conn, $IP['externalIpAdress']);
    $IP_localIpAdress_text = mysqli_real_escape_string($conn, $IP['localIPAdress']);
    $position_lat_text = mysqli_real_escape_string($conn, $position['lat']);
    $position_lon_text = mysqli_real_escape_string($conn, $position['lon']);
    $position_country_text = mysqli_real_escape_string($conn, $position['adress']['country']);
    $position_county_text = mysqli_real_escape_string($conn, $position['adress']['county']);
    $position_postcode_text = mysqli_real_escape_string($conn, $position['adress']['postcode']);
    $position_state_text = mysqli_real_escape_string($conn, $position['adress']['state']);
    $position_city_text = mysqli_real_escape_string($conn, $position['adress']['city'] || $position['adress']['village']);
}
