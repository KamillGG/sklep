<form action="koszyk.php" method="post">
    <input type="submit" value="Koszyk" class='przyciski'>
</form>
    <?php 
    error_reporting(E_ERROR | E_PARSE);
    if($_SESSION['uprawnienia']=="admin"){
        echo "<form action='dodawanie.php' method='post'>";
        echo "<input type='submit' value='Dodaj Produkt' class='przyciski'>";
        echo "</form>";
        echo "<form action='admin.php' method='post'>";
        echo "<input type='submit' value='Panel Administratora' class='przyciski'>";
        echo "</form>";
    }
    if($_SESSION['uprawnienia']=="pracownik"){
        echo "<li><a href='dodawanie.php'>Dodaj Produkt</a></li>";
    }
    ?>
<form method="post">
    <input type="hidden" name="menuToggle" value="true">
    <button type="submit"><img src='User.png' width='50px'></button>
</form>
<?php 
    if(!empty($_POST['menuToggle'])){
    }
?>