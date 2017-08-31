<?php

require __DIR__ . "/../class/sqldb_connection.php";
require __DIR__ . "/../class/photo_parser.php";
require __DIR__ . "/../class/Samfuu.php";

/*
 * Product модель
 * Содержит все нужные для пользователя методы( для подачи запросов в БД и приема и групировки данных)
 */


/*
 * Функция для ДОБАВЛЕНИЯ ТОВАРА пользователем
 * */

class Product
{
    function Add_product($user_id, $product_name, $category, $price, $made_in, $description, $product_country, $product_city, $product_photo)
    {
        $errorArr = array();//создание массива ошибок.

        if (/*(preg_match("/^[а-яА-ЯеЁа-zA-Z0-9.-.]+$/", $product_name) != true) || */
            $product_name == "" || strlen($product_name) < 2 || strlen($product_name) > 30
        ) {
            array_push($errorArr, "Incorrect product name");
        }
        if ($category == "" || strlen($category) < 5 || strlen($category) > 15) {
            array_push($errorArr, "Incorrect product category");
        }
        if ($made_in == "" || strlen($made_in) < 2 || strlen($made_in) > 15) {
            array_push($errorArr, "Incorrect product made in");
        }
        if ($description == "" || strlen($description) < 20 || strlen($description) > 200) {
            array_push($errorArr, "Incorrect product description");
        }
        if (preg_match("/^([a-zA-Zа-яА-ЯёЁ]+){1,50}$/", $product_country) != true) {
            array_push($errorArr, "Incorrect country");
        }
        if (preg_match("/^([a-zA-Zа-яА-ЯёЁ]+){1,50}$/", $product_city) != true) {
            array_push($errorArr, "Incorrect city");
        }
        if ($price == "" || strlen($price) < 1) {
            array_push($errorArr, "Incorrect product price");
        }

        if (count($errorArr) == 0) {

            $product_id = sqldb_connection::IS_add_product($product_name, $category, $price, $user_id,
                0, "disable", $made_in, $description, date('Y-m-d H:i:s'),
                $product_country, $product_city);

            if ($product_photo != "") {
                photo_parser::Getpicture_from_product($product_photo, $product_id);

            }

            sqldb_connection::Product_photo($product_id);

            $tmp_array = sqldb_connection::Show_product_singleview($product_id);
            //array_push($tmp_array, sqldb_connection::Show_product_multiview($product_id));
            return $tmp_array;
        } else {
            return $errorArr[0];
        }
    }

    /*
     * Выставляем товар на лот
     * */
    function Product_to_lot($product_id)
    {
        $errorArr = array();

        if ($product_id != null) {
            $bid_date = date('Y-m-d H:i:s');
            $tmp_db_row = sqldb_connection::Lot_Helper($product_id); // чтобы получить ид юзера и прайс

            sqldb_connection::Product_to_auction($product_id, $tmp_db_row['owner_id'], $tmp_db_row['price'], $bid_date);

            if ($tmp_db_row != null) {
                sqldb_connection::Update_product_status($product_id, "active");   // обновляем статус на active, если мы успешно выставили товар на аукцион
                return sqldb_connection::Show_product_singleview($product_id);
            }
        } else {
            array_push($errorArr, "Failed to up product in auction");
            return $errorArr;
        }
    }

    /*
     * Удаление продукта
     * */
    function Product_delete($product_id)
    {
        $errorArr = array();
        if ($product_id != null) {
            sqldb_connection::Delete_product($product_id); // удаляем продукт из базы данных
            return "Product successfully deleted";
        } else {
            array_push($errorArr, "Failed to delete product");
            return $errorArr;
        }
    }

