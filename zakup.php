<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <?php
    session_start();

    if ($_SERVER['REQUEST_METHOD'] === "POST") {
        include 'config.php';
        $conn = mysqli_connect($host, $user, $pass, $database);

        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        $login = $_SESSION['uzytkownik'];
        $sql = mysqli_prepare($conn, "SELECT * FROM koszyki,produkty WHERE id_uzytkownicy=? AND id_produktu=produkty.id");
        mysqli_stmt_bind_param($sql, "s", $login);
        mysqli_stmt_execute($sql);
        $result = mysqli_stmt_get_result($sql);

        if ($result) {
            if (mysqli_num_rows($result) > 0) {
                $sum = 0;
                $dateNow = date('Y-m-d');

                while ($row = mysqli_fetch_assoc($result)) {
                    $sum += $row['cena'] * $row['ilosc_zamow'];
                }

                $sql2 = mysqli_prepare($conn, "INSERT INTO `statystyki`(`data`, `cena_sum`) VALUES (?,?)");
                mysqli_stmt_bind_param($sql2, "sd", $dateNow, $sum);
                $success2 = mysqli_stmt_execute($sql2);

                if ($success2) {
                    $inserted_id = mysqli_insert_id($conn);

                    $sql3 = mysqli_prepare($conn, "SELECT * FROM statystyki WHERE id=?");
                    mysqli_stmt_bind_param($sql3, "i", $inserted_id);
                    mysqli_stmt_execute($sql3);
                    $result3 = mysqli_stmt_get_result($sql3);
                    $row3 = mysqli_fetch_assoc($result3);

                    $sql4 = mysqli_prepare($conn, "INSERT INTO `statystyki_zakup`(`id_zakupu`, `id_produktu`,`ilosc`) VALUES (?,?,?)");
                    mysqli_stmt_bind_param($sql4, "iii", $inserted_id, $id_produktu, $ilosc);

                    mysqli_data_seek($result, 0);

                    while ($row = mysqli_fetch_assoc($result)) {
                        $id_produktu = $row['id_produktu'];
                        $ilosc = $row['ilosc_zamow'];
                        mysqli_stmt_execute($sql4);
                    }

                    $sql5 = mysqli_prepare($conn, "DELETE FROM koszyki WHERE id_uzytkownicy=?");
                    mysqli_stmt_bind_param($sql5, "s", $login);
                    $success5 = mysqli_stmt_execute($sql5);
                    mysqli_data_seek($result, 0);
                    while ($row = mysqli_fetch_assoc($result)) {
                        $sql6 = "UPDATE `produkty` SET `ilosc`=ilosc-$row[ilosc_zamow] WHERE id='$row[id]'";
                        mysqli_query($conn, $sql6);
                    }
                    if ($success5) {
                        header('Location: ./index.php');
                    } else {
                        echo "Error deleting records from koszyki table: " . mysqli_error($conn);
                    }
                    mysqli_stmt_close($sql5);
                } else {
                    echo "Error inserting record into statystyki table: " . mysqli_error($conn);
                }

                mysqli_stmt_close($sql2);
                mysqli_stmt_close($sql3);
                mysqli_stmt_close($sql4);
            }
        } else {
            echo "Error executing query: " . mysqli_error($conn);
        }

        mysqli_stmt_close($sql);
        mysqli_close($conn);
    } else {
        header('Location: ./index.php');
    }
    ?>
</body>

</html>