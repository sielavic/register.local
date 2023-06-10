<?php

// Подключаемся к базе данных
$conn = mysqli_connect("localhost", "root", "", "myusers");

// Получаем данные из формы
$username = mysqli_real_escape_string($conn, $_POST['username']);
$password = mysqli_real_escape_string($conn, $_POST['password']);

// Ищем пользователя с таким же именем
$sql = "SELECT * FROM users WHERE username='$username'";
$query = mysqli_query($conn, $sql);
if (mysqli_num_rows($query) > 0) {
    $row = mysqli_fetch_assoc($query);
  // Проверяем правильность введенного пароля
    if(password_verify($password, $row['password'])) {
        // Устанавливаем пользовательские данные в сессию
        session_start();
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['username'] = $row['username'];
        header("Location: welcome.php");
    } else {
        echo "Неправильный пароль";
    }
} else {
    echo "Пользователь с таким именем не найден";
}

mysqli_close($conn);