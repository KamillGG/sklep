<?php
session_start();
?>
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delID']) && $_POST['delID'] !== "") {
        $conn = mysqli_connect('localhost', 'root', '', 'sklep');
        $sql = "DELETE FROM `koszyki` WHERE id_produktu='$_POST[delID]' AND id_uzytkownicy='$_SESSION[uzytkownik]'";
        mysqli_query($conn, $sql);
        unset($_POST['delID']);
        header("Location: " . $_SERVER['PHP_SELF']);
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="koszyk.css">
    <title>Document</title>
</head>

<body>
    <div class="products">
        <?php
        $conn = mysqli_connect('localhost', 'root', '', 'sklep');
        $sql = "SELECT * FROM `koszyki`,produkty WHERE produkty.id=koszyki.id_produktu AND id_uzytkownicy='$_SESSION[uzytkownik]';";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<div class='product'>";
                echo "<div class='photoName'>";
                echo "<img src='$row[FilePath]' width='50px' height='50px' >";
                echo "<h3>$row[nazwa]</h3>";
                echo "</div>";
                echo "<div class='actions'>";
                echo "<div class='number'>";
                echo "<select>";
                $i = 1;
                while ($i < 10 && $row['ilosc'] >= $i) {
                    if ($i == $row['ilosc_zamow']) echo "<option value='$i' selected=true>$i</option>";
                    else echo "<option value='$i'>$i</option>";
                    $i++;
                }
                echo "</select>";
                echo "</div>";
                echo "<div class='prod'>";
                echo "<h3>$row[cena]zł</h3>";
                echo "</div>";
                echo "<form class='prod' method='post'>";
                echo "<input type='hidden' value='$row[id_produktu]' name='delID'>";
                echo "<input type='submit' value='Usun' class='deleteButton'>";
                echo "</form>";
                echo "</div>";
                echo "</div>";
                echo "<hr>";
            }
        } else {
            echo "<h1>Pusto...</h1>";
        }
        ?>
        <form action='index.php' method='post'><input type='submit' value='powrot' class='control'></form>
    </div>
</body>

</html>