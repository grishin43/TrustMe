<?php
require_once '../model/User.php';
require_once '../phpunit-5.7.19.phar';

class User_module_test extends PHPUnit_Framework_TestCase
{
    /**@test */
    //---------------------------------SINGLE VIEW USER-----------------------------------------------------
    /**
     * @dataProvider provider_failed_single_view_user
     */
    public function test_failed_single_view_user($user_id, $user_id_select, $expected)
    {
        $user = new User();
        $this->assertEquals($expected, $user->Single_view_user($user_id, $user_id_select));
    }
    public function provider_failed_single_view_user ()
    {
        $tmpArray = array("Failed id", "Failed id select");
        return array (
            array (null, 3, $tmpArray[0]),
            array (1, null, $tmpArray[1]),
            array(9999999, 99999999, 'NOTHING'),
            array(null, null, $tmpArray[0])
        );
    }
    /**
     * @dataProvider provider_perfect_single_view_user
     */
    public function test_perfect_single_view_user($user_id, $user_id_select, $expected)
    {
        $user = new User();
        $this->assertEquals($expected, $user->Single_view_user($user_id, $user_id_select)['name']);
    }
    public function provider_perfect_single_view_user ()
    {
        return array (
            array (1, 3, 'three'),
            array (1, 5, 'five'),
            array (1, 6, 'six')
        );
    }

    //---------------------------------MULTI VIEW USERS-----------------------------------------------------
    public function test_failed_multi_view_users()
    {
        $user = new User();
        $this->assertEquals("Failed id", $user->Multi_view_users(null));
    }

    /**
     * @dataProvider provider_perfect_multi_view_users_one
     */
    public function test_perfect_multi_view_users_one($expected, $user_id)
    {
        $user = new User();
        for($i = 0; $i < 9; $i++)
        {
            $this->assertEquals($expected[$i], $user->Multi_view_users($user_id)[$i]['name']);
        }
    }
    public function provider_perfect_multi_view_users_one ()
    {
        return array(
            array(array("two", "three", "four", "five", "six", "seven", "eight", "nine", "ten"), 1),
            array(array("one", "three", "four", "five", "six", "seven", "eight", "nine", "ten"), 2),
            array(array("one", "two", "four", "five", "six", "seven", "eight", "nine", "ten"), 3),
            array(array("one", "two", "three", "five", "six", "seven", "eight", "nine", "ten"), 4),
            array(array("one", "two", "three", "four", "six", "seven", "eight", "nine", "ten"), 5),
            array(array("one", "two", "three", "four", "five", "seven", "eight", "nine", "ten"), 6),
            array(array("one", "two", "three", "four", "five", "six", "eight", "nine", "ten"), 7),
            array(array("one", "two", "three", "four", "five", "six", "seven", "nine", "ten"), 8),
            array(array("one", "two", "three", "four", "five", "six", "seven", "eight", "ten"), 9),
            array(array("one", "two", "three", "four", "five", "six", "seven", "eight", "nine"), 10)
        );
    }

    //---------------------------------MULTI VIEW FRIENDS-----------------------------------------------------
    /**
     * @dataProvider provider_perfect_multi_view_friends
     */
    public function test_perfect_multi_view_friends($expected, $user_id)
    {
        $user = new User();
        for($i = 0; $i < 5; $i++)
        {
            $this->assertEquals($expected[$i], $user->Multi_view_friends($user_id)[$i]['name']);
        }
    }
    public function provider_perfect_multi_view_friends ()
    {
        return array(
            array(array("two", "three", "four", "five", "ten"), 1),
            array(array("one", "three", "four", "five", "six"), 2)
        );
    }

    /**
    * @dataProvider provider_failed_multi_view_friends
    */
    public function test_failed_multi_view_friends($expected, $user_id)
    {
        $user = new User();
        $this->assertEquals($expected, $user->Multi_view_friends($user_id));
    }
    public function provider_failed_multi_view_friends ()
    {
        return array(
            array("Failed id", null),
            array("NOTHING", 999999999)
        );
    }


