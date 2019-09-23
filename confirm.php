<script>
    var token = localStorage.getItem('cashspotusa');
    //еще не работает
    token = {"cashspotusa": token};
    fetch("confirm.php", {
        method: 'POST', // *GET, POST, PUT, DELETE, etc.
        mode: 'cors', // no-cors, cors, *same-origin
        cache: 'no-cache', // *default, no-cache, reload, force-cache, only-if-cached
        credentials: 'same-origin', // include, *same-origin, omit
        headers: {
            'Content-Type': 'application/json',
        }
        body: JSON.stringify(token), // тип данных в body должен соответвовать значению заголовка "Content-Type"
    })
    .then(response => console.log(response.json()));
</script>

<?php 

$token = $_POST['token'];

echo $token

?>