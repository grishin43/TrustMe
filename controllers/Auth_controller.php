<?php
require "../auth_class/RAQ.php";

extract($_REQUEST);


switch (@$command) {
    case "auth": //http://37.57.92.40/trustme/controllers/auth_controller.php?command=auth&login=bodunjo855@gmail.com&password=rootttt

        if (@$email != "" && @$password != "") {
            $response = Auth(@$email, @$password);
        } else {
            $response ="null field";
        }
        break;
    case "registration_min": //http://37.57.92.40/trustme/controllers/auth_controller.php?command=reg_min&email=&phone=809503856636616&password1=rootttt&password2=rootttt

        if (@$email != "" && @$phone != "" && @$password != "") {
            $response = Registration_min(@$email, @$phone, @$password);

        } else {
            $response = "null field";
        }
        break;
    case "registration_full": //http://37.57.92.40/trustme/controllers/auth_controller.php?command=reg_full&id=1&email_2=fsdfsd@mads.ru&name=dasdasd&surname=fsdfsdfsdf&birth_day=14&birth_month=3&birth_year=1996&sex=1&country=Ucraine&city=Dnepro&photo


        if (@$id != "" && @$email_2 != "" && @$name != "" && @$surname != "" && @$birth_day != ""
            && @$birth_month != "" && @$birth_year != "" && @$sex != "" && @$country != "" && @$city != ""
        ) {
            $response = Registration_full(@$id, @$email_2, @$name, @$surname, @$birth_day,
                @$birth_month, @$birth_year, @$sex, @$country, @$city, @$user_photo);
        } else {
            $response = "null field";
        }
        break;
    case "quit":  //http://37.57.92.40/trustme/controllers/auth_controller.php?command=quit&id=1

        if (@$id != "") {
            $response = Quit(@$id);
        } else {
            $response = "null field";
        }
        break;
    case "forgot_password_email":
        $response = Password_forgot(@$email);
        break;
	case "forgot_password_phone":
		$response = Password_forgot(@$email);
		break;
    default:
        $response = "failed command";
        break;
}
logging(@$email." ".@$password." ".@$id." ".@$email_2." ".@$name." ".@$surname." ".@$birth_day
    ." ".@$birth_month." ".@$birth_year." ".@$sex." ".@$country." ".@$city, json_encode($response), @$command);

if(gettype($response) == "string"){
    $request = array('error' => $response);
    echo json_encode($request);
}
else{
    echo json_encode($response);
}
