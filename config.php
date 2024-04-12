<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$database = 'sklep';
function returnSelect($query, $conn)
{
    $result = mysqli_query($conn, $query);
    return $result;
}
