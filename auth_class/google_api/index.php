<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8"/>
    <title>Аутентификация через Google</title>
</head>
<body>

<?php
require "config.php";
require "function.php";


$params = array(
    'redirect_uri'  => REDIRECT,
    'response_type' => 'code',
    'client_id'     => CLIENT_ID,
    'scope'         => 'https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/userinfo.profile'
);

$gg = URL_AUTH . '?' . urldecode(http_build_query($params));

echo $link = '<p><a href="' . URL_AUTH . '?' . urldecode(http_build_query($params)) . '">Аутентификация через Google</a></p>';

if (isset($_GET['code'])) {
    $result = get_token($_GET['code']);

    if($result){
        $user = get_data($result);
    }

    if (isset($tokenInfo['access_token'])) {
        $params['access_token'] = $tokenInfo['access_token'];

        $userInfo = json_decode(file_get_contents(GET_DATA . '?' . urldecode(http_build_query($params))), true);
        if (isset($userInfo['id'])) {
            $userInfo = $userInfo;
            $result = true;
        }
    }

    if ($result) {
        $sql_query = "SELECT login,xo_online,chat_online,fb_id,google_id FROM clients WHERE fb_id='" . $userInfo['id'] . "'";
        $result_set = mysqli_query($h, $sql_query);
        $row = mysqli_fetch_row($result_set);

        if ($row[1] != "true") {
            if ($user->id == $row[3]) {
                $sql_query = "UPDATE clients SET xo_online = 'true', login = '".$userInfo['name']."'  WHERE google_id='" . $userInfo['id'] . "'";
                $result_set = mysqli_query($h, $sql_query);
                echo json_decode($row);

                setrawcookie('xo_auth_log', $userInfo['name'], time() + 86400, '/');
                setrawcookie('xo_auth_pass', $userInfo['id'], time() + 86400, '/');

                echo "OK";

                header('Location: ../../../client.html');
            } else {
                $sql_query = "INSERT INTO clients(login,password,email,banned,google_id,xo_online) VALUES('".$userInfo['name']."', '".$userInfo['id']."','".$userInfo['emaid']."','false','".$userInfo['id']."', 'true')";
                mysqli_query($h, $sql_query);
                echo "OK";

                setrawcookie('xo_auth_log', $userInfo['name'], time() + 86400, '/');
                setrawcookie('xo_auth_pass', $userInfo['id'], time() + 86400, '/');

                header('Location: ../../../client.html');
            }
        } else {
            echo "User online";

            header('Location: ../../../client.html');
        }

    }

}



?>

</body>
</html>
