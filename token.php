<script>
    var token = localStorage.getItem('token');
    $.POST('confirm.php', {'token':token}, (data) => {
        console.log('Token get from user', data);
    }
</script>