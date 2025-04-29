<?php

function connect_to_db() {
    $servername = 'localhost';
    $username = 'root';
    $password = '1234';
    $db_name = 'taskapp';

    $conn = mysqli_connect($servername, $username, $password, $db_name);

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    
    return $conn;
}

?>