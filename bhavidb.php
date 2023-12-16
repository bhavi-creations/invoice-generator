<?php
    $server='localhost';
    $username='root';
    $pass='';
    $database='bhavi_invoice_db';

    $conn = mysqli_connect($server,$username,$pass,$database);
if(!$conn){
    echo "connection failed";
}

?>