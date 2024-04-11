<link rel="stylesheet" href="dropdown.css">
<form action="koszyk.php" method="post" id="formKosz">
    <?php 
    $conn = mysqli_connect('localhost', 'root', '', 'sklep');
    $sql = "SELECT COUNT(id) AS ilosc FROM koszyki";
    $result = mysqli_query($conn, $sql);
    if(mysqli_num_rows($result)>0){
        $row = mysqli_fetch_assoc($result);
        echo "<span class='badge'>$row[ilosc]</span>";
    }
    ?>
    <input type="submit" value="Koszyk" class='przyciski'>
</form>
<?php
if ($_SESSION['uprawnienia'] == "admin") {
    echo "<form action='dodawanie.php' method='post'>";
    echo "<input type='submit' value='Dodaj Produkt' class='przyciski'>";
    echo "</form>";
    echo "<form action='admin.php' method='post'>";
    echo "<input type='submit' value='Panel Administratora' class='przyciski'>";
    echo "</form>";
}
if ($_SESSION['uprawnienia'] == "pracownik") {
    echo "<form action='dodawanie.php' method='post'>";
    echo "<input type='submit' value='Dodaj Produkt' class='przyciski'>";
    echo "</form>";
}
?>
<div class="dropdown">
    <input type="submit" value="Konto" class='przyciski'>
    <div class="dropdown-content">
        <a href="./settings.php">Ustawienia</a>
        <a href="./wyloguj.php">Wyloguj</a>
    </div>
</div>