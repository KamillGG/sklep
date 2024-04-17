<?php
include 'config.php';
session_start() ?>
<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!empty($_POST['delete']) && $_SESSION['uprawnienia'] !== 'pracownik') {
        $conn = mysqli_connect($host, $user, $pass, $database);
        $sql = mysqli_prepare($conn, "DELETE FROM `produkty` WHERE id=?");
        mysqli_stmt_bind_param($sql, "s", $_POST['delete']);
        mysqli_stmt_execute($sql);
    }
}
?>
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
        <div id="listContainer">
            <?php
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
                    echo "<form method='post'>";
                    echo "<input type='hidden' value='$row[id]' name='delete'>";
                    if ($_SESSION['uprawnienia'] != 'pracownik') {
                        echo "<input type='submit' value='Usun' class='przyciski'>";
                    } else echo "<input type='submit' value='Usun' class='przyciski notAllowed' disabled>";
                    echo "</form>";
                    echo "</div>";
                    echo "</div>";
                }
            }
            mysqli_close($conn);
            ?>
        </div>
        <div id="dodaj">
            <a href="./dodawanie.php" class="przyciski">Dodaj</a>
        </div>
    </div>
</body>

</html>