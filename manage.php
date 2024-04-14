<?php
session_start() ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="manage.css">
    <title>Document</title>
</head>

<body>
    <?php
    include 'menu.php';
    ?>
    <div id="list">
        <?php
        include 'config.php';
        $conn = mysqli_connect($host, $user, $pass, $database);
        $sql = "SELECT * FROM produkty";
        $result = returnSelect($sql, $conn);
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<div class='product'>";
                echo "<div class='left'>";
                echo "<img src='$row[FilePath]' width='50px' height='50px'>";
                echo "<h2>$row[nazwa]</h2>";
                echo "</div>";
                echo "<div class='right'>";
                echo "<p>";
                echo "$row[opis]";
                echo "</p>";
                echo "<p>$row[ilosc]</p>";
                echo "<form action='change.php' method='post'>";
                echo "<input type='hidden' value='$row[id]' name='id'>";
                echo "<input type='submit' value='Zmien' class='przyciski'>";
                echo "</form>";
                echo "</div>";
                echo "</div>";
            }
        }
        mysqli_close($conn);
        ?>
    </div>
</body>

</html>