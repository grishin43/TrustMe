<?php

require_once __DIR__.'/../model/Auction.php';
require_once  __DIR__.'/../phpunit-5.7.19.phar';

class AuctionTest extends PHPUnit_Framework_TestCase
{

    /** @test
     * @dataProvider providerMakeBid
     * */
    public function test_Make_bid($product_id, $user_id, $user_bid, $message)
    {
        $auction = new Auction();
        $this->assertEquals($message, $auction->Make_bid($product_id, $user_id, $user_bid));
    }

    public function providerMakeBid()
    {
        $array = array("Failed product id", "Failed user id", "Incorrect bid amount", "Lot created");
        return array(
            array('', '', '', $array[0]),
            array('', '', '30', $array[0]),
            array('', '1', '', $array[0]),
            array('', '1', '50', $array[0]),
            array('24', '', '', $array[1]),
            array('24', '', '40', $array[1]),
            array('24', '1', '', $array[2]),
            array('24', '1', '20', $array[3])
        );
    }


    /** @test
     * @dataProvider providerRemoveBid
     * */
    public function test_Remove_bid($product_id, $message)
    {
        $auction = new Auction();
        $this->assertEquals($message, $auction->Remove_bid($product_id));
    }

    public function providerRemoveBid()
    {
        return array(
            array(3, "Wrong lot id"),
            array('', "Wrong lot id"),
            array('asdf', "Wrong lot id")
        );
    }


    /** @test
     * @dataProvider providerPerfectShowBidsByUser
     * */
    public function test_Perfect_Show_Bids_By_User($user_id, $response)
    {
        $auction = new Auction();
        $this->assertEquals($response, $auction->Show_bids_by_user($user_id)[0]);
    }

    public function providerPerfectShowBidsByUser()
    {
        $responseText = array(
            array("product_id"=>"24","user_bid"=>"20","bid_date"=>"2017-05-04 01:05:56","pt_small_photo"=>"http://37.57.92.40/trustme/picture/product_photo/id42_small.jpeg","product_name"=>"Test","price"=>"2000","max_bid"=>null,"auction_end"=>null),
            array("product_id"=>"2","user_bid"=>"50","bid_date"=>"2017-04-16 06:04:32","pt_small_photo"=>"http://37.57.92.40/trustme/picture/product_photo/id42_small.jpeg","product_name"=>"Flower","price"=>"500","max_bid"=>null,"auction_end"=>null),
            array("product_id"=>"24","user_bid"=>"2000","bid_date"=>"2017-04-20 11:34:08","pt_small_photo"=>"http://37.57.92.40/trustme/picture/product_photo/id42_small.jpeg","product_name"=>"Test","price"=>"2000","max_bid"=>null,"auction_end"=>null)
        );
        return array(
            array(1, $responseText[0]),
            array(2, $responseText[1]),
            array(6, $responseText[2])
        );
    }

    /** @test
     * @dataProvider providerFailedShowBidsByUser
     * */
    public function test_Failed_Show_Bids_By_User($user_id, $response)
    {
        $auction = new Auction();
        $this->assertEquals($response, $auction->Show_bids_by_user($user_id));
    }

    public function providerFailedShowBidsByUser()
    {
        return array(
            array(999999, "NOTHING"),
            array(null, "Failed id"),
            array("", "Failed id")
        );
    }

    /** @test
     * @dataProvider providerPerfectShowBidsByProduct
     * */
    public function test_Perfect_Show_Bids_By_Product($product_id, $response)
    {
        $auction = new Auction();
        $this->assertEquals($response, $auction->Show_bids_by_product($product_id)[0]);
    }

    public function providerPerfectShowBidsByProduct()
    {
        $responseText = array(
            array("product_id"=>"24","user_bid"=>"2000","bid_date"=>"2017-04-20 11:34:08","pt_small_photo"=>"http://37.57.92.40/trustme/picture/product_photo/id42_small.jpeg","product_name"=>"Test","price"=>"2000","max_bid"=>null,"auction_end"=>null),
            array("product_id"=>"2","user_bid"=>"50","bid_date"=>"2017-04-16 06:04:32","pt_small_photo"=>"http://37.57.92.40/trustme/picture/product_photo/id42_small.jpeg","product_name"=>"Flower","price"=>"500","max_bid"=>null,"auction_end"=>null),
            array("product_id"=>"42","user_bid"=>"500","bid_date"=>"2017-05-03 21:41:19","pt_small_photo"=>"http://37.57.92.40/trustme/picture/product_photo/id42_small.jpeg","product_name"=>"machine","price"=>"10000","max_bid"=>null,"auction_end"=>null),
        );
        return array(
            array(24, $responseText[0]),
            array(2, $responseText[1]),
            array(42, $responseText[2]),
        );
    }

    /** @test
     * @dataProvider providerFailedShowBidsByProduct
     * */
    public function test_Failed_Show_Bids_By_Product($user_id, $response)
    {
        $auction = new Auction();
        $this->assertEquals($response, $auction->Show_bids_by_product($user_id));
    }

    public function providerFailedShowBidsByProduct()
    {
        return array(
            array(999999, "NOTHING"),
            array(null, "Failed id"),
            array("", "Failed id")
        );
    }
}

