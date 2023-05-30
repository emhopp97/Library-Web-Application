<?php

$user = 'root';
$pass = ''; 
$dsn='mysql:host=localhost; dbname=library';
try {
    $db = new PDO($dsn, $user, $pass);
    $dbname = $db->query('SELECT database()')->fetchColumn();
} catch (PDOException $e) {
    echo "Error!: " . $e->getMessage() . "<br>";
    die();
}

?>