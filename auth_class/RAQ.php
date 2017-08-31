<?php
require __DIR__ . "/../class/sqldb_connection.php";
require __DIR__ . "/../class/photo_parser.php";
require __DIR__ . "/../class/Samfuu.php";

/*
 * Файл для модуля авторизации на сервере
 * функции и методы для авторизаии(изменения данных в БД)
 */

/*
 * Функция для обновления статуса ЗАРЕГЕСТРИРОВАННОГО ПОЛЬЗОВАТЕЛЯ на онлайн
 */
function Auth($login, $password)
{

    $errorArr = array();    //создание массива ошибок.

    if ($login == "") array_push($errorArr, "Failed email or phone number");  // проверка на пустые поля.
    if ($password == "") array_push($errorArr, "Failed password");  //
	if (preg_match("/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,256}$/", $password) != true) {
		array_push($errorArr, "Incorrect password");
	}
    $tmp_db_row = sqldb_connection::Auth_Select($login);   // достаем строку из БД

    if (count($tmp_db_row) == 0) {
        array_push($errorArr, "Failed email or phone number");
    } elseif ($password != $tmp_db_row['password']) {
        array_push($errorArr, "Failed password");
    }

    if (count($errorArr) == 0) {
        sqldb_connection::Update_online_status($tmp_db_row['user_ID'], 1,
            date("Y-m-d h:m:s"));// обновляем статус на онлайн
        $id = sqldb_connection::Auth_Select_All($login, $password);
        return $id;
    } else {
        return $errorArr[0];
    }
}

/*
 * Функция для первичной регистрации пользователя.
 */
function Registration_min($email, $phone, $password)
{
    $errorArr = array();//создание массива ошибок.
    //Валидация мыла
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        array_push($errorArr, "Incorrect email");
    }    //валидация пароля
    if (preg_match("/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,256}$/", $password) != true) {
        array_push($errorArr, "Incorrect password");
    }
    if (preg_match("/^\+[0-9]{9,18}$/", $phone) != true) {
        array_push($errorArr, "Incorrect phone");
    }
    $tmp_db_row = sqldb_connection::Registration($phone, $email);
    if (count($tmp_db_row) != 0) {
        if ($tmp_db_row['phone'] == $phone) array_push($errorArr, "Phone already using");
        if ($tmp_db_row['email'] == $email) array_push($errorArr, "Email already using");
    }
    if (count($errorArr) == 0) {
        sqldb_connection::Registration_min($phone, $password, $email, date("Y-m-d h:m:s"),
            Temp_code());
        return sqldb_connection::Auth_Select_All($email, $password);
    } else {
        return $errorArr[0];
    }
}

/*
 * Функция для получения временного пароля для продолжения регистрации
 */
function Temp_code()
{
    $tempcode = "";
    for ($i = 1; $i <= 6; $i++) {
        $tempcode .= rand(0, 9);
    }
    return $tempcode;
}

/*
 * Функция для полной регистрации пользователя
 */
function Registration_full($id, $email_2, $name, $surname, $birth_day, $birth_month,
                           $birth_year, $sex, $country, $city, $photo)
{
    $errorArr = array();//создание массива ошибок.

    if (preg_match("/^[a-zA-Z0-9_\-.]+@[a-zA-Z0-9\-]+\.[a-zA-Z0-9\-.]+{6,256}$/", $email_2) != true) {
        array_push($errorArr, "Incorrect email");
    }
    if (preg_match("/^([a-zA-Zа-яА-ЯёЁ]+){1,50}$/", $name) != true) {
        array_push($errorArr, "Incorrect name");
    }
    if (preg_match("/^([a-zA-Zа-яА-ЯёЁ]+){1,50}$/", $surname) != true) {
        array_push($errorArr, "Incorrect surname");
    }
    if (preg_match("/^([a-zA-Zа-яА-ЯёЁ]+){1,50}$/", $country) != true) {
        array_push($errorArr, "Incorrect country");
    }
    if (preg_match("/^([a-zA-Zа-яА-ЯёЁ]+){1,50}$/", $city) != true) {
        array_push($errorArr, "Incorrect city");
    }
    if (preg_match("([1-9]|[12]\d|3[01])", $birth_day) != true) {
        array_push($errorArr, "Incorrect birthday");
    }
    if (preg_match("([1-9]|1[012])", $birth_month) != true) {
        array_push($errorArr, "Incorrect birthday month");
    }
    if ($birth_year == "" || strlen($birth_year) < (date('Y') - 100) || strlen($birth_year) > date('Y')) {
        array_push($errorArr, "Incorrect birthday year");
    }

    if (count($errorArr) == 0) {
        sqldb_connection::Registration_full($id, $email_2, $name, $surname, $birth_day,
            $birth_month, $birth_year, $sex, date("Y-m-d h:m:s"),
            1, $country, $city);

        photo_parser::Getpicture_from_User($photo, $id);


        return sqldb_connection::Auth_Select_All_id($id);
    } else {
        /*
$request = array(
    'error' => $errorArr[0]);
return $request;
*/
        return $errorArr[0];
    }
}

/*
 * Функция для изменения статуса пользователя на офлайн
 */
function Quit($id)
{
    sqldb_connection::Update_online_status($id, 0, date("Y-m-d h:m:s"));   // обновляем статус на офлайн
    return sqldb_connection::Auth_Select_All_id($id);
}


function Password_forgot($login){
    $errorArr = array();
    if($login == ""){
        array_push($errorArr, "Incorrect email");
    }
    $tmp_db_row = sqldb_connection::Auth_Select($login);

    if(count($tmp_db_row) == 0){
        array_push($errorArr, "Nothing to show");
    }

    if(count($errorArr) == 0){
        $sub = "Password recovery!";
        $msg = "Dear " . $tmp_db_row['name'] . " Thank you for using the services of our development team! \r\n 
This is the password of your account: " . $tmp_db_row['password'] . " \r\n
Thank you for being with us! \r\n";
        $headers = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'From: TrustMe <info@address.com>' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        mail($login, $sub, $msg, $headers);
        return array('msg' => "Email is send");
    }
     else {
        return $errorArr[0];
    }

}