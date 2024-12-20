<?php
session_start();
if ($_SESSION['uprawnienia'] !== "admin" && $_SESSION['uprawnienia'] !== "pracownik" && $_SESSION['uprawnienia'] !== "superAdmin") {
    header("Location: index.php");
}
?>
<?php
if (!empty($_POST['nazwa']) && !empty($_POST['opis']) && !empty($_POST['cena'] && !empty($_POST['ilosc']))) {
    $name = $_POST['nazwa'];
    $opis = $_POST['opis'];
    $cena = $_POST['cena'];
    $ilosc = $_POST['ilosc'];
    $target_dir = "uploads/";
    if (isset($_FILES["image"]) && $_FILES["image"]["error"] == UPLOAD_ERR_OK) {
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
                $sql = mysqli_prepare($conn, "INSERT INTO produkty(nazwa, opis,cena, ilosc, FilePath) VALUES (?,?,?,?,?)");
                mysqli_stmt_bind_param($sql, "ssdis", $name, $opis, $cena, $ilosc, $target_file);
                mysqli_stmt_execute($sql);
                mysqli_close($conn);
                header("Location: ./index.php");
            }
        }
    } else {
        $conn = mysqli_connect('localhost', 'root', '', 'sklep');
        $target = $target_dir . 'default.png';
        $sql = mysqli_prepare($conn, "INSERT INTO produkty(nazwa, opis,cena, ilosc, FilePath) VALUES (?,?,?,?,?)");
        mysqli_stmt_bind_param($sql, "ssdis", $name, $opis, $cena, $ilosc, $target_file);
        mysqli_stmt_execute($sql);
        mysqli_close($conn);
        header("Location: ./index.php");
    }
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
    <div class="add-container">

        <form method="post" enctype="multipart/form-data">
            <input type="text" placeholder="Nazwa Produktu" name="nazwa">
            <input type="text" placeholder="Opis Produktu" name="opis" id="opis">
            <input type="number" placeholder="Cena Produktu" step="0.01" name="cena" min="0.01">
            <input type="number" name="ilosc" placeholder="Ilosc Produktu" min="0">
            <label for="image" class="file-label">Wybierz zdjecie Produktu</label>
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