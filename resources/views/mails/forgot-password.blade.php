<?php

declare(strict_types=1);

?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Восстановление пароля</title>
    <style>
        /* Стили для письма */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .email-header {
            background-color: #1e40af;
            color: #ffffff;
            text-align: center;
            padding: 15px 10px;
        }
        .email-content {
            padding: 20px;
            color: #333333;
            line-height: 1.6;
        }
        .email-button {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #2563eb;
            color: #ffffff;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
        }
        .email-footer {
            text-align: center;
            padding: 10px;
            color: #777777;
            font-size: 12px;
        }
    </style>
</head>
<body>
<div class="email-container">
    <!-- Заголовок -->
    <div class="email-header">
        <h1>Восстановление пароля</h1>
    </div>

    <!-- Содержимое письма -->
    <div class="email-content">
        <p>Здравствуйте, {{ $name ?? 'пользователь' }}!</p>
        <p>Вы получили это письмо, потому что сделали запрос на восстановление пароля для вашей учетной записи.</p>

        <!-- Кнопка восстановления -->
        <p style="text-align: center;">
            <a href="{{ config('app.url') . $link }}" class="email-button">Сбросить пароль</a>
        </p>

        <p>Если вы не делали этот запрос, просто проигнорируйте это письмо.</p>
        <p>Ссылка для восстановления пароля будет действительна в течение 60 минут.</p>
    </div>

    <!-- Футер письма -->
    <div class="email-footer">
        <p>&copy; {{ date('Y') }} Ëрдан. Все права защищены.</p>
    </div>
</div>
</body>
</html><?php 
