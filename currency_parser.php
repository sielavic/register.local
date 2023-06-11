<?php
class CbrCurrencyParser{
// Устанавливаем соединение с базой данных
    private $dsn = 'mysql:host=localhost;dbname=myusers';
    private $username = 'root';
    private $password = '';
    private $url = 'http://www.cbr.ru/scripts/XML_daily.asp';// Определяем URL для парсинга курсов валют

    public function parse(){
        $db = new PDO($this->dsn, $this->username, $this->password);

        $delete = $db->prepare('DELETE FROM currency_rates');
        $delete->execute();

// Получаем данные с URL
        $xml = file_get_contents($this->url);

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
    }
}

// Создаем экземпляр парсера и вызываем метод parse
$cbr_parser = new CbrCurrencyParser();
$cbr_parser->parse();
// Добавляем задачу в Cron на unix системах для выполнения каждые 3 часа
// 0 */3 * * * /usr/bin/php /path/to/currency_parser.php >> /var/log/currency_parser.log