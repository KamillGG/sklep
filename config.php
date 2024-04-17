<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$database = 'sklep';
$defaultPath = './uploads/default.png';
function returnSelect($query, $conn)
{
    $result = mysqli_query($conn, $query);
    return $result;
}
