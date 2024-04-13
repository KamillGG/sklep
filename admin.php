<?php
session_start();
include 'config.php';
if ($_SESSION['uprawnienia'] !== "admin" && $_SESSION['uprawnienia'] !== "superAdmin") {
    header("Location: ./index.php");
}
?>
<?php
include 'menu.php'
?>
<div id="panelContainer">
    <?php
    if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['newRole'])) {
        if (($_SESSION['uprawnienia'] == "admin" && $_POST['newRole'] !== "admin") || $_SESSION['uprawnienia'] == "superAdmin") {
            $conn = mysqli_connect($host, $user, $pass, $database);
            $sql = "SELECT login,upr FROM uzytkownicy WHERE login='$_POST[login]'";
            $result = returnSelect($sql, $conn);
            $row = mysqli_fetch_assoc($result);
            if (($row['upr'] !== "admin" && $row['upr'] !== "superAdmin") || $_SESSION['uprawnienia'] !== "admin") {
                $sql2 = "UPDATE `uzytkownicy` SET `upr`='$_POST[newRole]' WHERE login='$_POST[login]'";
                echo $sql2;
                mysqli_query($conn, $sql2);
                unset($_POST);
                header("Location: " . $_SERVER['PHP_SELF']);
            }
        }
    }
    ?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="select.css">
        <link rel="stylesheet" href="admin.css">
        <title>Document</title>
    </head>

    <body>
        <div id="usersContainer">
            <?php
            $conn = mysqli_connect($host, $user, $pass, $database);
            $sql = "SELECT * FROM uzytkownicy WHERE login!='$_SESSION[uzytkownik]'";
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
                            echo "<form method='post'>";
                            echo "<select class='uprSelect' name='newRole' onChange=changed('$row[login]')>";
                            foreach ($options as $option) {
                                if ($row['upr'] !== $option) {
                                    echo "<option>$option</option>";
                                } else {
                                    echo "<option selected='selected'>$option</option>";
                                }
                            }
                            echo "</select>";
                            echo "<input type='hidden' value='$row[login]' name='login'>";
                            echo "<input type='submit' style='display:none' id='sub$row[login]'>";
                            echo "</form>";
                        }
                    }
                    echo "</div>";
                    echo "<hr class='line'>";
                }
            }
            ?>
        </div>
</div>
<script>
    function changed(id) {
        document.getElementById(`sub` + id).click()
    }
</script>
</body>

</html>