<?php
class RegisterUser{
    public function register()
    {
// Подключаемся к базе данных
        $conn = mysqli_connect("localhost", "root", "", "myusers");

// Получаем данные из формы
        $username = mysqli_real_escape_string($conn, $_POST['username']);
        $password = mysqli_real_escape_string($conn, $_POST['password']);


// Проверяем наличие пользователя с таким же именем
        $sql = "SELECT * FROM users WHERE username='$username'";
        $query = mysqli_query($conn, $sql);
        if (mysqli_num_rows($query) > 0) {
            echo "Такой пользователь уже существует";
        } else {
            // Хешируем пароль
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            // Добавляем новую запись в таблицу users
            $sql = "INSERT INTO users (username, password) VALUES ('$username', '$hashed_password')";
            mysqli_query($conn, $sql);
            echo "Вы успешно зарегистрировались";
        }

        mysqli_close($conn);
    }
}
$register_user = new RegisterUser();
$register_user->register();