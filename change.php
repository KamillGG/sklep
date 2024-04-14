<?php
session_start();
if ($_SESSION['uzytkownik'] == "user" || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ./index.php");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="add.css">
    <title>Document</title>
</head>

<body>
    <?php
    if (isset($_FILES["image"]) && $_FILES["image"]["error"] == UPLOAD_ERR_OK) {
        $name = $_POST['nazwa'];
        $opis = $_POST['opis'];
        $cena = $_POST['cena'];
        $ilosc = $_POST['ilosc'];
        $target_dir = "uploads/";
        $unique_filename = uniqid() . '_' . basename($_FILES['image']['name']);
        $target_file = $target_dir . $unique_filename;
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            echo "File is not an image.";
            $uploadOk = 0;
        }
        if ($_FILES["image"]["size"] > 500000) {
            echo "Sorry, your file is too large.";
            $uploadOk = 0;
        }
        if (
            $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif"
        ) {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }
        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
        } else {
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                $conn = mysqli_connect('localhost', 'root', '', 'sklep');
                $checkSql = "SELECT FilePath FROM produkty WHERE id='$_POST[id]'";
                $resultCheck = mysqli_query($conn, $checkSql);
                $rowCheck = mysqli_fetch_assoc($resultCheck);
                if (file_exists($rowCheck['FilePath'])) {
                    unlink($rowCheck['FilePath']);
                }
                $sql = "UPDATE `produkty` SET `nazwa`='$name',`cena`='$cena',`ilosc`='$ilosc',`opis`='$opis',`FilePath`='$target_file' WHERE id='$_POST[id]'";
                if (mysqli_query($conn, $sql)) {
                    "Dodano";
                }
                mysqli_close($conn);
                header("Location: ./index.php");
            }
        }
    } else if (isset($_POST['nazwa'])) {
        $name = $_POST['nazwa'];
        $opis = $_POST['opis'];
        $cena = $_POST['cena'];
        $ilosc = $_POST['ilosc'];
        $sql = "UPDATE `produkty` SET `nazwa`='$name',`cena`='$cena',`ilosc`='$ilosc',`opis`='$opis' WHERE id='$_POST[id]'";
        $conn = mysqli_connect('localhost', 'root', '', 'sklep');
        if (mysqli_query($conn, $sql)) {
            "Dodano";
        }
        mysqli_close($conn);
        header("Location: ./index.php");
    }
    ?>
    <?php
    include 'config.php';
    $conn = mysqli_connect($host, $user, $pass, $database);
    $sql = "SELECT * FROM produkty WHERE id='$_POST[id]'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    ?>
    <div class="add-container">
        <form method="post" enctype="multipart/form-data">
            <input type="hidden" value="<?php echo $row['id'] ?>" name='id'>
            <input type="text" placeholder="Nazwa Produktu" name="nazwa" value="<?php echo $row['nazwa'] ?>">
            <input type="text" placeholder="Opis Produktu" name="opis" id="opis" value="<?php echo $row['opis'] ?>">
            <input type="number" placeholder="Cena Produktu" step="0.01" name="cena" min="0.01" value="<?php echo $row['cena'] ?>">
            <input type="number" placeholder="Ilosc Produktu" min="0" name='ilosc' value="<?php echo $row['ilosc'] ?>">
            <label for="image" class="file-label">Zmien zdjęcie (opcjonalne)</label>
            <input type="file" placeholder="zdjecie" name="image" id="image" onchange="showFileName(this)">
            <input type="submit" value="Zatwierdź" class="przyciski">
        </form>
        <form action="index.php">
            <input type="submit" value="Powrót" class="przyciski">
        </form>
    </div>
    <script>
        function showFileName(input) {
            var label = document.getElementsByClassName("file-label")[0];
            if (input.files && input.files[0]) {
                label.textContent = input.files[0].name;
            } else {
                label.textContent = "Wybierz zdjecie Produktu";
            }
        }
    </script>
</body>

</html>