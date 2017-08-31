<?php

require_once __DIR__.'/../model/Product.php';
require_once  __DIR__.'/../phpunit-5.7.19.phar';
/**
 * Created by PhpStorm.
 * User: b0dun
 * Date: 24.04.2017
 * Time: 14:20
 */
class ProductTest extends PHPUnit_Framework_TestCase
{
    /*                                      ADD PRODUCT                              */
    /**
     * @test
     * @dataProvider product_add_perfect_providerPower
     */
    public function test_add_product_perfect($u_id, $p_name, $p_category, $p_price, $p_made_in, $p_description, $p_country, $p_city, $p_photo)
    {
        $product = new Product();
        $this->assertEquals(117, $product->Add_product($u_id, $p_name, $p_category, $p_price, $p_made_in, $p_description, $p_country, $p_city, $p_photo)['product_id']);
    }

    public function product_add_perfect_providerPower()
    {
        return array(
            array(1, 'machine', 'texnika', 10000, 'Italy', 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa', 'ukraine', 'dnipro', ''),
        );
    }

    /**
     * @test
     * @dataProvider product_add_errors_providerPower
     */
    public function test_add_product_errors($u_id, $p_name, $p_category, $p_price, $p_made_in, $p_description, $p_country, $p_city, $p_photo, $message)
    {
        $product = new Product();
        $this->assertEquals($message, $product->Add_product($u_id, $p_name, $p_category, $p_price, $p_made_in, $p_description, $p_country, $p_city, $p_photo));
    }

    public function product_add_errors_providerPower()
    {
        $errArray = array('Incorrect product name','Incorrect product category','Incorrect product made in','Incorrect product description','Incorrect product country','Incorrect product city','Incorrect product price');
        return array(
            array(1, '', '', '', '', '', '', '', '', $errArray[0]),
            array(1, '', 'texnika', 10000, 'Italy', 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa', 'ukraine', 'dnipro', '', $errArray[0]),
            array(1, 'machine', '', 10000, 'Italy', 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa', 'ukraine', 'dnipro', '', $errArray[1]),
            array(1, 'machine', 'texnika', '', 'Italy', 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa', 'ukraine', 'dnipro', '', $errArray[6]),
            array(1, 'machine', 'texnika', 10000, '', 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa', 'ukraine', 'dnipro', '', $errArray[2]),
            array(1, 'machine', 'texnika', 10000, 'Italy', '', 'ukraine', 'dnipro', '', $errArray[3]),
            array(1, 'machine', 'texnika', 10000, 'Italy', 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa', '', 'dnipro', '', $errArray[4]),
            array(1, 'machine', 'texnika', 10000, 'Italy', 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa', 'ukraine', '', '', $errArray[5])
        );
    }
    /*                                      PRODUCT DELETE                             */
    /**
     * @test
     * @dataProvider product_delete_providerPower
     */
    public function test_product_delete($p_id,$message){
        $product = new Product();
        $this->assertEquals($message,$product->Product_delete($p_id));
    }

    public function product_delete_providerPower()
    {
        $arr = array('Product successfully deleted','Failed to delete product');
        return array(
            array(174,$arr[0]),
            array(175,$arr[0]),
            array(176,$arr[0]),
        );
    }
    /*                                      PRODUCT TO LOT                              */

    /**
     * @test
     */
    public function test_product_to_lot_perfect(){
        $arr = 'active';
        $product = new Product();
        $arrP_2_lot = array($product->Product_to_lot(42));
        $this->assertEquals($arr,$arrP_2_lot[0]['status']);
    }

    /**
     * @test
     */
    public function  test_product_to_lot_error(){
        $err = array('Failed to up product in auction');
        $product = new Product();
        $this->assertEquals($err,$product->Product_to_lot(null));
    }

    /*                                      EDIT PRODUCT                               */

    /**
     * @test
     * @dataProvider product_edit_providerPower
     */
    public function test_product_edit_perfect($product_id, $product_name, $category, $price, $made_in, $description, $product_country, $product_city, $product_photo, $message)
    {
        $product = new Product();
        $this->assertEquals($message, $product->Product_edit($product_id, $product_name, $category, $price, $made_in, $description, $product_country, $product_city, $product_photo));
    }

    public function product_edit_providerPower()
    {
        $message = 'Product_update';
        return array(
            array(42, 'machine', 'texnika', 10000, 'Italy', 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa', 'ukraine', 'dnipro', '', $message),
        );
    }
    /**
     * @test
     * @dataProvider product_edit_errors_providerPower
     */

    public function test_product_edit_errors_0($p_id, $p_name, $p_category, $p_price, $p_made_in, $p_description, $p_country, $p_city, $p_photo, $message)
    {
        $product = new Product();
        $this->assertEquals($message, $product->Product_edit($p_id, $p_name, $p_category, $p_price, $p_made_in, $p_description, $p_country, $p_city, $p_photo));
    }

    public function product_edit_errors_providerPower()
    {
        $errArray = array('Incorrect product name','Incorrect product category','Incorrect product made in','Incorrect product description','Incorrect product country','Incorrect product city','Incorrect product price');
        return array(
            array(1, '', '', '', '', '', '', '', '', $errArray[0]),
            array(1, '', 'texnika', 10000, 'Italy', 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa', 'ukraine', 'dnipro', '', $errArray[0]),
            array(1, 'machine', '', 10000, 'Italy', 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa', 'ukraine', 'dnipro', '', $errArray[1]),
            array(1, 'machine', 'texnika', '', 'Italy', 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa', 'ukraine', 'dnipro', '', $errArray[6]),
            array(1, 'machine', 'texnika', 10000, '', 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa', 'ukraine', 'dnipro', '', $errArray[2]),
            array(1, 'machine', 'texnika', 10000, 'Italy', '', 'ukraine', 'dnipro', '', $errArray[3]),
            array(1, 'machine', 'texnika', 10000, 'Italy', 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa', '', 'dnipro', '', $errArray[4]),
            array(1, 'machine', 'texnika', 10000, 'Italy', 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa', 'ukraine', '', '', $errArray[5])
        );
    }
    /*                                      OWNER BUYER STATUS                               */
    /**
     * @test
     */
    public function test_owner_buyer_status(){
        $status = 'owner';
        $product = new Product();
        $this->assertEquals($status,$product->Owner_buyer_status(10));
    }

    /**
     * @test
     */
    public function test_owner_buyer_status_error(){
        $err = array('Failed to get owner/buyer status');
        $product = new Product();
        $this->assertEquals($err,$product->Owner_buyer_status(null));
    }

    /*                                      LIST PRODUCT                                */
    /**
     * @test
     * @dataProvider list_product_perfect_providerPower
     */
    public function test_list_product_perfect($response){;
        $product = new Product();
        for($i = 0; $i == 2; $i++)
        {
            $this->assertEquals($response, $product->List_product('televisors')[$i]);
        }
    }

    public function list_product_perfect_providerPower()
    {
        $response_Text = array(
            array('product_id'=>'2','product_name'=>'Kettle','price'=>'500','owner_id'=>'2','buyer_id'=>'6','status'=>'disable','made_in'=>'china','description'=>'here_must_be_description','add_date'=>'2017-04-18 23:41:51','max_bid'=>null,'min_bid'=>null,'auction_end'=>null,'product_country'=>'ukraine','product_city'=>'dnipro','pt_medium_photo'=>'http://37.57.92.40/trustme/picture/product_photo/id42_medium.jpeg'),
            array('product_id'=>'29','product_name'=>'Sony','price'=>'120','owner_id'=>'14','buyer_id'=>'0','status'=>'disable','made_in'=>'japan','description'=>'made of wool','add_date'=>'2017-04-21 02:16:53','max_bid'=>null,'min_bid'=>null,'auction_end'=>null,'product_country'=>'ukraine','product_city'=>'dnepr','pt_medium_photo'=>'http://37.57.92.40/trustme/picture/product_photo/id42_medium.jpeg'),
            array('product_id'=>'30','product_name'=>'Phillips','price'=>'220','owner_id'=>'1','buyer_id'=>'0','status'=>'disable','made_in'=>'japan','description'=>'made of wool','add_date'=>'2017-04-21 02:20:23','max_bid'=>null,'min_bid'=>null,'auction_end'=>null,'product_country'=>'ukraine','product_city'=>'dnepr','pt_medium_photo'=>'http://37.57.92.40/trustme/picture/product_photo/id42_medium.jpeg')
        );
        return array(
            array($response_Text[0]),
            array($response_Text[1]),
            array($response_Text[2]),
        );
    }

    /**
     * @test
     */
    public function test_list_product_error(){
        $errArr = array("Failed to show list product_photo");
        $product = new Product();
        $arr_list_product = array($product->List_product(''));
        $this->assertEquals($errArr,$arr_list_product[0]);
    }

    /*                               LIST_MY_PRODUCT                                */
    /**
     * @test
     * @dataProvider list_my_product_perfect_providerPower
     */
    public function test_list_my_product_perfect($response){;
        $product = new Product();
        $this->assertEquals($response, $product->List_my_product('10')[0]);
    }

    public function list_my_product_perfect_providerPower()
    {
        $response_Text = array(
            array('product_id'=>'42','product_name'=>'machine','price'=>'10000','owner_id'=>'10','buyer_id'=>'0','status'=>'active','made_in'=>'Italy','description'=>'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa','add_date'=>'2017-05-04 14:21:11','max_bid'=>null,'min_bid'=>null,'auction_end'=>null,'product_country'=>'ukraine','product_city'=>'dnipro','pt_medium_photo'=>'http://37.57.92.40/trustme/picture/product_photo/id42_medium.jpeg'),
        );
        return array(
            array($response_Text[0])
        );
    }

    /**
     * @test
     */
    public function test_list_my_product_error(){
        $errArr = array("Failed to show list product_photo");
        $product = new Product();
        $arr_list_product = array($product->List_my_product(''));
        $this->assertEquals($errArr,$arr_list_product[0]);
    }

    /*                                      LIST ORDERS                               */
    /**
     * @test
     * @dataProvider list_orders_perfect_providerPower
     */
    public function test_list_orders_perfect($response){;
        $product = new Product();
        $this->assertEquals($response, $product->List_orders('10')[0]);
    }

    public function list_orders_perfect_providerPower()
    {
        $response_Text = array(
            array('product_id'=>'42','product_name'=>'machine','price'=>'10000','owner_id'=>'10','buyer_id'=>'0','status'=>'active','made_in'=>'Italy','description'=>'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa','add_date'=>'2017-05-04 14:21:11','max_bid'=>null,'min_bid'=>null,'auction_end'=>null,'product_country'=>'ukraine','product_city'=>'dnipro','pt_medium_photo'=>'http://37.57.92.40/trustme/picture/product_photo/id42_medium.jpeg'),
        );
        return array(
            array($response_Text[0])
        );
    }

    /**
     * @test
     */
    public function test_list_orders_error(){
        $errArr = array("Failed to show list product_photo");
        $product = new Product();
        $arr_list_product = array($product->List_orders(null));
        $this->assertEquals($errArr,$arr_list_product[0]);
    }
    /*                                      ADD TO FAVOURITE PRODUCT                               */
    /**
     * @test
     */
    public function test_add_2_favourite_perfect(){;
        $arr = array('product_id' => '28','product_name' => 'Armchair','category' => 'home','price' => '120','owner_id' => '14','buyer_id' => '0','status' => 'disable','made_in' => 'spain','description' => 'made of wool','add_date' => '2017-04-21 02:14:32','max_bid' => null,'min_bid' => null,'auction_end' => null,'product_country' => 'ukraine','product_city' => 'kharkiv');
        $product = new Product();
        $this->assertEquals($arr, $product->Add_to_favourite_product(14, 28));
    }

    /**
     * @test
     * @dataProvider add_2_favourite_error_providerPower
     */
    public function test_add_2_favourite_error($user_id,$product_id){
        $errArr = array("Failed to add favourite product_photo");
        $product = new Product();
        $arr_favourite = array($product->Add_to_favourite_product($user_id,$product_id));
        $this->assertEquals($errArr,$arr_favourite[0]);
    }

    public function add_2_favourite_error_providerPower()
    {
        return array(
            array(null,null),
            array(null,'10'),
            array('10',null)
        );
    }
}
