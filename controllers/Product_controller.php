<?php
require "../model/Product.php";


extract($_REQUEST);

$product = new Product();

switch (@$command) {
    case "add_product": //http://localhost/trustme/controllers/product_controller.php?command=add_product&user_id=1&product_name=Kettle&category=home&price=500&made_in=china&description=here_must_be_description&product_country=ukraine&product_city=dnipro&product_photo=photo_link

        if (@$user_id != "" /*&& $product_name != "" && $category != "" && $price != "" && $made_in != "" && $description != "" && $product_country != ""
            && $product_city != ""*/ ) {
            $response = $product->Add_product(@$user_id, @$product_name, @$category, @$price, @$made_in, @$description,
                @$product_country, @$product_city, @$product_photo);
        } else {
            $response = "null field";
        }
        break;
    case "product_to_lot": //http://localhost/trustme/controllers/product_controller.php?command=product_to_lot&product_id=1

        if (@$product_id != "") {
            $response = $product->Product_to_lot(@$product_id);
        } else {
            $response =  "null field";
        }
        break;
    case "product_delete": //http://localhost/trustme/controllers/product_controller.php?command=product_delete&product_id=1

        if (@$product_id != "") {
            $response =  $product->Product_delete(@$product_id);
        } else {
            $response =  "null field";
        }
        break;
    case "edit_product": //http://localhost/trustme/controllers/product_controller.php?command=edit_product&user_id=1&product_id=1&product_name=Kettle&category=home&price=500&made_id=china&description=here_must_be_description&product_country=ukraine&product_city=dnipro&product_photo=here_must_be_photo

        if (@$product_id != "" /*&& $product_name != "" && $category != ""
            && $price != "" && $description != ""
            && $product_country != "" && $product_city != "" */) {
            $response = $product->Product_edit(@$user_id, @$product_id, @$product_name, @$category, @$price, @$made_in,
                @$description, @$product_country, @$product_city, @$product_photo);
        } else {
            $response = "null field";
        }
        break;
    case "product_search": //http://localhost/trustme/controllers/product_controller.php?command=product_search&product_id=1&query=kettle


        if (@$product_id != "" && @$query != "") {
            $response = $product->Product_search(@$product_id, @$query);
        } else {
            $response = "null field";
        }
        break;
    case "product_singleview": //http://localhost/trustme/controllers/product_controller.php?command=product_singleview&user_id=1&product_id=1


        if (@$user_id != "" && @$product_id != "") {
            $response = $product->Product_singleview(@$user_id, @$product_id);

        } else {
            $response = "null field";
        }
        break;
    case "product_multiview": //http://localhost/trustme/controllers/product_controller.php?command=product_multiview&user_id=1&product_id=1

        if (@$product_id != "") {
            $response = $product->Product_multiview(@$product_id);
        } else {
            $response = "null field";
        }
        break;
    case "list_product":

        if(@$category != ""){
            $response = $product->List_product(@$category);
        } else {
            $response = "null field";
        }
        break;
    case "list_my_product":

        if (@$user_id != ""){
            $response = $product->List_my_product(@$user_id);
        } else{
            $response = "null field";
        }
        break;
    case "list_orders":


        if (@$user_id != ""){
            $response = $product->List_orders(@$user_id);
        } else{
            $response = "null field";
        }
        break;
    case "add_to_favourite_product":

        if (@$user_id != "" && @$product_id != ""){
            $response = $product->Add_to_favourite_product(@$user_id, @$product_id);
        } else{
            $response = "null field";
        }
        break;
    case "list_favourite":

        if (@$user_id != ""){
            $response = $product->List_orders(@$user_id);
        } else{
            $response = "null field";
        }
        break;
    default:
        $response = "failed command";
        break;
}

if(@$product_photo){$pht = "photo";}

logging(@$user_id." ".@$product_id." ".@$product_name." ".@$category." ".@$price." ".@$made_in." ".@$description." ".
    @$product_country." ".@$product_city." ".$pht,json_encode($response),@$command);

if(gettype($response) == "string"){
    $request = array('error' => $response);
    echo json_encode($request);
}
else{
    echo json_encode($response);
}