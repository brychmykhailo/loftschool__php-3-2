<?php

// Проверка на регистрацию пользователя с $email
function isUser($email)
{
try {
$pdo = new PDO("mysql:host=localhost;dbname=loftschool", 'root', 'root');
$query = $pdo->prepare("SELECT * FROM users WHERE `email` = :email");
$query->execute(['email' => $email]);
$user = $query->fetchAll( PDO::FETCH_ASSOC);

if ($user) {
return true;
} else {
return false;
}

} catch (PDOException $e) {
echo $e->getMessage();
die;
}
}

// Регистрация нового пользователя с $email
function addUser($email)
{
try {
$pdo = new PDO("mysql:host=localhost;dbname=loftschool", 'root', 'root');
$query = $pdo->prepare("INSERT INTO users (email, ordercount) VALUES (:email, 0)");
$query->execute(['email' => $email]);
return true;
} catch (PDOException $e) {
echo $e->getMessage();
die;
}
}

// Добавление заказа
function addOrder($email, $address)
{
try {
$pdo = new PDO("mysql:host=localhost;dbname=loftschool", 'root', 'root');

//получаем id пользователя по email
$findUserQuery = $pdo->prepare("SELECT * FROM users WHERE `email` = :email");
$findUserQuery->execute(['email' => $email]);
$user = $findUserQuery->fetchAll(PDO::FETCH_ASSOC);
$userId = $user[0]['userid'];
$orderDate = date('Y-m-d H:i:s');
$addOrderQuery = $pdo->query("INSERT INTO orders (userid, orderdate, address) VALUES ('$userId', '$orderDate', '$address');");

//получаем id заказа
$orderIdQuery = $pdo->query("SELECT * FROM orders WHERE `userid` = '$userId' and `orderdate` = '$orderDate'");
$order = $orderIdQuery->fetchAll(PDO::FETCH_ASSOC);
$orderId = $order[0]['orderid'];

//возвращаем ошибку или масив с id заказа и числом заказов пользователя
if (!$addOrderQuery) {
var_dump($pdo->errorInfo());
} else {
//увеличиваем число заказов пользователя
$pdo->query("UPDATE users SET ordercount=ordercount+1 WHERE userid = '$userId'");
//получаем число заказов пользователя
$userOrdersQuery = $pdo->query("SELECT ordercount FROM users WHERE `userid` = '$userId'");
$userOrders = $userOrdersQuery->fetchAll(PDO::FETCH_ASSOC);

return [
'orderid' => $orderId,
'userorders' => $userOrders[0]['ordercount'],
];
}

} catch (PDOException $e) {
echo $e->getMessage();
die;
}
}