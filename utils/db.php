<?php 
    require $_SERVER['DOCUMENT_ROOT']."../assets/libs/redbean/rb.php";

    $host = 'localhost';
    $dbname = 'howard';
    $username = 'root';
    $password = 'root';

    R::setup('mysql:host='.$host.';dbname='.$dbname,$username,$password);

    session_start();
?>