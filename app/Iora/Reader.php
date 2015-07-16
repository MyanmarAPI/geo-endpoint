<?php
/**
 * This file is part of php-endpoint-bootstrap project
 * 
 * @package App\Iora
 * @author Yan Naing <yannaing@hexcores.com>
 * Date: 6/30/15
 * Time: 3:40 PM
 */

namespace App\Iora;

use App\Iora\Contract\AbstractReader;

class Reader extends AbstractReader {

    protected $model;

    protected $path;

    protected $rows = 0;

    /**
     * Constructor method for
     * @param null $path
     */
    public function __construct($path = null)
    {
        $this->path = (is_null($path)) ? storage_path('data') : $path;
    }

    /**
     * Accessor and mutator for variable model
     * @param null $model
     * @return App\Iora\Reader
     */
    public function model($model = null)
    {
        if (is_null($model))
        {
            return $this->model;
        }

        $this->model = $model;

        return $this;
    }

    /**
     * Assign path to read csv files
     * @param string $path
     * @return App\Iora\Reader $this
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Return the path to read csv files
     * @return null|string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Return the count of imported rows
     * @return int
     */
    public function getRows()
    {
        return $this->rows;
    }

    /**
     * Import data from csv files to database
     * @return \Exception
     */
    public function import()
    {
        if (is_null($this->model))
        {
            return new \Exception("No model has been set. Use model('model_name') method");
        }

        $this->insertIntoDatabase($this->render());
    }

    protected function insertIntoDatabase($data)
    {
        $keys = $data->fetchOne();

        $data->setOffset(1);
        $rows = $data->query();

        foreach ($rows as $row)
        {
            $madeData = $this->makeData($keys, $row);

            $model = '\App\Model\\' . ucfirst($this->model);

            $collection = (new $model())->getCollection();

            $collection->insert($madeData);

            $this->rows += 1;
        }
    }

    protected function makeData(array $keys, array $data)
    {
        $result = [];

        foreach ($keys as $index => $value)
        {
            $result[snake_case($value)] = $data[$index];
        }

        return $result;
    }
}