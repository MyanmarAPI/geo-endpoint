<?php

use Faker\Factory as Faker;

/**
 * Api Unit Test
 */

abstract class ApiTester extends TestCase {


    protected $fake;    

    function __construct()
    {
        $this->fake = Faker::create();
    }

    /**
     * get json
     * @param  string $uri
     * @param  string $method
     * @param  array  $param
     * @return json
     */
    protected function getJson($uri, $method = 'GET', $param = [])
    {
        return json_decode($this->call($method, $uri, $param)->getContent());
    }

    /**
     * check object has attributes
     * @return [type] [description]
     */
    protected function assertObjectHasAttributes()
    {
        $args = func_get_args();

        $object = array_shift($args);

        foreach ($args as $attribute) {

            $this->assertObjectHasAttribute($attribute,$object);

        }
    }

}
