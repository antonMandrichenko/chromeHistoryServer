MIT License

Copyright (c) 2019 antonMandrichenko

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
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
$headers = "From: wwww//.chromeextention.space  r\n"; // Set from headers
$headers .= 'Content-type: text/html; charset=utf-8' . " r\n";
$message = '
 
Thanks your order <br/>
 
------------------------ <br/>
Name: ' . $name . ' <br/>
Email: ' . $email . ' <br/>
------------------------ <br/>
 
Please click this link to activate your account: <br/>
http://www.chromeextention.space/confirm.php?' . $token . '
 
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
    $userId = "SELECT id FROM users WHERE token = '$token'";
    $result = $conn->query($userId);

    if ($result->num_rows == 0) {
        $username_text = mysqli_real_escape_string($conn, $name);
        $email_text = mysqli_real_escape_string($conn, $email);
        $token_text = mysqli_real_escape_string($conn, $token);

        $sql = "INSERT INTO users (date, username, email, token)
    VALUES (now(),  '$username_text', '$email_text', '$token_text') ON DUPLICATE KEY UPDATE    
    date=now(),  username='$username_text', email='$email_text', token='$token_text'";

        if ($conn->query($sql) === TRUE) {
            echo "Page saved!";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

$conn->close();
