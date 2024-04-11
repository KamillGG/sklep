<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="koszyk.css">
    <title>Document</title>
</head>
<body>
    <?php 
        $conn = mysqli_connect('localhost', 'root', '', 'sklep');
        $sql = "SELECT * FROM `koszyki`,produkty WHERE produkty.id=koszyki.id_produktu;";
        $result = mysqli_query($conn, $sql);
        echo "<div class='products'>";
        if(mysqli_num_rows($result)>0){
            while($row=mysqli_fetch_assoc($result)){
                echo "<div class='product'>";
                echo "<img src='$row[FilePath]' width='50px' height='50px'>";
                echo "<h3>$row[nazwa]</h3>";
                echo "<div>";
                echo "<select>";
                $i=1;
                while($i<10 && $row['ilosc']>=$i){
                    if($i==$row['ilosc_zamow']) echo "<option value='$i' selected=true>$i</option>";
                    else if($i==9) echo "<option value='$i'>$i++</option>";
                    else echo "<option value='$i'>$i</option>";
                    $i++;
                }
                echo "</select>";
                echo "</div>";
                echo "<div>";
                echo "<h3>$row[cena]zł</h3>";
                echo "</div>";
                echo "<form>";
                echo "<input type='hidden' value='$row[id_produktu]'>";
                echo "<input type='submit' value='Usun' class='deleteButton'>";
                echo "</form>";
                echo "</div>";
                echo "<hr>";
            }
        }
        echo "<form action='index.php' method='post'><input type='submit' value='powrot' class='control'></form>";
        echo "</div>";
    ?>
</body>
</html>