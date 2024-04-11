<?php
session_start();
if ($_SESSION['zalogowano'] !== "tak") {
    header("Location: ./logowanie.php");
    exit;
}
?>
<?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(isset($_POST['id_prod']) && $_POST['id_prod'] !== ''){
        $conn = mysqli_connect('localhost', 'root', '', 'sklep');
        $sq = "SELECT * FROM `koszyki`,produkty WHERE id_uzytkownicy='$_SESSION[uzytkownik]' AND id_produktu=produkty.id AND id_produktu=$_POST[id_prod];";
        $res = mysqli_query($conn,$sq);
        if(mysqli_num_rows($res)>0){
            $row = mysqli_fetch_assoc($res);
            if($row['ilosc']>$row['ilosc_zamow']){
                $sql = "UPDATE koszyki SET ilosc_zamow=ilosc_zamow+1 WHERE id_produktu=$_POST[id_prod] AND id_uzytkownicy='$_SESSION[uzytkownik]';";
            $result = mysqli_query($conn, $sql);
        $rowsAff=mysqli_affected_rows($conn);
        if($rowsAff>0){
            unset($rowsAff);
        }
        else{
            $sql2= "INSERT INTO `koszyki`(`id_produktu`, `id_uzytkownicy`, `ilosc_zamow`) VALUES ('$_POST[id_prod]','$_SESSION[uzytkownik]',1)";
            mysqli_query($conn, $sql2);
        }
        unset($_POST['id_prod']);
        header("Location: ".$_SERVER['PHP_SELF']);
        exit();
            }
            else{
                $_SESSION['displayMax']=true;
            }
        }
        mysqli_close($conn);
    }
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
    <div id="menu">
        <div id="buttonContainer">
        
            <?php include 'menu.php' ?>
        </div>
    </div>
    <div>
        <div id="header">Produkty</div>
        <div id="productsContainer">
            <?php
            $conn = mysqli_connect('localhost', 'root', '', 'sklep');
            $sql = "SELECT * FROM produkty";
            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    if ($row['ilosc'] > 10) $quantityColor = "green";
                    else if ($row['ilosc'] > 0) $quantityColor = "yellow";
                    else $quantityColor = "red";
                    echo "<div class='product'>";
                    echo "<h2 class='product-title'>$row[nazwa]</h2>";
                    echo "<div class='zdjCont'>";
                    echo "<img class='prodZdj' src='./$row[FilePath]'/>";
                    echo "</div>";
                    echo "<p class='product-price'>$row[cena]zł</p>";
                    echo "<p class='product-quantity' style='Color:$quantityColor'>";
                    echo $row['ilosc'];
                    echo "</p>";
                    echo "<form action='index.php' method='post'>";
                    echo "<input type='hidden' name='id_prod' value='$row[id]'>";
                    echo "<input type='submit' value='Dodaj do Koszyka' class='przyciski'>";
                    echo "</form>";
                    echo "</div>";
                }
            }
            ?>
        </div>
    </div>
</body>
</html>