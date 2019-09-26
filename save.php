<?php

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
if ($conn->connect_error) {     // Check connection
    die("Connection failed: " . $conn->connect_error . "\nservername: " . $servername . "\ndbname:" . $dbname . "\nusername:" . $username . "\npassword: " . $password);
}

$users = "CREATE TABLE IF NOT EXISTS `users` (
    `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `username` varchar(255) NOT NULL,
    `email` varchar(255) NOT NULL,
    `token` varchar(255) NOT NULL
    )";

$tabsTable = "CREATE TABLE IF NOT EXISTS `tabs` (
    `tabsId` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `dateLoaded` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `url` varchar(255) NOT NULL,
    `userId` int,
        FOREIGN KEY (userId) 
            REFERENCES users(id)
        )";
$bookmarksTable = "CREATE TABLE IF NOT EXISTS `bookmarks` (
    `bookmarksId` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `dateAdded` TIMESTAMP NOT NULL,
    `url` varchar(255),
    `userId` int,
        FOREIGN KEY (userId) 
            REFERENCES users(id)
        )";
$historyTable = "CREATE TABLE IF NOT EXISTS `history` (
    `historyId` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `lastVisitTime` TIMESTAMP,
    `url` varchar(255),
    `visitCount` varchar(10),
    `userId` int,
        FOREIGN KEY (userId) 
            REFERENCES users(id)
        )";
$iPTable = "CREATE TABLE IF NOT EXISTS `IP` (
    `ipId` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `dateLoaded` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `externalIpAdress` varchar(20),
    `localIpAdress` varchar(20),
    `userId` int,
        FOREIGN KEY (userId) 
            REFERENCES users(id)
        )";
$positionTable = "CREATE TABLE IF NOT EXISTS `position` (
    `positionId` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `dateLoaded` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `lat` varchar(50),
    `lon` varchar(50),
    `country` varchar(50),
    `county` varchar(255),
    `postCode` varchar(20),
    `statePos` varchar(255),
    `city` varchar(100),
    `userId` int,
        FOREIGN KEY (userId) 
            REFERENCES users(id)
        )";

$tables = [$tabsTable, $bookmarksTable, $historyTable, $iPTable, $positionTable];


foreach ($tables as $k => $sql) {
    $query = @$conn->query($sql);

    if (!$query)
        $errors[] = "Table $k : Creation failed ($conn->error)";
    else
        $errors[] = "Table $k : Creation done";
}


foreach ($errors as $msg) {
    echo "$msg <br>";
}

$isConnectDB = $conn->query($tabsTable) === TRUE && $conn->query($users) === TRUE &&
    $conn->query($bookmarksTable) === TRUE && $conn->query($historyTable) === TRUE &&
    $conn->query($iPTable) === TRUE && $conn->query($positionTable) === TRUE;

if ($isConnectDB) {
    $IP_externalIpAdress_text = mysqli_real_escape_string($conn, $IP['externalIpAdress']);
    $IP_localIpAdress_text = mysqli_real_escape_string($conn, $IP['localIPAdress']);
    $position_lat_text = mysqli_real_escape_string($conn, $position['lat']);
    $position_lon_text = mysqli_real_escape_string($conn, $position['lon']);
    $position_country_text = mysqli_real_escape_string($conn, $position['adress']['country']);
    $position_county_text = mysqli_real_escape_string($conn, $position['adress']['county']);
    $position_postcode_text = mysqli_real_escape_string($conn, $position['adress']['postcode']);
    $position_state_text = mysqli_real_escape_string($conn, $position['adress']['state']);
    $position_city_text = mysqli_real_escape_string($conn, $position['adress']['city']);
}

foreach ($tabs as $key => $tab) {
    if ($conn->query($tabsTable) === TRUE) {
        $tabs_url_text = mysqli_real_escape_string($conn, $tab['url']);
    }
    $sqlTabs = "INSERT INTO tabs (dateLoaded, url, userId)
VALUES (now(), '$tabs_url_text', (SELECT id FROM users WHERE token = '$token')) ON DUPLICATE KEY UPDATE    
dateLoaded=now(),  url='$tabs_url_text', userId=(SELECT id FROM users WHERE token = '$token')";

    mysqli_query($conn, $sqlTabs);
}

foreach ($bookmarks as $key => $bookmark) {
    if ($conn->query($bookmarksTable) === TRUE) {
        $bookmarks_dateAdded_text = mysqli_real_escape_string($conn, $bookmark['dateAdded']);
        $bookmarks_url_text = mysqli_real_escape_string($conn, $bookmark['url']);
    }
    $sqlBookmarks = "INSERT INTO bookmarks (dateAdded, url, userId)
VALUES ('$bookmarks_dateAdded_text', '$bookmarks_url_text', (SELECT id FROM users WHERE token = '$token')) ON DUPLICATE KEY UPDATE    
dateAdded='$bookmarks_dateAdded_text',  url='$bookmarks_url_text', userId=(SELECT id FROM users WHERE token = '$token')";

    mysqli_query($conn, $sqlBookmarks);
}

foreach ($history as $key => $value) {
    if ($conn->query($historyTable) === TRUE) {
        $history_lastVisitTime_text = mysqli_real_escape_string($conn, $value['lastVisitTime']);
        $history_url_text = mysqli_real_escape_string($conn, $value['url']);
        $history_visitCount_text = mysqli_real_escape_string($conn, $value['visitCount']);
    }
    $sqlHistory = "INSERT INTO history (lastVisitTime, url, visitCount, userId)
VALUES ('$history_lastVisitTime_text', '$history_url_text', '$history_visitCount_text', (SELECT id FROM users WHERE token = '$token')) ON DUPLICATE KEY UPDATE    
lastVisitTime='$history_lastVisitTime_text',  url='$history_url_text', visitCount='$history_visitCount_text', userId=(SELECT id FROM users WHERE token = '$token')";

    mysqli_query($conn, $sqlHistory);
}


$sqlIP = "INSERT INTO IP (dateLoaded, externalIpAdress, localIpAdress, userId)
VALUES (now(), '$IP_externalIpAdress_text', '$IP_localIpAdress_text', (SELECT id FROM users WHERE token = '$token')) ON DUPLICATE KEY UPDATE    
dateLoaded=now(),  externalIpAdress='$IP_externalIpAdress_text', localIpAdress='$IP_localIpAdress_text', userId=(SELECT id FROM users WHERE token = '$token')";

$sqlPosition = "INSERT INTO position (dateLoaded, lat, lon, country, county, postCode, statePos, city, userId)
VALUES (now(), '$position_lat_text', '$position_lon_text', '$position_country_text', '$position_county_text',
'$position_postcode_text', '$position_state_text', '$position_city_text', (SELECT id FROM users WHERE token = '$token')) ON DUPLICATE KEY UPDATE    
dateLoaded=now(),  lat='$position_lat_text', lon='$position_lon_text', country='$position_country_text', 
county='$position_county_text', postCode='$position_postcode_text', statePos='$position_state_text', 
city='$position_city_text', userId=(SELECT id FROM users WHERE token = '$token')";

$isSaved = $conn->query($sqlTabs) === TRUE && $conn->query($sqlBookmarks) === TRUE &&
    $conn->query($sqlHistory) === TRUE && $conn->query($sqlIP) === TRUE && $conn->query($sqlPosition) === TRUE;

if ($isSaved) {
    echo "Page saved!";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();