    //---------------------------------MULTI VIEW FRIENDS ONLINE----------------------------------------------
    /**
     * @dataProvider provider_perfect_multi_view_friends_online
     */
    public function ALE_test_perfect_multi_view_friends_online($expected, $user_id)
    {
        $user = new User();
        for($i = 0; $i < 3; $i++)
        {
            $this->assertEquals($expected[$i], $user->Multi_view_friends_online($user_id)[$i]['name']);
        }
    }
    public function provider_perfect_multi_view_friends_online ()
    {
        return array(
            array(array("two", "five", "ten"), 1),
            array(array("one", "five", "six"), 2)
        );
    }

    /**
     * @dataProvider provider_failed_multi_view_friends_online
     */
    public function ALE_test_failed_multi_view_friends_online($expected, $user_id)
    {
        $user = new User();
        $this->assertEquals($expected, $user->Multi_view_friends_online($user_id));
    }
    public function provider_failed_multi_view_friends_online ()
    {
        return array(
            array("Failed id", null),
            array("NOTHING", 999999999)
        );
    }

    //---------------------------------SEARCH-----------------------------------------------------------------
    /**
     * @dataProvider provider_perfect_search
     */
    public function ALE_test_perfect_search($expected, $user_id, $query)
    {
        $user = new User();
        for($i = 0; $i < 3; $i++)
        {
            $this->assertEquals($expected[$i], $user->Search($user_id, $query)[$i]['name']);
        }
    }
    public function provider_perfect_search ()
    {
        return array(
            array(array("two", "three", "eight", "ten"), 1, "t"),
            array(array("five", "six", "eight", "nine"), 1, "i")
        );
    }

    /**
     * @dataProvider provider_failed_search
     */
    public function ALE_test_failed_search($expected, $user_id, $query)
    {
        $user = new User();
        $this->assertEquals($expected, $user->Search($user_id, $query));
    }
    public function provider_failed_search ()
    {
        return array(
            array("Failed id", null, "t"),
            array("NOTHING", 11, "This query does not exist")
        );
    }

    public function ALE_test_empty_query_search()
    {
        $expected = array("two", "three", "four", "five", "six", "seven", "eight", "nine", "ten");
        $user = new User();
        for ($i = 0; $i < 9; $i++)
        {
            $this->assertEquals($expected[$i], $user->Search(1, "")[$i]['name']);
        }
    }

    //---------------------------------MULTI VIEW REQUESTS OUTPUT----------------------------------------------
    /**
     * @dataProvider provider_perfect_multi_view_request_output
     */
    public function test_perfect_multi_view_request_output($expected, $user_id)
    {
        $user = new User();
        for($i = 0; $i < 2; $i++)
        {
            $this->assertEquals($expected[$i], $user->Multi_view_requests_output($user_id)[$i]['name']);
        }
    }
    public function provider_perfect_multi_view_request_output ()
    {
        return array(
            array(array("six", "seven"), 1),
            array(array("seven", "eight"), 2)
        );
    }

    /**
     * @dataProvider provider_failed_multi_view_request_output
     */
    public function test_failed_multi_view_request_output($expected, $user_id)
    {
        $user = new User();
        $this->assertEquals($expected, $user->Multi_view_requests_output($user_id));
    }
    public function provider_failed_multi_view_request_output ()
    {
        return array(
            array("Failed id", null),
            array("NOTHING", 999999999)
        );
    }

    //---------------------------------MULTI VIEW REQUESTS OUTPUT----------------------------------------------
    /**
     * @dataProvider provider_perfect_multi_view_request_input
     */
    public function test_perfect_multi_view_request_input($expected, $user_id)
    {
        $user = new User();
        for($i = 0; $i < 2; $i++)
        {
            $this->assertEquals($expected[$i], $user->Multi_view_requests_input($user_id)[$i]['name']);
        }
    }
    public function provider_perfect_multi_view_request_input ()
    {
        return array(
            array(array("eight", "nine"), 1),
            array(array("nine", "ten"), 2)
        );
    }

    /**
     * @dataProvider provider_failed_multi_view_request_input
     */
    public function test_failed_multi_view_request_input($expected, $user_id)
    {
        $user = new User();
        $this->assertEquals($expected, $user->Multi_view_requests_input($user_id));
    }
    public function provider_failed_multi_view_request_input ()
    {
        return array(
            array("Failed id", null),
            array("NOTHING", 999999999)
        );
    }
}
