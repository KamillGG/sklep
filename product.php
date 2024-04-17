<?php
session_start();
include 'config.php';
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $conn = mysqli_connect($host, $user, $pass, $database);
    $sql = "INSERT INTO `koszyki`(`id_produktu`, `id_uzytkownicy`, `ilosc_zamow`) VALUES ('$_POST[id]','$_SESSION[uzytkownik]',1)";
    $result = mysqli_query($conn, $sql);
    mysqli_close($conn);
    header("Location: ./koszyk.php");
    exit;
} else if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: ./index.php");
    exit;
}
$id = $_GET['id'];
$conn = mysqli_connect($host, $user, $pass, $database);
$sql = "SELECT * FROM produkty WHERE id='$id'";
$result1 = mysqli_query($conn, $sql);
if (mysqli_num_rows($result1) <= 0) {
    mysqli_close($conn);
    header("Location: ./index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="product.css">
    <title>Document</title>
</head>

<body>
    <?php
    include 'menu.php';
    $row = mysqli_fetch_assoc($result1);
    ?>
    <div id="lowerPart">
        <div id="productContainer">
            <h2 id="nazwa"><?php echo $row['nazwa'] ?></h2>
            <img src="
            <?php
            if (file_exists('./' . $row['FilePath'])) {
                echo $row['FilePath'];
            } else echo $defaultPath;
            ?>">
            <h3 id="cena"><?php echo $row['cena'] ?>zł</h3>
            <p id="opis">Opis: <?php echo $row['opis'] ?></p>
            <?php
            if ($row['ilosc'] > 0) {
                echo "<form method='post'>";
                echo "<input type='hidden' name='id' value='<?php echo $id ?>'>";
                echo "<input type='submit' value='Dodaj do koszyka' class='przyciski'>";
                echo "</form>";
            } else echo "<h2 class='unavailable'>Produkt niedostępny</h2>"
            ?>
        </div>
    </div>
</body>

</html>