<?php
session_start();
?>
<?php
if (isset($_SESSION['zalogowano'])) {
    if ($_SESSION['zalogowano'] !== "nie") {
        header("Location: ./index.php");
        exit;
    }
}
if (isset($_POST['redirect'])) {
    // Redirect to another page
    unset($_POST);
    header("Location: ./rejestracja.php");
    exit; // Ensure that code below is not executed after redirection
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="logowanie.css">
    <title>Document</title>
</head>

<body>
    <div class="login-container">
        <div class="login-header">
            <h1>Logowanie</h1>
        </div>
        <form method="post">
            <input type="text" placeholder="login" name="login">
            <input type="password" name="password" placeholder="password">
            <input type="submit" value="Zaloguj" class="przyciski">
        </form>
        <form method="post" class="return">
            <p>Nie masz konta?</p>
            <input type="submit" name="redirect" value="Zarejestruj" class="przyciski">
        </form>
    </div>
    <?php
    if (isset($_POST['login']) && isset($_POST['password'])) {
        $conn = mysqli_connect('localhost', 'root', '', 'sklep');
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        $sql = mysqli_prepare($conn, "SELECT * FROM uzytkownicy WHERE login=? AND password=?;");
        mysqli_stmt_bind_param($sql, "ss", $login, $password);
        $login = $_POST['login'];
        $password = md5($_POST['password']);
        mysqli_stmt_execute($sql);
        $result = mysqli_stmt_get_result($sql);
        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $_SESSION['uzytkownik'] = $login;
            $_SESSION['zalogowano'] = "tak";
            if ($row['upr'] == "admin") {
                $_SESSION['uprawnienia'] = "admin";
            } else if ($row['upr'] == "superAdmin") {
                $_SESSION['uprawnienia'] = "superAdmin";
            } else if ($row['upr'] == "pracownik") {
                $_SESSION['uprawnienia'] = "pracownik";
            } else $_SESSION['uprawnienia'] = "user";
            header("Location: ./index.php");
        }
        mysqli_close($conn);
    }
    ?>
</body>

</html>