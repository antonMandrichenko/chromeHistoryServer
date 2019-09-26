<?php
$name = $_POST['name'];
$email = $_POST['email'];
$name = htmlspecialchars($name);
$email = htmlspecialchars($email);
$name = urldecode($name);
$email = urldecode($email);
$name = trim($name);
$email = trim($email);

$token   = md5($email);
$to      = $email; // Send email to our user
$subject = 'Confirm your e-mail'; // Give the email a subject 
$headers = "From: wwww//.chromeextention.space \r\n"; // Set from headers
$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
$message = '
 
Thanks your order \r\n
 
------------------------\r\n
Name: '.$name.'\r\n
Email: '.$email.'\r\n
------------------------\r\n
 
Please click this link to activate your account:\r\n
http://www.chromeextention.space/confirm.php?'.$token.'
 
'; // Our message above including the link

mail($to, $subject, $message, $headers); // Send our email


$servername = 'mg363964.mysql.tools';
$username = 'mg363964_db';
$password = 'wst8AKQt';
$dbname = 'mg363964_db';

$conn = new mysqli($servername, $username, $password, $dbname); // Create connection
if ($conn->connect_errno) {     // Check connection
    die("Connection failed: " . $conn->connect_error . "\nservername: " . $servername . "\ndbname:" . $dbname . "\nusername:" . $username . "\npassword: " . $password);
} 

$users = "CREATE TABLE IF NOT EXISTS `users` (
    `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `username` varchar(255) NOT NULL,
    `email` varchar(255) NOT NULL,
    `token` varchar(255) NOT NULL
    )";

if ($conn->query($users) === TRUE) {
    $username_text = mysqli_real_escape_string($conn, $name);
    $email_text = mysqli_real_escape_string($conn, $email);
    $token_text = mysqli_real_escape_string($conn, $token);
}

$sql = "INSERT INTO users (date, username, email, token)
VALUES (CURDATE(),  '$username_text', '$email_text', '$token_text') ON DUPLICATE KEY UPDATE    
date=CURDATE(),  username='$username_text', email='$email_text', token='$token_text'";

if ($conn->query($sql) === TRUE) {
    echo "Page saved!";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();

?>

