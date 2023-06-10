<?php
$host = "localhost";
$username = "root";
$password = '';
$dbname = "myusers";

$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// Проверяем, авторизован ли пользователь
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Отображаем имя пользователя
echo "Привет, " . $_SESSION['username'];
?>
<h2>Конвертер валют</h2>
<form action="converter.php" method="POST">
    <ul>Конвертация в рубли </ul>
    <select name='target_currency'>
    <?php
    $sql= "SELECT currency_code FROM `currency_rates`";
    $result = $conn->query($sql);
    while($row=$result->fetch_array(MYSQLI_ASSOC)) {
    echo "<option  value=".$row['currency_code'].">".$row['currency_code']."</option>
 ";}
    ?>
    </select>



    <input type="text" name="amount" placeholder="Сумма для конвертации" required>
    <button type="submit" name="conversion">Получить</button>
</form>
<form action="converter.php" method="POST">
<ul>Конвертация рубля в другие валюты </ul>
<select name='target_currency'>
    <option value="RUB">RUB</option>
</select>
<input type="text" name="amount" placeholder="Сумма для конвертации в" required>
<select name='another_currency'>
    <?php
    $sql= "SELECT currency_code FROM `currency_rates`";
    $result = $conn->query($sql);
    while($row=$result->fetch_array(MYSQLI_ASSOC)) {
        echo "<option  value=".$row['currency_code'].">".$row['currency_code']."</option>
 ";}
    ?>
</select>
<button type="submit" name="conversion">Получить</button>
</form>