<?php
/*
 *
 */

function get_token($code){
    $result = false;

    $params = array(
        'client_id'     => CLIENT_ID,
        'client_secret' => SECRET,
        'redirect_uri'  => REDIRECT,
        'grant_type'    => 'authorization_code',
        'code'          => $_GET['code']
    );

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, TOKEN);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, urldecode(http_build_query($params)));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

    $result = curl_exec($curl);
    if (!$result) {
        exit(curl_error($curl));
    }
    if ($i = json_decode($result)) {
        if ($i->error) {
            exit($i->error->message);

        }
    } else {
        parse_str($result, $token);
        if ($token['access_token']) {
            return $token['access_token'];
        }

    }
}

function get_data($token)
{

    $curl = curl_init();
    $query = "fields=id,email,first_name&access_token=" . $token;

    curl_setopt($curl, CURLOPT_URL, GET_DATA . "?" . $query);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);

    $result = curl_exec($curl);
    if (!$result) {
        exit(curl_error($curl));
    }

    return json_decode($result);
}