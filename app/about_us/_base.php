<?php

$_db = new PDO('mysql:dbname=ass', 'root', '', [
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
]);

?>