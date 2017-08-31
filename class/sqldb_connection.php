<?php

/*
 * Класс для всех запросов с сервера в SQL базу.
 * (добавление, удаление, изменение)
 */

class sqldb_connection
{
    /*
     * ИНКАПСУЛИРОВАННАЯ ФУНКЦИЯ, ДЛЯ КОДКЛЮЧЕНИЮ В БАЗЕ(ИНКАПСУЛИРОВАННАЯ!!)
     */
    private function DB_connect()
    {
        $dsn = 'mysql:dbname=trustme_db;host=127.0.0.1';
        $user = 'root';
        $password = '';

        try {
            $dbh = new PDO($dsn, $user, $password);
            return $dbh;
        } catch (PDOException $e) {
            return 'Connection failed: ' . $e->getMessage();
        }
    }

    /*
     * Выбока из баззы значения для проверки наличия пользователя в БД
     */
    public static function Auth_Select($login)
    {
        $dbh = sqldb_connection::DB_connect();
        $sth = $dbh->prepare("SELECT user_ID,name,email,phone,password FROM user WHERE email= :login OR phone= :login");
        $sth->execute(array(':login' => $login));
        return $sth->fetch(PDO::FETCH_ASSOC);
    }

    /*
    * Выбока из баззы всего юзера
    */
    public static function Auth_Select_All($login, $password)
    {
        $dbh = sqldb_connection::DB_connect();
        $sth = $dbh->prepare("SELECT * FROM user WHERE email= :login OR phone= :login  AND password = :password");
        $sth->execute(array(':login' => $login, ':password' => $password));
        return $sth->fetch(PDO::FETCH_ASSOC);
    }

    /*
    * Выбока из баззы всего юзера
    */
    public static function Auth_Select_All_id($user_id)
    {
        $dbh = sqldb_connection::DB_connect();
        $sth = $dbh->prepare("SELECT * FROM user WHERE user_id = :user_id");
        $sth->execute(array(':user_id' => $user_id));
        return $sth->fetch(PDO::FETCH_ASSOC);
    }

    /*
     * Функция для обновления статуса ЗАРЕГЕСТРИРОВАННОГО ПОЛЬЗОВАТЕЛЯ на онлайл или офлайн
     */
    public static function Update_online_status($id, $value, $last_visit)
    {
        $dbh = sqldb_connection::DB_connect();
        $sth = $dbh->prepare("UPDATE user SET online_status =:online_status, last_visit = :last_visit WHERE user_ID =:id");
        $sth->execute(array(':online_status' => $value, ':id' => $id, ':last_visit' => $last_visit));
    }

    /*
     * Функция для выборки и сравнения данных первичной регистрации пользователя.
     */
    public static function Registration($phone, $email)
    {
        $dbh = sqldb_connection::DB_connect();
        $sth = $dbh->prepare("SELECT email,phone,password FROM user WHERE phone=:phone OR email =:email ");
        $sth->execute(array(':phone' => $phone, ':email' => $email));
        return $sth->fetch(PDO::FETCH_ASSOC);
    }

