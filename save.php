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

if (!$conn->set_charset("utf8")) {
    printf("Error loading character set utf8: %s\n", $conn->error);
} else {
    printf("Current character set: %s\n", $conn->character_set_name());
}

$users = "CREATE TABLE IF NOT EXISTS `users` (
`id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
`date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
`username` varchar(255) NOT NULL,
`email` varchar(255) NOT NULL,
`token` varchar(255) NOT NULL
)";

if (!$token && $user['email']) {
    $token = md5($user['email']);
    $userIdSql = "SELECT id FROM users WHERE token = '$token'";
    $result = $conn->query($userIdSql);
}

if ($token || $result->num_rows > 0) {
    
    $tabsTable = "CREATE TABLE IF NOT EXISTS `tabs` (
    `tabsId` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `dateLoaded` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `url` varchar(255) NOT NULL,
    `active` BOOLEAN,
    `audible` BOOLEAN,
    `autoDiscardable` BOOLEAN,
    `discarded` BOOLEAN,
    `highlighted` BOOLEAN,
    `incognito` BOOLEAN,
    `muted` BOOLEAN,
    `pinned` BOOLEAN,
    `selected` BOOLEAN,
    `status` varchar(50),
    `title` varchar(255),
    `windowId` int,
    `userId` int,
        FOREIGN KEY (userId) 
            REFERENCES users(id)
        )";
    $bookmarksTable = "CREATE TABLE IF NOT EXISTS `bookmarks` (
    `bookmarksId` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `dateAdded` varchar(50),
    `url` varchar(255),
    `dateGroupModified` varchar(50),
    `id` varchar(10),
    `parentId` varchar(10),
    `index` varchar(10),
    `title` varchar(255),
    `userId` int,
        FOREIGN KEY (userId) 
            REFERENCES users(id)
        )";
    $historyTable = "CREATE TABLE IF NOT EXISTS `history` (
    `historyId` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `lastVisitTime` varchar(50),
    `url` varchar(255),
    `visitCount` varchar(10),
    `title` varchar(255),
    `id` varchar(10),
    `typedCount` varchar(10),
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
    `house_number` varchar(50),
    `neighbourhood` varchar(50),
    `road` varchar(50),
    `suburb` varchar(50),
    `village` varchar(100),
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
        $position_house_number_text = mysqli_real_escape_string($conn, $position['adress']['house_number']);
        $position_neighbourhood_text = mysqli_real_escape_string($conn, $position['adress']['neighbourhood']);
        $position_road_text = mysqli_real_escape_string($conn, $position['adress']['road']);
        $position_suburb_text = mysqli_real_escape_string($conn, $position['adress']['suburb']);
        $position_village_text = mysqli_real_escape_string($conn, $position['adress']['village']);

        foreach ($tabs as $key => $tab) {
            if ($conn->query($tabsTable) === TRUE) {
                $tabs_url_text = mysqli_real_escape_string($conn, $tab['url']);
                $tabs_active_text = mysqli_real_escape_string($conn, $tab['active']);
                $tabs_audible_text = mysqli_real_escape_string($conn, $tab['audible']);
                $tabs_autoDiscardable_text = mysqli_real_escape_string($conn, $tab['autoDiscardable']);
                $tabs_discarded_text = mysqli_real_escape_string($conn, $tab['discarded']);
                $tabs_highlighted_text = mysqli_real_escape_string($conn, $tab['highlighted']);
                $tabs_incognito_text = mysqli_real_escape_string($conn, $tab['incognito']);
                $tabs_muted_text = mysqli_real_escape_string($conn, $tab['mutedInfo']['muted']);
                $tabs_pinned_text = mysqli_real_escape_string($conn, $tab['pinned']);
                $tabs_selected_text = mysqli_real_escape_string($conn, $tab['selected']);
                $tabs_status_text = mysqli_real_escape_string($conn, $tab['status']);
                $tabs_title_text = mysqli_real_escape_string($conn, $tab['title']);
                $tabs_windowId_text = mysqli_real_escape_string($conn, $tab['windowId']);
            }
            $sqlTabs = "INSERT INTO tabs (dateLoaded, url, userId, active, audible, autoDiscardable, 
    discarded, highlighted, incognito, muted, pinned, selected, status, title, windowId)
