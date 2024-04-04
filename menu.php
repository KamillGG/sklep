<ul>
    <li><a href="koszyk.php">Koszyk</a></li>
    <?php 
    error_reporting(E_ERROR | E_PARSE);
    if($_SESSION['admin']==true){
        echo "<li><a href='admin.php'>Admin Panel</a></li>";
        echo "<li><a href='dodawanie.php'>Dodaj Produkt</a></li>";
    }
    if($_SESSION['pracownik']==true){
        echo "<li><a href='dodawanie.php'>Dodaj Produkt</a></li>";
    }
    ?>
</ul>