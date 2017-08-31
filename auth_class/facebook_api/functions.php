<?php
function get_token($code)
{
    $curl = curl_init();
    $query = "client_id=" . CLIENT_ID . "&redirect_uri=" . urlencode(REDIRECT) .
        "&client_secret=" . SECRET . "&code=" . $code;

    curl_setopt($curl, CURLOPT_URL, TOKEN . "?" . $query);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

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
?>

