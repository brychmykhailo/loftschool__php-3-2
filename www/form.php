<?php

require ('functions.php');

if (!empty($_POST['email'])) {
    $userEmail = $_POST['email'];
} else {
    echo 'Заполните обязательные поля для оформления заказа';
    die;
}

if (!empty($_POST['street']) && !empty($_POST['home']) && !empty($_POST['part']) && !empty($_POST['appt']) && !empty($_POST['floor'])) {
    $address = 'улица ' . $_POST['street'] . '; дом ' . $_POST['home'] . '; корпус ' . $_POST['part'] . '; квартира ' . $_POST['appt'] . '; этаж ' . $_POST['floor'];
} else {
    echo 'Укажите адрес доставки';
    die;
}

if ($userEmail && $address) {

    if (!isUser($userEmail)) {
        addUser($userEmail);
    }

    $orderInfo = addOrder($userEmail, $address);
    echo 'Спасибо, ваш заказ будет доставлен по адресу:' . $address . '<br>';
    echo 'Номер вашего заказа: '. $orderInfo['orderid']. '<br>';
    echo 'Это ваш ' . $orderInfo['userorders'] . '-й заказ!<br>';
}




