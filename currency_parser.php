<?php
// Устанавливаем соединение с базой данных
$dsn = 'mysql:host=localhost;dbname=myusers';
$username = 'root';
$password = '';
$db = new PDO($dsn, $username, $password);

$delete =  $db->prepare('DELETE FROM currency_rates');
$delete->execute();
// Определяем URL для парсинга курсов валют
$url = 'http://www.cbr.ru/scripts/XML_daily.asp';

// Получаем данные с URL
$xml = file_get_contents($url);

// Создаем объект SimpleXMLElement из XML-данных
$sxml = new SimpleXMLElement($xml);

// Проходимся по каждому элементу в XML и добавляем его в базу данных
foreach ($sxml->Valute as $valute) {
    $code = $valute->CharCode;
    $name = $valute->Name;
    $value = $valute->Value;

    // Добавляем данные в базу данных

    $stmt = $db->prepare('INSERT INTO currency_rates (currency_code, currency_name, currency_value, created_at) VALUES (:code, :name, :value, NOW())');

    $stmt->bindParam(':code', $code);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':value', $value);
    $stmt->execute();
}

// Закрываем соединение с базой данных
$db = null;

// Добавляем задачу в Cron на unix системах для выполнения каждые 3 часа
// 0 */3 * * * /usr/bin/php /path/to/currency_parser.php >> /var/log/currency_parser.log