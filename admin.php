<?php
session_start();
include 'config.php';
if ($_SESSION['uprawnienia'] !== "admin" && $_SESSION['uprawnienia'] !== "superAdmin") {
    header("Location: ./index.php");
}
?>


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
<?php $conn = mysqli_connect($host, $user, $pass, $database);
$sqlData = "SELECT SUM(cena_sum) as 'sales',MONTH(data) as 'month' FROM statystyki GROUP BY MONTH(data)";
$chartDataResult = mysqli_query($conn, $sqlData);

$chartData = []; // Initialize an empty array to store the data

// Fetch the data from the MySQL result object
while ($row = mysqli_fetch_assoc($chartDataResult)) {
    $chartData[] = $row; // Add each row to the array
}

$chartDataJson = json_encode($chartData);
$sqlData2 = "SELECT nazwa,SUM(cena*statystyki_zakup.ilosc) as zysk FROM `statystyki_zakup` JOIN produkty ON produkty.id=statystyki_zakup.id_produktu GROUP BY id_produktu";
$chartDataResult2 = mysqli_query($conn, $sqlData2);

$chartData2 = []; // Initialize an empty array to store the data

// Fetch the data from the MySQL result object
while ($row2 = mysqli_fetch_assoc($chartDataResult2)) {
    $chartData2[] = $row2; // Add each row to the array
}

$chartDataJson2 = json_encode($chartData2);
$sqlData3 = "SELECT upr,COUNT(login) as login FROM `uzytkownicy` GROUP BY upr";
$chartDataResult3 = mysqli_query($conn, $sqlData3);

$chartData3 = []; // Initialize an empty array to store the data

// Fetch the data from the MySQL result object
while ($row3 = mysqli_fetch_assoc($chartDataResult3)) {
    $chartData3[] = $row3; // Add each row to the array
}

