<?php
    $server='localhost';
    // Condition to check if the script is running locally or on a server
    if ($_SERVER['HTTP_HOST'] == 'localhost' || $_SERVER['HTTP_HOST'] == '127.0.0.1') {
        // Local environment details
        $username = 'root';
        $pass = '';
        $database = 'bhavi_invoice_db';
    } else {
        // Server environment details
        $username = 'cnpthbbs_invoice_user';
        $pass = '%tNc6peV4-}w';
        $database = 'cnpthbbs_invoice';
    }

    $conn = mysqli_connect($server,$username,$pass,$database);
    if(!$conn){
        echo "connection failed";
    }

?>