<?php

/*
 * перед использованием этого класса необходимо создать в директории папки user_photo
 * также создать папки product_photo
 */

//http://www.cyberforum.ru/php-beginners/thread419777.html
class photo_parser
{
    /*
     * принимаем закодированную строку и юзер ID, под которым записываем фото на сервер
     */
    public static function Getpicture_from_User($decoded_string, $user_id)    {

        $decoded_string = base64_decode($decoded_string);//декодируем строку в картинку
        $path =  __DIR__.'\picture\user_photo\id'; //указание директории куда сохраняем файл

        $file = fopen($path.$user_id.'_large.jpeg', 'wb');
        fwrite($file, $decoded_string);//записываю в файл
        fclose($file);

        photo_parser::Resize_foto_small($path, $user_id);
        photo_parser::Resize_foto_medium($path, $user_id);

        sqldb_connection::User_photo_update($user_id);
    }

    public static function Getpicture_from_product($decoded_string, $product_id)
    {
        $decoded_string = base64_decode($decoded_string);//декодируем строку в картинку
        $path = __DIR__.'\picture\product_photo\id';   //указание директории куда сохраняем файл

        $file = fopen($path.$product_id.'_large.jpeg', 'wb');
        fwrite($file, $decoded_string);//записываю в файл
        fclose($file);

        photo_parser::Resize_foto_small($path, $product_id);
        photo_parser::Resize_foto_medium($path, $product_id);

    }
    //path принимает путь к файлу который нужно изменить
    //$image_name имя картинки

    function Resize_foto_small($path, $id)
    {
        $img_id = imagecreatefromjpeg($path.$id.'_large.jpeg');

        $img_width = imageSX($img_id);
        $img_height = imageSY($img_id);

        $k = round($img_width / 75, 2);

        $new_width = $img_width / $k;
        $new_height = $img_height / $k;
        $new_img = imagecreatetruecolor($new_width, $new_height);

        imagecopyresampled($new_img, $img_id, 0, 0, 0, 0,
            $new_width, $new_height, $img_width, $img_height);

        imagejpeg($new_img, $path.$id.'_small.jpeg', 100);


        imagedestroy($img_id);//чистка памяти
        imagedestroy($new_img);//чистка памяти
    }

    function Resize_foto_medium($path, $id)
    {
        $img_id = imagecreatefromjpeg($path.$id.'_large.jpeg');

        $img_width = imageSX($img_id);
        $img_height = imageSY($img_id);

        $k = round($img_width / 130, 2);

        $new_width = $img_width / $k;
        $new_height = $img_height / $k;
        $new_img = imagecreatetruecolor($new_width, $new_height);


        imagecopyresampled($new_img, $img_id, 0, 0, 0, 0,
            $new_width, $new_height, $img_width, $img_height);

        imagejpeg($new_img, $path.$id.'_medium.jpeg', 100);

        imagedestroy($img_id);
        imagedestroy($new_img);
    }

    public static function Url_encoder($string_url){
        return str_replace('\/','/',$string_url);
    }
}