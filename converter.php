<?php
// Получаем выбранную валюту пользователя и сумму для конвертации
$target_currency = $_POST['target_currency'];
$amount = $_POST['amount'];
$another_currency = $_POST['another_currency'];


$host = "localhost";
$username = "root";
$password = '';
$dbname = "myusers";

// Получаем текущий курс рубля из базы данных
$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


if ($target_currency !== "RUB"){
    $rub_rate = 1;
// Получаем курс выбранной валюты из базы данных
$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$sql = "SELECT `currency_value` FROM `currency_rates` WHERE `currency_code`=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $target_currency);
$stmt->execute();
$result = $stmt->get_result();
$target_rate = $result->fetch_assoc()['currency_value'];
$stmt->close();
$conn->close();
$result = $amount / $rub_rate * $target_rate;
// Отображаем результат
    echo number_format($result, 2)  . ' RUB = '. $amount  . ' '. $target_currency    ;
}
else{
    $target_rate =1;

// Получаем курс выбранной валюты из базы данных
    $conn = new mysqli($host, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $sql = "SELECT `currency_value` FROM `currency_rates` WHERE `currency_code`=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $another_currency);
    $stmt->execute();
    $result = $stmt->get_result();
    $another_rate = $result->fetch_assoc()['currency_value'];
    $stmt->close();
    $conn->close();
    // Пересчитываем сумму в выбранную валюту
    $result = $amount / $another_rate * $target_rate;
    // Отображаем результат
    echo   $amount  . ' '. $target_currency .' = '. number_format($result, 4)  . ' ' . $another_currency;
}



