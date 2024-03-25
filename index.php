<?php 
session_start();
if($_SESSION['zalogowano']!=="tak"){
    header("Location: ./logowanie.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="style.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <div>
        <?php  include 'menu.php'?>
    </div>
    <div>Strona główna</div>
</body>
</html>