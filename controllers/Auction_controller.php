<?php
require "../model/Auction.php";

extract($_REQUEST);

$auction = new Auction();

switch (@$command) {
    case "makeBid": //http://37.57.92.40/trustme/controllers/auction_controller.php?command=makeBid&product_id=2&user_id=2&user_bid=50

        if (@$product_id != "" && @$user_id != "" && @$user_bid != "") {

            $response = $auction->Make_bid(@$product_id, @$user_id, @$user_bid);
        } else {
            $response = "null field";
        }
        break;
    case "showBidsByUser": //http://37.57.92.40/trustme/controllers/auction_controller.php?command=showBidsByUser&user_id=2

        if (@$user_id != "") {
            $response = $auction->Show_bids_by_user(@$user_id);
        } else {
            $response = "null field";
        }
        break;
    case "showBidsByProduct": //http://37.57.92.40/trustme/controllers/auction_controller.php?command=showBidsByProduct&product_id=1

        if (@$product_id != "") {
            $response = $auction->Show_bids_by_product(@$product_id);
        } else {
            $response = "null field";
        }
        break;
    case "removeBid": //http://37.57.92.40/trustme/controllers/auction_controller.php?command=removeBid&product_id=2

        if (@$product_id != "") {
            $response = $auction->Remove_bid(@$product_id);
        } else {
            $response = "null field";
        }
        break;
    default:
        $response = "failed command";
        break;
}

logging(@$product_id." ".@$user_id." ".@$user_bid." ",json_encode($response),@$command);

if(gettype($response) == "string"){
    $request = array('error' => $response);
    echo json_encode($request);
}
else{
    echo json_encode($response);
}