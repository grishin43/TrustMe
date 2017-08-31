<?php

require __DIR__."/../class/sqldb_connection.php";
require __DIR__.("/../class/Samfuu.php");


/*
 * Данные для групировки списков Аукционных товаров, для всех видов пользователей
 * Содержит все нужные для пользователя методы( для подачи запросов в БД и приема и групировки данных)
 */

/*
 * Функция для добавлнеии ставки по лоту либо выставления товара на аукцион
 */
class Auction{
    function Make_bid($product_id, $user_id, $user_bid)
    {
        $errorArr = array();//создание массива ошибок.
        $tmp_array = sqldb_connection::Show_product_singleview($product_id); //запрос для того, что бы узнать статус проекта

        if ($product_id == null || $tmp_array['status'] != "active") array_push($errorArr, "Failed product id"); // проверка на пустой product_id

        if ($user_id == null) array_push($errorArr, "Failed user id"); // проверка на пустой user_id

        if ($user_bid == "" && strlen($user_bid) < 1 ) {
            array_push($errorArr, "Incorrect bid amount");
        }

        if (count($errorArr) == 0) {
            sqldb_connection::bid_create($product_id, $user_id, $user_bid, date("Y-m-d h:m:s"));
            return "Lot created";
        } else {
            return $errorArr[0];
        }
    }

    /*
     * Функция для удаления лота с аукциона
     */
    //добавить проверку на валидность лота
    function Remove_bid($product_id)
    {
        $tmp_array = sqldb_connection::Show_product_singleview($product_id); //запрос для того, что бы узнать статус объекта
        if($product_id != null && $tmp_array['status'] == "active"){
            sqldb_connection::bid_remove($product_id); // удаляем лот из базы данных
            sqldb_connection::Update_product_status($product_id, "disable");
            return "Lot successfully deleted";
        }
        else{
            return  "Wrong lot id";
        }
    }

    /*
     * Принимаем user_id и возвращаем список ставок этого пользователя
     */

    function Show_bids_by_user($user_id)
    {

        if ($user_id != null) {
            $tmp_db_row = sqldb_connection::select_multi_view_bids_by_user($user_id);   // достаем строки из БД
            if (count($tmp_db_row) == 0) {
                return "NOTHING";
            } else {
                return $tmp_db_row;
            }
        }else{
            return "Failed id";
        }
    }


    /*
    * Принимаем product_id и возвращаем список ставок по этому товару
    */


    function Show_bids_by_product($product_id)
    {
        if ($product_id != null) {
            $tmp_db_row = sqldb_connection::select_multi_view_bids_by_lot($product_id);   // достаем строки из БД
            if (count($tmp_db_row) == 0) {
                return "NOTHING";
            } else {
                return $tmp_db_row;
            }
        } else {
            return "Failed id";
        }
    }


    /*
     * Принимаем bid_id и проверяем ставку на актуальность и актиность товара
     */

    function isBidValid($product_id)
    {


    }
}