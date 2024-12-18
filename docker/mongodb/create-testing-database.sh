mongosh <<EOF
// Подключаемся с root-учётной записью
use admin;
db.auth("", "");

// Создаём базу данных, если она ещё не создана
use testing;

// Создаём пользователя с привилегиями на базу данных
db.createUser({
    user: "",
    pwd: "",
    roles: [
        { role: "readWrite", db: "testing" }
    ]
});
EOF