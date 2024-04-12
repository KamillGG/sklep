<?php
session_start();
if (isset($_SESSION['zalogowano'])) {
    if ($_SESSION['zalogowano'] !== "nie") {
        header("Location: ./index.php");
        exit;
    }
}
if (isset($_POST['redirectlog'])) {
    // Redirect to another page
    unset($_POST);
    header("Location: ./logowanie.php");
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
            <h1>Rejestracja</h1>
        </div>
        <form method="post">
            <input type="text" placeholder="login" name="login">
            <input type="password" name="password" placeholder="password">
            <input type="password" name="passwordrep" placeholder="repeat password">
            <input type="submit" value="Zarejestruj" class="przyciski">
        </form>
        <form method="post" class="return">
            <p>Masz już konto?</p>
            <input type="submit" name="redirectlog" value="Zaloguj" class="przyciski">
        </form>
        <?php
        if (isset($_POST['login']) && isset($_POST['password'])) {
            if ($_POST['login'] !== "" && $_POST['password'] !== "" && $_POST['passwordrep'] !== "") {
                if ($_POST['password'] === $_POST['passwordrep']) {
                    $conn = mysqli_connect('localhost', 'root', '', 'sklep');
                    if (!$conn) {
                        die("Connection failed: " . mysqli_connect_error());
                    }
                    $login = $_POST['login'];
                    $password = md5($_POST['password']);
                    $sql = "INSERT INTO uzytkownicy(login, password, upr) values ('$login', '$password','user');";
                    try {
                        // Execute the query
                        if (mysqli_query($conn, $sql)) {
                            header("Location: ./logowanie.php");
                        } else {
                            throw new Exception('Error executing query: ' . mysqli_error($conn));
                        }
                    } catch (mysqli_sql_exception $e) {
                        // Check if it's a duplicate entry error
                        if ($e->getCode() == 1062) { // MySQL error code for duplicate entry
                            echo 'nazwa zajeta';
                        } else {
                            echo 'An error occurred: ' . $e->getMessage();
                        }
                    }
                    mysqli_close($conn);
                } else {
                    echo "Hasła się nie zgadzają";
                }
            } else {
                echo "wypełnij wszystkie pola";
            }
        }
        ?>
    </div>
</body>

</html>