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
    <div id="menu">
        <div id="buttonContainer">
        <?php  include 'menu.php'?>
        </div>
        <div  id="userPNG">

        <div id="dropdownContent">
            <form action="settings.php">
                <input type="submit" value="Ustawienia">
            </form>
            <form action="wyloguj.php">
                <input type="submit" value="Wyloguj">
            </form>
        </div>
        </div>
    </div>
    <div>
        <div id="header">Produkty</div>
    <div id="productsContainer">
        <?php
        $conn = mysqli_connect('localhost','root','','sklep');
        $sql = "SELECT * FROM produkty";
        $result = mysqli_query($conn,$sql);
        if(mysqli_num_rows($result)>0){
            while($row=mysqli_fetch_assoc($result)){
                echo "<div class='product'>";
                echo "<h2 class='product-title'>$row[nazwa]</h2>";
                echo "<div class='zdjCont'>";
                echo "<img class='prodZdj' src='./$row[FilePath]'/>";
                echo "</div>";
                echo "<p class='product-price'>$row[cena]zł</p>";
                if($row['ilosc']>10) {
                echo "<p class='product-quantity' style='Color:green;'>Na stanie ($row[ilosc])</p>";
                }
                else if ($row['ilosc']>0) echo "<p class='product-quantity' style='Color:rgb(153, 107, 9);'>Ograniczona ($row[ilosc])</p>";
                else echo "<p class='product-quantity' style='Color:red;'>Brak w magazynie</p>";
                echo "</p>";
                echo "</div>";
            }
        }
        ?>
    </div>
    </div>
</body>
</html>