$chartDataJson3 = json_encode($chartData3);
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
    <?php
    include 'menu.php'
    ?>
    <div id="panelContainer">
        <div id="usersContainer">
            <?php
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
        <div id="chartContainer">
            <div id="options">
                <div id="Total" class="optionsC">
                    <div class="header">
                        <h1>Przychód:</h1>
                    </div>
                    <p>W tym miesiącu:
                        <?php
                        $sql = "SELECT SUM(cena_sum) as cena FROM statystyki WHERE MONTH(data)=MONTH(CURRENT_TIME())";
                        $result = mysqli_query($conn, $sql);
                        $row = mysqli_fetch_assoc($result);
                        echo $row['cena'] . "zł"
                        ?>
                    </p>
                </div>
                <div id="products" class="optionsC">
                    <div class="header">
                        <h1>Sprzedaż produktow</h1>
                    </div>
                    <p>Wyświetl statystyki</p>
                </div>
                <div id="pracownicy" class="optionsC">
                    <div class="header">
                        <h1>Uzytkownicy</h1>
                    </div>
                    <p>Ilosc Uzytkownikow:
                        <?php
                        $sql = "SELECT COUNT(*) as ilosc FROM uzytkownicy";
                        $result = mysqli_query($conn, $sql);
                        $row = mysqli_fetch_assoc($result);
                        echo $row['ilosc']
                        ?>
                    </p>
                </div>
            </div>
            <div id="chart">
                <canvas id="myChart" style="width:100%; max-width:80vw; max-height:70vh;"></canvas>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        var pleasingColors = [
            '	#00A36C', 
            '	#008000', 
            '#228B22', 
            '#4F7942',
            '#50C878', 
            '	#DFFF00', 
            '#AFE1AF',
            '#097969',
            '#AAFF00',
            '#7FFFD4'
        ];
        const months = [
            "January", "February", "March", "April", "May", "June", "July",
            "August", "September", "October", "November", "December"
        ];

        function numberToMonth(number) {
            // Subtract 1 from the number because array indices start from 0
            return months[number - 1];
        }

        var chartData = <?php echo $chartDataJson; ?>;
        var chartData2 = <?php echo $chartDataJson2; ?>;
        var chartData3 = <?php echo $chartDataJson3; ?>;
        var labele = chartData3.map(item => item.upr);
        const labelColors = backgroundColors
        var backgroundColors = labele.map(function() {
            var randomIndex = getRandomColorIndex();
            var temp = pleasingColors[randomIndex];
            pleasingColors.splice(randomIndex, 1);
            return temp
        });
        console.log(chartData, chartData2, chartData3)
        var sales = chartData.map(item => item.sales);
        labele = chartData.map(item => numberToMonth(item.month));
        var myChart = new Chart("myChart", {
            type: "line",
            data: {
                labels: labele,
                font: {
                    size: '50px'
                },
                datasets: [{
                    fill: false,
                    pointRadius: 4,
                    borderColor: "#28a745",
                    data: sales,
                    tension: 0.3
                }]
            },
            options: {
                color: "blue",
                plugins: {
                    title: {
                        display: true,
                        text: "Przychód według miesiaca",
                        font: {
                            family: 'Arial, sans-serif',
                            size: 25,
                        },
                        color: "#333"
                    },
                    tooltip: {
                        intersect: false, // Display tooltips at the same x-axis width point
                        mode: 'index', // Show a single tooltip for each dataset at the same index
                    },
                    legend: {
                        display: false,
                    },


                }
            },
        });
        document.getElementById('Total').addEventListener('click', () => {
            total()
        })
        document.getElementById('products').addEventListener('click', () => {
            product()
        })
        document.getElementById('pracownicy').addEventListener('click', () => {
            worker()
        })

        function total() {
            sales = chartData.map(item => item.sales);
            labele = chartData.map(item => numberToMonth(item.month));
            myChart.data.labels = labele;
            myChart.data.datasets[0].data = sales;
            myChart.config.type = 'line';
            myChart.data.datasets[0].backgroundColor = 'transparent'
            myChart.data.datasets[0].borderColor = '#42b15b'
            myChart.options.plugins.title.text = 'Przychód według miesiaca'
            myChart.options.plugins.tooltip.intersect = false
            myChart.options.plugins.tooltip.mode = 'index'
            myChart.options.scales.x.display = true
            myChart.options.scales.y.display = true
            myChart.update()
        }

        function product() {
            sales = chartData2.map(item => item.zysk);
            labele = chartData2.map(item => item.nazwa);
            myChart.data.labels = labele;
            myChart.data.datasets[0].data = sales;
            myChart.config.type = 'bar';
            myChart.data.datasets[0].backgroundColor = '#42b15b'
            myChart.data.datasets[0].borderColor = '#42b15b'
            myChart.options.plugins.title.text = 'Sprzedaż według produktu'
            myChart.options.plugins.tooltip.intersect = false
            myChart.options.plugins.tooltip.mode = 'index'
            myChart.options.scales.x.display = true
            myChart.options.scales.y.display = true
            myChart.update()
        }

        function worker() {

            sales = chartData3.map(item => item.login);
            labele = chartData3.map(item => item.upr);
            console.log(sales, labele)
            myChart.data.labels = labele;
            myChart.data.datasets[0].data = sales;
            myChart.config.type = 'doughnut';
            myChart.data.datasets[0].backgroundColor = backgroundColors
            myChart.data.datasets[0].borderColor = backgroundColors
            myChart.options.plugins.title.text = 'Ilość uzytkownikow i rodzaj ich uprawnien'
            myChart.options.plugins.tooltip.intersect = true
            myChart.options.plugins.tooltip.mode = 'point'
            myChart.options.scales.x.display = false
            myChart.options.scales.y.display = false
            myChart.update()
        }

        function getRandomColorIndex() {
            return Math.floor(Math.random() * pleasingColors.length);
        }

        function changed(id) {
            document.getElementById(`sub` + id).click()
        }
    </script>
</body>

</html>