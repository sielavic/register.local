<?php
class CbrCurrencyConverter{
    private $host = "localhost";
    private $username = "root";
    private $password = '';
    private $dbname = "myusers";

    public function converter(){
    // Получаем выбранную валюту пользователя и сумму для конвертации
$target_currency = $_POST['target_currency'];
$amount = $_POST['amount'];
$another_currency = $_POST['another_currency'];

$conn = new mysqli($this->host, $this->username, $this->password, $this->dbname);
if ($conn->connect_error)
{
die("Connection failed: " . $conn->connect_error);
}


if ($target_currency !== "RUB") {
    $rub_rate = 1;
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
    echo number_format($result, 2) . ' RUB = ' . $amount . ' ' . $target_currency;
} else {
    $target_rate = 1;
// Получаем курс выбранной валюты из базы данных
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
    echo $amount . ' ' . $target_currency . ' = ' . number_format($result, 4) . ' ' . $another_currency;
}
}
}
$cbr_converter = new CbrCurrencyConverter();
$cbr_converter->converter();