    /*
     * Редактирование продукта
     * */
    function Product_edit($product_id, $product_name, $category, $price, $made_in, $description, $product_country, $product_city, $product_photo)
    {
        $errorArr = array();//создание массива ошибок.

        if ($product_name == "" || strlen($product_name) < 2 || strlen($product_name) > 30) {
            array_push($errorArr, "Incorrect product name");
        }
        if ($category == "" || strlen($category) < 5 || strlen($category) > 15) {
            array_push($errorArr, "Incorrect product category");
        }
        if ($made_in == "" || strlen($made_in) < 2 || strlen($made_in) > 15) {
            array_push($errorArr, "Incorrect product made in");
        }
        if ($description == "" || strlen($description) < 20 || strlen($description) > 200) {
            array_push($errorArr, "Incorrect product description");
        }
        if ($product_country == "" || strlen($product_country) < 3 || strlen($product_country) > 60) {
            array_push($errorArr, "Incorrect product country");
        }
        if ($product_city == "" || strlen($product_city) < 2 || strlen($product_city) > 20) {
            array_push($errorArr, "Incorrect product city");
        }
        if ($price == "" || strlen($price) < 1) {
            array_push($errorArr, "Incorrect product price");
        }


        if (count($errorArr) == 0) {
            sqldb_connection::Product_Edit($product_id, $product_name, $category, $price, $made_in, $description, $product_country, $product_city);

            if ($product_photo != "") {
                photo_parser::Getpicture_from_product($product_photo, $product_id);
                sqldb_connection::Product_photo_update($product_id);
            }
            return "Product_update";//sqldb_connection::Show_product_singleview($product_id);
        } else {
            return $errorArr[0];
        }
    }

    /*
     * Поиск продукта по критериям , см выборку, возвращает первые 50
     * */
    function Product_search($product_id, $query)
    {
        $errorArr = array();

        if ($product_id == null) {
            array_push($errorArr, "Failed id");
        }
        if ($query == "") {
            $tmp_db_row = sqldb_connection::Show_product_multiview($product_id);
        }
        if (strlen($query) > 0) {
            $query = trim($query);
            $tmp_db_row = sqldb_connection::Product_Search($product_id, $query);
        }
        if (count($tmp_db_row) == 0) {
            return "NOTHING";
        }
        if (count($tmp_db_row) > 0) {
            return $tmp_db_row;
        } else {
            return $errorArr;
        }
    }

    /*
     *Синглвью продукта
     * */
    function Product_singleview($product_id)
    {
        if ($product_id != null) {
            $tmp_db_row = sqldb_connection::Show_product_singleview($product_id);
            return $tmp_db_row;
        } else {
            return "Failed product id";
        }
    }


    /*
     * Мультивью продукта
     * */
    function Product_multiview($user_id)
    {
        if ($user_id != null) {
            $tmp_db_row = sqldb_connection::Show_product_multiview($user_id);
            return $tmp_db_row;
        } else {

            return "Failed user id";
        }
    }

    function List_product($category)
    {
        if ($category != null) {
            $tmp_db_row = sqldb_connection::Get_list_product($category);
            return $tmp_db_row;
        } else {
            return "Failed user id";
        }
    }

    function List_my_product($user_id)
    {
        if ($user_id != null) {
            $tmp_db_row = sqldb_connection::Get_list_my_product($user_id);
            return $tmp_db_row;
        } else {
            return "Failed user id";
        }
    }

    function List_orders($user_id)
    {
        if ($user_id != null) {
            $tmp_db_row = sqldb_connection::Get_list_orders($user_id);
            return $tmp_db_row;
        } else {
            return "Failed user id";
        }
    }

    function Add_to_favourite_product($user_id, $product_id)
    {
        $errorArr = array();
        if ($user_id != null) array_push($errorArr, "Failed user id");
        if ($product_id != null) array_push($errorArr, "Failed product id");
        if (count($errorArr) == 0) {
            sqldb_connection::Add_favourite_product($user_id, $product_id, date('Y-m-d H:i:s'));
            return sqldb_connection::Show_product_singleview($product_id);
        } else {
            return $errorArr;
        }
    }

    function List_favourite_product($user_id)
    {
        if ($user_id != null) {
            $tmp_db_row = sqldb_connection::Get_list_favourite($user_id);
            return $tmp_db_row;
        } else {
            return "Failed user id";
        }
    }
}