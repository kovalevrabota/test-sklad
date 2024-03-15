<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Авторизации</title>

    <link rel="stylesheet" href="./css/authorization.css">
</head>

<body>
    <div class="authorization">
        <form method="post" class="form-authorization">

            <p class="field">
                <label for="login">Логин: </label>
                <input type="text" id="login" name="login" placeholder="">
            </p>

            <p class="field">
                <label for="password">Пароль: </label>
                <input type="password" id="password" name="password" placeholder="">
            </p>

            <button id="submit" type="submit" class="button button--success">Авторизоваться</button>
            <p id="message" class=""></p>
        </form>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="./js/authorization.js"></script>
</body>

</html>