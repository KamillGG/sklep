<?php
session_start();
if ($_SESSION['uprawnienia'] !== "admin" && $_SESSION['uprawnienia'] !== "superAdmin") {
    header("Location: ./index.php");
}
?>
<?php

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="przyciski.css">
    <link rel="stylesheet" href="select.css">
    <link rel="stylesheet" href="admin.css">
    <title>Document</title>
</head>

<body>
    <?php
    include 'menu.php'
    ?>
    <div id="panelContainer">
        <div id="usersContainer">
            <?php
            include 'config.php';
            $conn = mysqli_connect($host, $user, $pass, $database);
            $sql = "SELECT * FROM uzytkownicy";
            $result = returnSelect($sql, $conn);
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<div class='uzytkownik'>";
                    echo "<h2>";
                    echo $row['login'];
                    echo "</h2>";
                    if ($row['upr'] === "superAdmin") {
                        echo "<select class='uprSelect disabled' disabled>";
                        echo "<option>superAdmin</option>";
                        echo "</select>";
                    } else {
                        if ($row['upr'] == 'admin' && $_SESSION['uprawnienia'] == "admin") {
                            echo "<select class='uprSelect disabled' disabled>";
                            echo "<option>admin</option>";
                            echo "</select>";
                        } else {
                            if ($row['upr'] !== 'admin' && $_SESSION['uprawnienia'] === "admin") {
                                $options = ['user', 'pracownik'];
                            } else {
                                $options = ['admin', 'user', 'pracownik'];
                            }
                            echo "<select class='uprSelect'>";
                            foreach ($options as $option) {
                                if ($row['upr'] !== $option) {
                                    echo "<option>$option</option>";
                                } else {
                                    echo "<option selected='selected'>$option</option>";
                                }
                            }
                            echo "</select>";
                        }
                    }
                    echo "</div>";
                    echo "<hr class='line'>";
                }
            }
            ?>
        </div>
    </div>
</body>

</html>