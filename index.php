<?php
session_start();
if ($_SESSION['zalogowano'] !== "tak") {
    header("Location: ./logowanie.php");
    exit;
}
$_SESSION['displayMax'] = false;
?>
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['id_prod']) && $_POST['id_prod'] !== '') {
        $conn = mysqli_connect('localhost', 'root', '', 'sklep');
        $sq = "SELECT * FROM `koszyki`,produkty WHERE id_uzytkownicy='$_SESSION[uzytkownik]' AND id_produktu=produkty.id AND id_produktu=$_POST[id_prod];";
        $res = mysqli_query($conn, $sq);
        if (mysqli_num_rows($res) > 0) {
            $row = mysqli_fetch_assoc($res);
            if ($row['ilosc'] > $row['ilosc_zamow'] && $row['ilosc_zamow'] < 9) {
                $sql = "UPDATE koszyki SET ilosc_zamow=ilosc_zamow+1 WHERE id_produktu=$_POST[id_prod] AND id_uzytkownicy='$_SESSION[uzytkownik]';";
                $result = mysqli_query($conn, $sql);
                unset($_POST['id_prod']);
                header("Location: " . $_SERVER['PHP_SELF']);
                exit();
            } else {
                $_SESSION['displayMax'] = true;
            }
        } else {
            mysqli_query($conn, queryReturn());
        }
        mysqli_close($conn);
    }
}
function queryReturn()
{
    return "INSERT INTO `koszyki`(`id_produktu`, `id_uzytkownicy`, `ilosc_zamow`) VALUES ('$_POST[id_prod]','$_SESSION[uzytkownik]',1)";
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
    <?php
    if ($_SESSION['displayMax'] == true) {
        echo "<div class='popup active'>";
    } else echo "<div class='popup'>";
    ?>
    <p class="popup-text">Limit dodawania tego produktu</p>
    <a class="popup-link" href="./">Zamknij</a>
    </div>

    <?php include 'menu.php' ?>
    </div>
    </div>
    <div id="lowerPart">
        <div id="productsContainer">
            <?php
            $conn = mysqli_connect('localhost', 'root', '', 'sklep');
            $sql = "SELECT * FROM produkty";
            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {

                    echo "<div class='product'>";
                    echo "<h2 class='product-title'>$row[nazwa]</h2>";
                    echo "<div class='zdjCont'>";
                    echo "<img class='prodZdj' src='./$row[FilePath]'/>";
                    echo "</div>";
                    echo "<p class='product-price'>$row[cena]zł</p>";
                    if ($row['ilosc'] > 10) echo "<p class='product-quantity' style='Color:green'>Produkt Dostępny</p>";
                    else if ($row['ilosc'] > 0) echo "<p class='product-quantity' style='Color:#BEA309'>Ograniczona ilość($row[ilosc])</p>";
                    else echo "<p class='product-quantity' style='Color:#A31013'>Produkt niedostępny</p>";
                    if ($row['ilosc'] > 0) {
                        echo "<form action='index.php' method='post'>";
                        echo "<input type='hidden' name='id_prod' value='$row[id]'>";
                        echo "<input type='submit' value='Dodaj do Koszyka' class='przyciski'>";
                        echo "</form>";
                    }

                    echo "</div>";
                }
            }
            ?>
        </div>
    </div>
</body>

</html>