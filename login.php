<?php
session_start();

if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    header("Location: protected.php"); 
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $valid_username = '';
    $valid_password = '';

    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if ($username === $valid_username && $password === $valid_password) {
        $_SESSION['logged_in'] = true; 
        header("Location: protected.php"); 
        exit;
    } else {
        $error = "Неверный логин или пароль!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./assets/style/app.css">
    <title>Login</title>
</head>

<body>
    <div class="container" style="display: flex; justify-content: center; align-items: center; height: 100vh;">
        <?php if (!empty($error)): ?>
        <p style="color: red;">
            <?php echo $error; ?>
        </p>
        <?php endif; ?>
        <form class="form" method="post" action="login.php">
            <div class="form__group">
                <label for="username">Логин:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form__group">
                <label for="password">Пароль:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button class="form__button" type="submit">Войти</button>
        </form>
    </div>
</body>

</html>