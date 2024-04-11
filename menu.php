<link rel="stylesheet" href="dropdown.css">
<div id="formKosz">
    <?php
    $conn = mysqli_connect('localhost', 'root', '', 'sklep');
    $sql = "SELECT COUNT(id) AS ilosc FROM koszyki WHERE id_uzytkownicy='$_SESSION[uzytkownik]'";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        echo "<span class='badge'>$row[ilosc]</span>";
    }
    ?>
    <a class='przyciski' href='./koszyk.php'>Koszyk</a>
</div>
<?php
if ($_SESSION['uprawnienia'] == "admin") {
    echo "<a class='przyciski' href='./dodawanie.php'>Dodaj Produkt</a>";
    echo "<a class='przyciski' href='./admin.php'>Panel administratora</a>";
}
if ($_SESSION['uprawnienia'] == "pracownik") {
    echo "<a class='przyciski' href='./dodawanie.php'>Dodaj Produkt></a>";
}
?>
<div class="dropdown">
    <a class='przyciski'>Konto</a>
    <div class="dropdown-content">
        <a href="./settings.php">Ustawienia</a>
        <a href="./wyloguj.php">Wyloguj</a>
    </div>
</div>