<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Калькулятор витрат</title>
    <style>
        body {
            background-color: cadetblue;
        }

        h1 {
            text-align: center;
        }

        #form1{
            position: fixed;
            top: 7px;
            right: 10px;
        }

        #exit{
            height: 25px;
            border-radius: 14px;
            cursor: pointer;
            background-color: salmon;
        }

        #addcosts {
            height: 25px;
            border-radius: 14px;
            cursor: pointer;
            background-color: salmon;
        }

        div {
            display: flex;
        }

        #inputcostvalue {
            width: 7%;
            height: 25px;
            border-radius: 14px;
            background-color: lightyellow;
            outline: 0;
        }

        #inputcostname {
            width: 93%;
            height: 25px;
            border-radius: 14px;
            background-color: lightyellow;
            outline: 0;
        }

        pre {
            border-radius: 8px;
            background-color: gold;
            border: 2px solid;
        }
    </style>
</head>
<body>
    <h1>Калькулятор витрат</h1>
    <form action="" method="post" id="form1">
        <input type="submit" name="exit" id="exit" value="Вийти">
    </form>
    <?php
    session_start();
    if (isset($_SESSION['username'])) {
        $username = $_SESSION['username'];
        echo "<h4>Вітаємо, $username!</h4>";
        if (file_exists($username)) {
            chdir($username);
        }
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST["exit"])) {
            session_destroy();
            header('Location: index.php');
            exit();
        }
    }
    ?>
    <form action="" method="post">
        <input type="submit" name="addcosts" id="addcosts" value="Додати витрати">
        <br>
        <br>
        <div>
            <input type="number" name="inputcostvalue" id="inputcostvalue" required>
            <input type="text" name="inputcostname" id="inputcostname" required>
        </div>
    </form>
    <?php
    if (file_exists('costscount.txt')) {
        $fileCostValue = file_get_contents('costscount.txt');
    }
    else {
        $fileCostValue = 0;
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST["addcosts"])) {
            $inputcostvalue = $_POST["inputcostvalue"];
            if (is_numeric($inputcostvalue)) {
                if ($inputcostvalue > 0) {
                    $inputcostname = $_POST["inputcostname"];
                    if (ctype_space($inputcostname)) {
                        echo "<br>Неможливо додати порожнє поле!";
                    }
                    else {
                        $fileCostValue += $inputcostvalue;
                        $hCostFile = fopen('costscount.txt', 'w');
                        fwrite($hCostFile, $fileCostValue);
                        fclose($hCostFile);

                        date_default_timezone_set('Europe/Kiev');
                        $fileCostName = "\n" . date('d.m.Y H:i:s') . " - $inputcostname, $inputcostvalue\n";
                        file_put_contents('costs.txt', $fileCostName, FILE_APPEND);
                        header('Location: index3.php');
                        exit();
                    }
                }
                else {
                    if ($inputcostvalue == 0) {
                        echo "<br>Введіть значеня відмінне від 0!";
                    }
                    else {
                        echo "<br>Введіть додатне число!";
                    }
                }
            }
            else {
                echo "<br>Введено нечислове значення!";
            }
        }
    }
    echo "<h3>Поточні витрати: $fileCostValue</h3>";

    if (file_exists('costs.txt')) {
        $costs = file_get_contents('costs.txt');
        echo "<pre>$costs</pre>";
    }
    ?>
</body>
</html>