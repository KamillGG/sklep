<?php
session_start();
if ($_SESSION['uprawnienia'] !== "admin") {
    header("Location: ./index.php");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="przyciski.css">
    <link rel="stylesheet" href="admin.css">
    <title>Document</title>
</head>

<body>
    <?php
    include 'menu.php'
    ?>
    <div id="usersContainer">
        <?php
        include 'config.php';
        $conn = mysqli_connect($host, $user, $pass, $database);
        $sql = "SELECT * FROM uzytkownicy";
        $result = returnSelect($sql, $conn);
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo $row['login'];
            }
        }
        ?>
    </div>
</body>

</html>