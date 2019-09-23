<?php
$fio = $_POST['fio'];
$email = $_POST['email'];
$fio = htmlspecialchars($fio);
$email = htmlspecialchars($email);
$fio = urldecode($fio);
$email = urldecode($email);
$fio = trim($fio);
$email = trim($email);

$subject = "Confirm your e-mail";
$message = "<html>
<body>
    <h1>Confirm your e-mail</h1>
    <p>Click for confirm <a href='http://www.chromeextention.space/confirm.php'>here</a> </p>
</body>
</html>" ;
$message = wordwrap($message, 70, "\r\n");
$headers = "From: www//.chromeextention.space \r\n";
$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
$token = md5($email);

if (mail($email, $subject , $message, $headers)) {
    echo "<h2>You have received a confirmation email to your email</h2>";
    echo $token;
} else {
    echo "при отправке сообщения возникли ошибки";
}
?>

<script>
    localStorage.setItem('cashspotusa', '<?php echo $token;?>');  
</script>
