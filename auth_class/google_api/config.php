<?php
/*
 * файл с конфигурациями для авторизации через гугл
 */

define("URL_AUTH", "https://wwww.facebook.com/dialog/oauth");
define("CLIENT_ID", "782280748456-20m3038rcmn4nk9oa735bcj2a4co6iph.apps.googleusercontent.com"); // Client id
define("SECRET", "ql07ZKZ9O7606MmJxEI8vGbz");                                             // Client secret
define("REDIRECT", "http://localhost/tic.tac.toe/tic.tac.toe/msg/auth/google/index.php"); //redirect_uri
define("TOKEN", "https://accounts.google.com/o/oauth2/token");  // url token
define("GET_DATA", "https://www.googleapis.com/oauth2/v1/userinfo");  //get data