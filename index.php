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
        <?php  include 'menu.php'?>
        <div  id="userPNG">
        <img src="User.png" width="50px" onclick="toggleDropdown()">
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
        <?php
        $conn = mysqli_connect('localhost','root','','sklep');
        $sql = "SELECT * FROM produkty";
        $result = mysqli_query($conn,$sql);
        if(mysqli_num_rows($result)>0){
            while($row=mysqli_fetch_assoc($result)){
                echo "<div>";
                echo "<h1>$row[nazwa]</h1>";
                echo "<img src='./$row[FilePath]'/>";
                echo "<h2>$row[cena]zł</h2>";
                echo "<h3>Na stanie: $row[ilosc]</h3>";
                echo "</div>";
            }
        }
        ?>
    </div>
    <script>
  function toggleDropdown() {
    var dropdownContent = document.getElementById("dropdownContent");
    if (dropdownContent.style.display === "block") {
      dropdownContent.style.display = "none";
    } else {
      dropdownContent.style.display = "block";
    }
  }
    </script>
</body>
</html>