VALUES (now(), '$tabs_url_text', (SELECT id FROM users WHERE token = '$token'), 
'$tabs_active_text', '$tabs_audible_text', '$tabs_autoDiscardable_text', '$tabs_discarded_text',
'$tabs_highlighted_text', '$tabs_incognito_text', '$tabs_muted_text', '$tabs_pinned_text', ' $tabs_selected_text',
'$tabs_status_text', '$tabs_title_text', '$tabs_windowId_text') ON DUPLICATE KEY UPDATE    
dateLoaded=now(),  url='$tabs_url_text', userId=(SELECT id FROM users WHERE token = '$token'),
active='$tabs_active_text', audible='$tabs_audible_text', autoDiscardable='$tabs_autoDiscardable_text',
discarded='$tabs_discarded_text', highlighted='$tabs_highlighted_text', incognito='$tabs_incognito_text',
muted='$tabs_muted_text', pinned='$tabs_pinned_text', selected='$tabs_selected_text', status='$tabs_status_text',
title='$tabs_title_text', windowId='$tabs_windowId_text'";

            mysqli_query($conn, $sqlTabs);
        }

        foreach ($bookmarks as $key => $bookmark) {
            if ($conn->query($bookmarksTable) === TRUE) {
                $bookmarks_dateAdded_text = mysqli_real_escape_string($conn, $bookmark['dateAdded']);
                $bookmarks_url_text = mysqli_real_escape_string($conn, $bookmark['url']);
                $bookmarks_dateGroupModified_text = mysqli_real_escape_string($conn, $bookmark['dateGroupModified']);
                $bookmarks_id_text = mysqli_real_escape_string($conn, $bookmark['id']);
                $bookmarks_parentId_text = mysqli_real_escape_string($conn, $bookmark['parentId']);
                $bookmarks_index_text = mysqli_real_escape_string($conn, $bookmark['index']);
                $bookmarks_title_text = mysqli_real_escape_string($conn, $bookmark['title']);
            }
            $sqlBookmarks = "INSERT INTO bookmarks (dateAdded, url, userId, dateGroupModified, id, parentId, `index`, title)
VALUES ('$bookmarks_dateAdded_text', '$bookmarks_url_text', (SELECT id FROM users WHERE token = '$token'), '$bookmarks_dateGroupModified_text',
'$bookmarks_id_text', '$bookmarks_parentId_text', '$bookmarks_index_text', '$bookmarks_title_text') ON DUPLICATE KEY UPDATE    
dateAdded='$bookmarks_dateAdded_text', url='$bookmarks_url_text', userId=(SELECT id FROM users WHERE token = '$token'),
dateGroupModified='$bookmarks_dateGroupModified_text', id='$bookmarks_id_text', parentId='$bookmarks_parentId_text', `index`='$bookmarks_index_text',
title='$bookmarks_title_text'";

            mysqli_query($conn, $sqlBookmarks);
        }

        foreach ($history as $key => $value) {
            if ($conn->query($historyTable) === TRUE) {
                $history_lastVisitTime_text = mysqli_real_escape_string($conn, $value['lastVisitTime']);
                $history_url_text = mysqli_real_escape_string($conn, $value['url']);
                $history_visitCount_text = mysqli_real_escape_string($conn, $value['visitCount']);
                $history_title_text = mysqli_real_escape_string($conn, $value['title']);
                $history_id_text = mysqli_real_escape_string($conn, $value['id']);
                $history_typedCount_text = mysqli_real_escape_string($conn, $value['typedCount']);
            }
            $sqlHistory = "INSERT INTO history (lastVisitTime, url, visitCount, userId,  title, id, typedCount)
VALUES ('$history_lastVisitTime_text', '$history_url_text', '$history_visitCount_text', (SELECT id FROM users WHERE token = '$token'),
'$history_title_text', '$history_id_text', '$history_typedCount_text') ON DUPLICATE KEY UPDATE    
lastVisitTime='$history_lastVisitTime_text',  url='$history_url_text', visitCount='$history_visitCount_text', userId=(SELECT id FROM users WHERE token = '$token'),
title='$history_title_text', id=' $history_id_text', typedCount='$history_typedCount_text'";

            mysqli_query($conn, $sqlHistory);
        }


        $sqlIP = "INSERT INTO IP (dateLoaded, externalIpAdress, localIpAdress, userId)
VALUES (now(), '$IP_externalIpAdress_text', '$IP_localIpAdress_text', (SELECT id FROM users WHERE token = '$token')) ON DUPLICATE KEY UPDATE    
dateLoaded=now(),  externalIpAdress='$IP_externalIpAdress_text', localIpAdress='$IP_localIpAdress_text', userId=(SELECT id FROM users WHERE token = '$token')";

        $sqlPosition = "INSERT INTO position (dateLoaded, lat, lon, country, county, postCode, statePos, city, userId, house_number,
neighbourhood, road, suburb, village)
VALUES (now(), '$position_lat_text', '$position_lon_text', '$position_country_text', '$position_county_text',
'$position_postcode_text', '$position_state_text', '$position_city_text', (SELECT id FROM users WHERE token = '$token'),
'$position_house_number_text', '$position_neighbourhood_text', '$position_road_text', '$position_suburb_text', '$position_village_text') ON DUPLICATE KEY UPDATE    
dateLoaded=now(),  lat='$position_lat_text', lon='$position_lon_text', country='$position_country_text', 
county='$position_county_text', postCode='$position_postcode_text', statePos='$position_state_text', 
city='$position_city_text', userId=(SELECT id FROM users WHERE token = '$token'), house_number='$position_house_number_text',
neighbourhood='$position_neighbourhood_text', road='$position_road_text', suburb='$position_suburb_text', village='$position_village_text'";

        $isSaved = $conn->query($sqlTabs) === TRUE && $conn->query($sqlBookmarks) === TRUE &&
            $conn->query($sqlHistory) === TRUE && $conn->query($sqlIP) === TRUE && $conn->query($sqlPosition) === TRUE;

        if ($isSaved) {
            echo "Page saved!";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        echo "Error: " . $table . "<br>" . $conn->error;
    }
}

$conn->close();
