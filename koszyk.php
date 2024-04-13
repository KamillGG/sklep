<?php
session_start();
?>
<?php
if (isset($_POST['delID']) && $_POST['delID'] !== "") {
    $conn = mysqli_connect('localhost', 'root', '', 'sklep');
    $sql = "DELETE FROM `koszyki` WHERE id_zamowienia='$_POST[delID]' AND id_uzytkownicy='$_SESSION[uzytkownik]'";
    mysqli_query($conn, $sql);
    mysqli_close($conn);
    unset($_POST);
    header("Location: " . $_SERVER['PHP_SELF']);
} else if (isset($_POST['id_mod']) && $_POST['id_mod'] !== "") {
    $conn = mysqli_connect('localhost', 'root', '', 'sklep');
    $sql = "UPDATE `koszyki` SET ilosc_zamow='$_POST[number]' WHERE id_zamowienia='$_POST[id_mod]'";
    mysqli_query($conn, $sql);
    unset($_POST);
    header("Location: " . $_SERVER['PHP_SELF']);
    mysqli_close($conn);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="koszyk.css">
    <link rel="stylesheet" href="select.css">
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
                echo "<form action='koszyk.php' method='post' id='form$row[id_zamowienia]'>";
                echo "<input type='hidden' name='id_mod' value='$row[id_zamowienia]'>";
                echo "<select name='number' onChange='simSub($row[id_zamowienia])'>";
                $i = 1;
                while ($i < 10 && $row['ilosc'] >= $i) {
                    if ($i == $row['ilosc_zamow']) echo "<option value='$i' selected=true>$i</option>";
                    else echo "<option value='$i'>$i</option>";
                    $i++;
                }
                echo "</select>";
                echo "<input type=submit class='hiddenSub' id='bt$row[id_zamowienia]'>";
                echo "</form>";
                echo "</div>";
                echo "<div class='prod'>";
                echo "<h3>$row[cena]zł</h3>";
                echo "</div>";
                echo "<form class='prod' method='post'>";
                echo "<input type='hidden' value='$row[id_zamowienia]' name='delID'>";
                echo "<input type='submit' value='Usun' class='przyciski'>";
                echo "</form>";
                echo "</div>";
                echo "</div>";
                echo "<hr>";
            }
        } else {
            echo "<h1>Pusto...</h1>";
        }
        ?>
        <a href="index.php" class="przyciski powrot">Powrót</a>
    </div>
    <script>
        function simSub(id) {
            document.getElementById('bt' + id).click()
        }
    </script>
</body>

</html>