    /*
     * Функция для внесения в базу минимальных данных о пользователе
     */
    public static function Registration_min($phone, $password, $email, $reg_date, $code)
    {
        $dbh = sqldb_connection::DB_connect();
        $sth = $dbh->prepare("INSERT INTO user(name,password,email,phone,reg_date, temp_code ) 
                          VALUES(:name, :password, :email, :phone, :reg_date,:code)");
        $sth->execute(array(':name' => "someone", ':password' => $password, ':email' => $email, ':phone' => $phone,
            ':code' => $code, ':reg_date' => $reg_date));
    }

    /*
     * Функция для внесения в базу почти всех данных о пользователя
     */
    public static function Registration_full($id, $email_2, $name, $surname, $birth_day, $birth_month, $birth_year, $sex, $last_visit, $online_status, $country, $city)
    {
        $dbh = sqldb_connection::DB_connect();
        $sth = $dbh->prepare("UPDATE user SET email_2 = :email_2, name = :name,  surname = :surname, birth_day = :birth_day, birth_month =
        :birth_month, birth_year = :birth_year, sex = :sex, last_visit = :last_visit, online_status = :online_status, country = :country, city = :city WHERE user_id= :id");
        $sth->execute(array(':email_2' => $email_2, ':name' => $name, ':surname' => $surname, ':birth_day' => $birth_day,
            ':birth_month' => $birth_month, ':birth_year' => $birth_year, ':sex' => $sex, ':last_visit' => $last_visit,
            ':online_status' => $online_status, ':country' => $country, ':city' => $city, ':id' => $id));
    }

    public static function User_photo_update($user_id)
    {
        $dbh = sqldb_connection::DB_connect();
        $photo_path = "http://37.57.92.40/trustme/class/picture/user_photo/id";
        $sth = $dbh->prepare("UPDATE user SET single_photo= :large, 
                              mid_photo = :medium_photo, multi_photo = :small_photo
                              WHERE user_ID = :id");
        $sth->execute(array(':id' => $user_id,
            ':large' => $photo_path . $user_id . "_large.jpeg",
            ':medium_photo' => $photo_path . $user_id . "_medium.jpeg",
            ':small_photo' => $photo_path . $user_id . "_small.jpeg"));
    }

    /*
       * Илья
       *
       */
//Функция для выбора первых 50-ти пользователей
    public static function Select_Multi_View_users($user_id)
    {
        $dbh = sqldb_connection::DB_connect();
        $sth = $dbh->prepare("SELECT user_id, name, surname, sex, 
                              multi_photo, balance, online_status, rate
                              FROM user 
                              WHERE user_id != :user_id");
        $sth->execute(array(':user_id' => $user_id));
        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }

//Функция для выбора одиночного просмотра
    public static function Select_Single_View_user($user_id_select)
    {
        /*user_id, name, surname, sex, single_photo, balance,
        online_status, rate, last_visit, country, city, reg_date*/

        $dbh = sqldb_connection::DB_connect();
        $sth = $dbh->prepare("SELECT *
        FROM user 
        WHERE user_id = :user_id_select");
        $sth->execute(array(':user_id_select' => $user_id_select));//
        return $sth->fetch(PDO::FETCH_ASSOC);
    }

    /*user_id, name, surname, sex, single_photo, balance,
                online_status, rate, last_visit, country, city, reg_date*/
//Функция для выборки списка друзей
    public static function Select_Multi_View_friends($user_id)
    {
        $dbh = sqldb_connection::DB_connect();
        $sth = $dbh->prepare("SELECT u.user_id, u.name, 
        u.surname, u.sex, u.multi_photo, 
        u.balance, u.online_status, u.rate
        FROM user u 
        INNER JOIN friends f 
        ON (f.user_id_1 = u.user_id OR f.user_id_2 = u.user_id) 
        AND u.user_id != :user_id AND f.friend_request = TRUE
        AND (f.user_id_1 = :user_id OR f.user_id_2 = :user_id)
        LIMIT 50");
        $sth->execute(array(':user_id' => $user_id));
        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }

//Функция для выборки списка друзей онлайн
    public static function Select_Multi_View_friends_online($user_id)
    {
        $dbh = sqldb_connection::DB_connect();
        $sth = $dbh->prepare("SELECT u.user_id, u.name, u.surname, u.sex, u.multi_photo, u.balance, u.online_status, u.rate
        FROM user u 
        INNER JOIN friends f 
        ON (f.user_id_1 = u.user_id OR f.user_id_2 = u.user_id) 
        AND u.user_id != :user_id AND f.friend_request = TRUE 
        AND (f.user_id_1 = :user_id OR f.user_id_2 = :user_id)
        AND u.online_status = TRUE
        LIMIT 50");
        $sth->execute(array(':user_id' => $user_id));
        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }

//Функция для выбора по поисковому запросу
    public static function Select_Search($user_id, $query)
    {
        $dbh = sqldb_connection::DB_connect();
        $sth = $dbh->prepare("SELECT user_id, name, surname, sex, multi_photo, balance, online_status, rate
        FROM user 
        WHERE user_id != :user_id 
        AND (name LIKE :query OR surname LIKE :query) 
        LIMIT 50");
        $sth->execute(array(':user_id' => $user_id, ':query' => "%$query%"));
        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }

//Удаление из списка друзей
    public static function Update_Friendship_Cancel($user_id, $user_id_friend)
    {
        $dbh = sqldb_connection::DB_connect();
        $sth = $dbh->prepare("UPDATE friends SET friend_request = FALSE, friendship_date = NOW()
        WHERE (user_id_1 = :user_id AND user_id_2 = :user_id_friend) 
        OR (user_id_2 = :user_id AND user_id_1 = :user_id_friend)");
        return $sth->execute(array(':user_id' => $user_id, ':user_id_friend' => $user_id_friend));
    }

//Проверка на успешность запроса
    public static function Select_Friendship($user_id, $user_id_friend)
    {
        $dbh = sqldb_connection::DB_connect();
        $sth = $dbh->prepare("SELECT user_id_1, user_id_2, friend_request
        FROM friends WHERE user_id_1 = :user_id AND user_id_2 = :user_id_friend 
        LIMIT 50");
        $sth->execute(array(':user_id' => $user_id, ':user_id_friend' => $user_id_friend));
        return $sth->fetch(PDO::FETCH_ASSOC);
    }

//Отправка заявки
    public static function Insert_Friendship_Request($user_id, $user_id_friend)
    {
        $dbh = sqldb_connection::DB_connect();
        $sth = $dbh->prepare("INSERT IGNORE INTO friends (user_id_1, user_id_2, friend_request, friendship_date) 
        VALUES (:user_id, :user_id_friend, FALSE, NOW())");
        $sth->execute(array(':user_id' => $user_id, ':user_id_friend' => $user_id_friend));
    }

//Отмена заявки
    public static function Delete_Friendship_Request_Cancel($user_id, $user_id_friend)
    {
        $dbh = sqldb_connection::DB_connect();
        $sth = $dbh->prepare("DELETE FROM friends
        WHERE (user_id_1 = :user_id AND user_id_2 = :user_id_friend) 
        OR (user_id_2 = :user_id AND user_id_1 = :user_id_friend)");
        return $sth->execute(array(':user_id' => $user_id, ':user_id_friend' => $user_id_friend));
    }

//Приём заявки
    public static function Update_Friendship_Request_Agree($user_id, $user_id_friend)
    {
        $dbh = sqldb_connection::DB_connect();
        $sth = $dbh->prepare("UPDATE friends SET friend_request = TRUE, friendship_date = NOW()
        WHERE (user_id_1 = :user_id AND user_id_2 = :user_id_friend) 
        OR (user_id_2 = :user_id AND user_id_1 = :user_id_friend)");
        return $sth->execute(array(':user_id' => $user_id, ':user_id_friend' => $user_id_friend));
    }

//Функция для выборки списка заявок входящие
    public static function Select_Multi_View_Requests_Input($user_id)
    {
        $dbh = sqldb_connection::DB_connect();
        $sth = $dbh->prepare("SELECT u.user_id, u.name, u.surname,
 u.sex, u.multi_photo, u.balance, u.online_status, u.rate 
                                    FROM user u 
                                    INNER JOIN friends f 
                                    ON f.user_id_1 = u.user_id 
                                    AND f.user_id_2 = :user_id 
                                    AND u.user_id != :user_id 
                                    AND f.friend_request = FALSE");
        $sth->execute(array(':user_id' => $user_id));
        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }

//Функция для выборки списка заявок исходящие
    public static function Select_Multi_View_Requests_Output($user_id)
    {
        $dbh = sqldb_connection::DB_connect();
        $sth = $dbh->prepare("SELECT u.user_id, u.name, u.surname, u.sex, u.multi_photo, u.balance, u.online_status, u.rate 
                                    FROM user u 
                                    INNER JOIN friends f 
                                    ON f.user_id_2 = u.user_id 
                                    AND f.user_id_1 = :user_id 
                                    AND u.user_id != :user_id 
                                    AND f.friend_request = FALSE");
        $sth->execute(array(':user_id' => $user_id));
        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }

    //Evgeniy v Illye
    public static function Frends_test($user_id, $user_id_select){
        $dbh = sqldb_connection::DB_connect();
        $sth = $dbh->prepare("SELECT * from friends WHERE user_id_1 = :user_id AND user_id_2 = :user_id_select
                             OR user_id_2 = :user_id AND user_id_1 = :user_id_select");
        $sth->execute(array(':user_id' => $user_id, ':user_id_select' =>  $user_id_select));
        return $sth->fetch(PDO::FETCH_ASSOC);
    }

    /*
     *
     * Vlad
     */

    /*
     * Добавить продукт
     */
    public static function IS_add_product($product_name, $category, $price,
                                          $user_id, $buyer_id, $status, $made_in,
                                          $description, $add_date, $product_country,
                                          $product_city)
    {
        $dbh = sqldb_connection::DB_connect();
        $sth = $dbh->prepare("INSERT INTO product(product_name,category,price,owner_id,buyer_id,status,made_in,
                                description,add_date,product_country,product_city)
                             VALUES(:product_name, :category, :price, :user_id, :buyer_id, :status, :made_in, :description, :add_date,
                              :product_country, :product_city)");
        $sth->execute(array(':product_name' => $product_name, ':category' => $category, ':price' => $price,
            ':user_id' => $user_id, ':buyer_id' => $buyer_id, ':status' => $status, ':made_in' => $made_in,
            ':description' => $description, ':add_date' => $add_date, ':product_country' => $product_country,
            ':product_city' => $product_city));
        $sth = $dbh->prepare("SELECT product_id FROM product WHERE product_name = :product_name AND category = :category AND
                        price = :price AND owner_id = :user_id AND buyer_id = :buyer_id AND status = :status AND
                        made_in = :made_in AND description = :description AND add_date = :add_date AND
                        product_country = :product_country AND product_city = :product_city");
        $sth->execute(array(':product_name' => $product_name, ':category' => $category, ':price' => $price,
            ':user_id' => $user_id, ':buyer_id' => $buyer_id, ':status' => $status, ':made_in' => $made_in,
            ':description' => $description, ':add_date' => $add_date, ':product_country' => $product_country,
            ':product_city' => $product_city));
        return $sth->fetchAll(PDO::FETCH_ASSOC)[0]['product_id'];

    }


    public static function Product_photo($product_id)
    {
        $dbh = sqldb_connection::DB_connect();
        $photo_path = "http://37.57.92.40/trustme/class/picture/product_photo/";
        $sth = $dbh->prepare("INSERT INTO productgallery 
                            (product_id, pt_large_photo, pt_medium_photo , pt_small_photo )
                             VALUES ( :product_id, :large, :medium, :small)");
        $sth->execute(array(':product_id' => $product_id, ':large' => $photo_path . 'id' . $product_id . "_large.jpeg",
            ':medium' => $photo_path . 'id' . $product_id . "_medium.jpeg",
            ':small' => $photo_path . 'id' . $product_id . "_small.jpeg"));
    }

    public static function Product_photo_update($product_id)
    {
        $dbh = sqldb_connection::DB_connect();
        $photo_path = "http://37.57.92.40/trustme/class/picture/product_photo/";
        $sth = $dbh->prepare("UPDATE productgallery SET pt_large_photo = :large,
                                pt_medium_photo = :medium,
                                pt_small_photo = :small
                                WHERE product_id = :product_id");
        $sth->execute(array(':product_id' => $product_id, ':large' => $photo_path . 'id' . $product_id . "_large.jpeg",
            ':medium' => $photo_path . 'id' . $product_id . "_medium.jpeg",
            ':small' => $photo_path . 'id' . $product_id . "_small.jpeg"));
    }

    /*
     * Функция для обновления СТАТУСА ТОВАРА на active, sold, disable
     */
    public static function Update_product_status($product_id, $status)
    {
        $dbh = sqldb_connection::DB_connect();
        $sth = $dbh->prepare("UPDATE product
                                    SET status = :status
                                    WHERE product_id = :product_id");
        $sth->execute(array(':status' => $status, ':product_id' => $product_id));;
    }

    /*
     * Получить ID овнера и байера если он покупатель или владелец
     * */
    public static function Get_owner_buyer($user_id)
    {
        $dbh = sqldb_connection::DB_connect();
        $sth = $dbh->prepare("SELECT owner_id, buyer_id
                                    FROM product
                                    WHERE owner_id = :user_id
                                    OR buyer_id = :user_id");
        $sth->execute(array(':user_id' => $user_id));;
        return $sth->fetch(PDO::FETCH_ASSOC);
    }

    /*
     *  функция для удаления продукта
     * */
    public static function Delete_product($product_id)
    {
        $dbh = sqldb_connection::DB_connect();
        $sth = $dbh->prepare("DELETE FROM product
                                    WHERE product_id = :product_id  ");
        $sth->execute(array(':product_id' => $product_id));
    }

    /*
    * Функция для выборки данных О ТОВАРЕ для пользователя
    * */
    public static function Show_product_singleview($product_id)
    {
        $dbh = sqldb_connection::DB_connect();
        $sth = $dbh->prepare("SELECT p.product_id, p.product_name, p.category, p.price, p.owner_id, p.buyer_id, p.status, 
                                    p.made_in, p.description, p.add_date, p.max_bid, p.min_bid,
                                    p.auction_end, p.product_country, p.product_city
                                    FROM product p                                    
                                    WHERE p.product_id = :product_id  ");
        //Влад, смотри, указывай и имя первой таблицы, и второй. INNER JOIN productgallery pg ON pg.product_id = p.product_id
        //FROM product_photo p INNER JOIN productgallery pg
        // у тебя в запросе WHERE product_id - конфликт имен // окей, понял - принял
        $sth->execute(array(':product_id' => $product_id));
        return $sth->fetch(PDO::FETCH_ASSOC);
    }
    /*
   * Функция для выборки данных О ТОВАРЕ для пользователя
   * */
    public static function Show_product_multiview($product_id)
    {
        $dbh = sqldb_connection::DB_connect();
        $sth = $dbh->prepare("SELECT p.product_id, p.product_name, p.owner_id, p.price,
            p.status, a.bid_date, p.auction_end, pg.pt_medium_photo
                                    FROM product p
                                    INNER JOIN auction a
                                    INNER JOIN productgallery pg
                                    WHERE p.product_id = :product_id"); // показать первые 50 товаров
        $sth->execute(array(':product_id' => $product_id));
        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }
    /*
     * Закидываем товар на аукцион
     * */
    public static function Product_to_auction($product_id, $user_id, $price, $bid_date)
    {
        $dbh = sqldb_connection::DB_connect();
        $sth = $dbh->prepare("INSERT INTO auction(product_id, user_id, user_bid, bid_date)
                                    VALUES (:product_id, :user_id, :user_bid, :bid_date)");
        // тут был джоин который непонятно зачем тут был
        $sth->execute(array(':product_id' => $product_id, ':user_id' => $user_id, ':user_bid' => $price, ':bid_date' => $bid_date));
        return $sth->fetchAll(PDO::FETCH_ASSOC); // возврат для проверки статуса
    }

    /*
     * Выборка ID и PRICE для товара, для отправки товара на аукцион
     * */
    public static function Lot_Helper($product_id)
    {
        $dbh = sqldb_connection::DB_connect();
        $sth = $dbh->prepare("SELECT owner_id, price
                                    FROM product
                                    WHERE product_id = :product_id  ");
        $sth->execute(array(':product_id' => $product_id));
        return $sth->fetchAll(PDO::FETCH_ASSOC)[0];
    }

    /*
     * Выборка по поиску товаров, первые 50
     * */
    public static function Product_Search($product_id, $query)
    {
        $dbh = sqldb_connection::DB_connect();
        $sth = $dbh->prepare("SELECT p.product_id, p.product_name,p.category, p.price, p.status, p.made_in, p.description,
                                   p.product_country, p.product_city, pg.pt_small_photo
                                   FROM product p
                                   INNER JOIN productgallery pg
                                   WHERE p.product_id != :product_id
                                   AND (product_name LIKE :query OR category LIKE :query) LIMIT 50");
        $sth->execute(array(':product_id' => $product_id, ':query' => "%$query%"));
        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }

    /*
     * Редактирование товара
     * */
    public static function Product_Edit($product_id, $product_name, $category, $price, $made_in, $description, $product_country, $product_city)
    {
        $dbh = sqldb_connection::DB_connect();
        $sth = $dbh->prepare("UPDATE product
                                    SET product_name = :product_name, category = :category,
                                     price = :price, made_in = :made_in, description = :description,
                                    add_date = NOW(), product_country = :product_country,
                                    product_city = :product_city
                                    WHERE product_id = :product_id");
        $sth->execute(array(':product_id' => $product_id,
            ':product_name' => $product_name,
            ':category' => $category,
            ':price' => $price,
            ':made_in' => $made_in,
            ':description' => $description,
            ':product_country' => $product_country,
            ':product_city' => $product_city));
    }

    /*
     * List product_photo by category выборка
     * */
    public static function Get_list_product($category)
    {
        $dbh = sqldb_connection::DB_connect();
        $sth = $dbh->prepare("SELECT p.product_id, p.product_name, p.price, p.owner_id, p.buyer_id,
                                    p.status, p.made_in, p.description, p.add_date, p.max_bid,
                                     p.min_bid, p.auction_end, p.product_country, p.product_city,
                                     pg.pt_medium_photo
                                    FROM product p 
                                    INNER JOIN productgallery pg 
                                    ON pg.product_id = p.product_id
                                    WHERE category = :category");
        $sth->execute(array(':category' => $category));
        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }

    /*
       * Get MY list product_photo by category
       * */
    public static function Get_list_my_product($user_id)
    {
        $dbh = sqldb_connection::DB_connect();
        $sth = $dbh->prepare("SELECT p.product_id, p.product_name, p.price, p.owner_id, p.buyer_id,
                                    p.status, p.made_in, p.description, p.add_date, p.max_bid,
                                     p.min_bid, p.auction_end, p.product_country, p.product_city,
                                     pg.pt_medium_photo
                                    FROM product p 
                                    INNER JOIN productgallery pg
                                    ON pg.product_id = p.product_id
                                    WHERE owner_id = :user_id");
        $sth->execute(array(':user_id' => $user_id));
        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }

    /*
        * Get MY list product_photo by category
        * */
    public static function Get_list_orders($user_id)
    {
        $dbh = sqldb_connection::DB_connect();
        $sth = $dbh->prepare("SELECT p.product_id, p.product_name, p.price, p.owner_id, p.buyer_id,
                                    p.status, p.made_in, p.description, p.add_date, p.max_bid,
                                     p.min_bid, p.auction_end, p.product_country, p.product_city,
                                     pg.pt_medium_photo
                                    FROM product p 
                                    INNER JOIN productgallery pg
                                    ON pg.product_id = p.product_id
                                    WHERE buyer_id = :user_id OR owner_id = :user_id");
        $sth->execute(array(':user_id' => $user_id));
        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function Add_favourite_product($user_id, $product_id, $add_date)
    {
        $dbh = sqldb_connection::DB_connect();
        $sth = $dbh->prepare("INSERT INTO favourite_product (user_id, product_id, favourite_date) VALUE 
                                           (:user_id,:product_id,:add_date)");
        $sth->execute(array(':user_id' => $user_id, ':product_id' => $product_id, ':add_date' => $add_date));
        return $sth->fetchAll(PDO::FETCH_ASSOC); // возврат для проверки статуса
    }

    public static function Get_list_favourite($user_id)
    {
        $dbh = sqldb_connection::DB_connect();
        $sth = $dbh->prepare("SELECT p.product_name, p.price, p.owner_id, p.buyer_id,
                                    p.status, p.made_in, p.description, p.add_date, p.max_bid,
                                    p.min_bid, p.auction_end, p.product_country, p.product_city,
                                    pg.pt_small_photo
                                    FROM product p 
                                    INNER JOIN productgallery pg ON pg.product_id = p.product_id
                                    INNER JOIN favourite_product fp ON fp.product_id = p.product_id
                                    AND fp.user_id = p.product_id
                                    WHERE fp.user_id = :user_id");
        $sth->execute(array(':user_id' => $user_id));
        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }

    /*
  * Dima
  */
    /*
     * Функция для создания лота на аукционе и в базе
     */
    public static function bid_create($product_id, $user_id, $user_bid, $bid_date)
    {
        $dbh = sqldb_connection::DB_connect();
        $sth = $dbh->prepare("INSERT INTO auction(product_id, user_id, user_bid, bid_date)
                              VALUES(:product_id, :user_id, :user_bid, :bid_date)");
        $sth->execute(array(':product_id' => $product_id, ':user_id' => $user_id, ':user_bid' => $user_bid, ':bid_date' => $bid_date));
    }

    /*
     * Функция для удаления лота с аукциона и из базы
     */
    public static function bid_remove($product_id)
    {
        $dbh = sqldb_connection::DB_connect();
        $sth = $dbh->prepare("DELETE FROM auction WHERE product_id = :product_id");
        $sth->execute(array(':product_id' => $product_id));
    }

    /*
     * Запрос статуса продукта (active)
     * */

    /*
    * Функция добавления Buyer'a в случае успешной продажи + время окончания торгов
    * Уже сделал Влад
    */
    public static function lot_sold()
    {


    }

    /*
    * Выбрать все ставки по id пользователя Multi view
    */
    public static function select_multi_view_bids_by_user($user_id)
    {
        $dbh = sqldb_connection::DB_connect();
        $sth = $dbh->prepare("SELECT a.product_id, a.user_bid, a.bid_date, pg.pt_small_photo, p.product_name, p.price,
                                      p.max_bid, p.auction_end
                                FROM auction a 
                                INNER JOIN product p ON a.product_id = p.product_id 
                                INNER JOIN productgallery pg ON a.product_id = pg.product_id
                                WHERE a.user_id = :user_id");
        $sth->execute(array(':user_id' => $user_id));
        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }

    /*
   * Отображение ставки по id пользователя и id auction Single view
   */
    public static function select_single_view_bids_by_user($user_id)
    {
        $dbh = sqldb_connection::DB_connect();
        $sth = $dbh->prepare("SELECT a.product_id, a.user_bid, a.bid_date, pg.pt_large_photo, p.product_name, p.category,
                                      p.price, p.made_in, p.description, p.add_date, p.auction_qouta,
                                      p.max_bid, p.min_bid, p.auction_end, p.product_country, p.product_city
                                FROM auction a 
                                INNER JOIN product p ON a.product_id = p.product_id 
                                INNER JOIN productgallery pg ON a.product_id = pg.product_id
                                WHERE a.user_id = :user_id");
        $sth->execute(array(':user_id' => $user_id));
        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }

    /*
     * Выбрать все ставки по id лота Multi view
     */
    public static function select_multi_view_bids_by_lot($product_id)
    {
        $dbh = sqldb_connection::DB_connect();
        $sth = $dbh->prepare("SELECT a.product_id, a.user_bid, a.bid_date, pg.pt_small_photo, p.product_name, p.price,  
                                      p.max_bid, p.auction_end
                                FROM auction a 
                                INNER JOIN product p ON a.product_id = p.product_id 
                                INNER JOIN productgallery pg ON a.product_id = pg.product_id
                                WHERE p.product_id = :product_id");
        $sth->execute(array(':product_id' => $product_id));
        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }

    /*
     * Выбрать все ставки по id лота Single view
     */
    public static function select_single_view_bids_by_lot($product_id)
    {
        $dbh = sqldb_connection::DB_connect();
        $sth = $dbh->prepare("SELECT a.product_id, a.user_bid, a.bid_date, pg.pt_large_photo, p.product_name, p.category,
                                      p.price, p.made_in, p.description, p.add_date, p.auction_qouta,
                                      p.max_bid, p.min_bid, p.auction_end, p.product_country, p.product_city
                                FROM auction a 
                                INNER JOIN product p ON a.product_id = p.product_id 
                                INNER JOIN productgallery pg ON a.product_id = pg.product_id
                                WHERE p.product_id = :product_id");
        $sth->execute(array(':product_id' => $product_id));
        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }

}

