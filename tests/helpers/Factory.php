<?php

/**
 * trait for Unitest
 */
trait Factory {

    /**
     * times for create a data
     * @var integer
     */
	protected $times = 1;

    /**
     * [times description]
     * @param  [int] $count
     * @return 
     */
	protected function times($count)
    {
        $this->times = $count;

        return $this;
    }

    /**
     * create test data to database
     * @param  [model] $type
     * @param  array  $fields
     * @return 
     */
    protected function make($type, $fields = [])
    {
        $type = 'App\Api\Model\\'.$type;
        while ($this->times--) {

            $stub = array_merge($this->getStub(), $fields);

            $type::create($stub);
        }
    }


    /**
     * declare for database field
     * @return [type] [description]
     */
    protected function getStub()
    {
        throw new BadMethodCallException("Create your own getStub method to declare your fields");
    }
}