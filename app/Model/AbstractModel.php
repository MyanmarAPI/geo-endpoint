<?php namespace App\Model;

use MongoRegex;

use Hexcores\MongoLite\Query;

use Illuminate\Pagination\LengthAwarePaginator;

/**
 * This file is part of elecapi project
 * Abstract class for application model
 *
 * @author  Hexcores Web and Mobile Studio <support@hexcores.com>
 * @package App\Model
 */
abstract class AbstractModel
{
    /**
     * Mongo lite query instance
     * @var \Hexcores\MongoLite\Query
     */
    protected $collection;

    /**
     * Mongodb connection
     * @var \Hexcores\MongoLite\Connection
     */
    protected $connection;

    /**
     * Constructor method for abstract model class
     */
    public function __construct()
    {
        $this->connection = app('connection');

        $this->collection = new Query($this->connection->collection($this->getCollectionName()));
    }

    public function getCollection()
    {
        return $this->collection;
    }

    /**
     * Get all documents from specific collection
     * @return array
     */
    public function all()
    {
        return $this->getCollection()->all();
    }

    /**
     * Get many document form specific collection
     * @param  integer $limit
     * @param  integer $offset Offset or skip
     * @return array
     */
    public function getMany($limit = 10, $offset = 0)
    {
        return $this->getCollection()->all($limit, $offset);
    }

    /**
     * Get many document with where statement
     * @param  string $key Document key
     * @param  mix $value
     * @param  array $field
     * @return array
     */
    public function getManyBy($key, $value, array $field = [])
    {
        return $this->getCollection()->where($key, '=', $value)->get($field);
    }

    /**
     * Get one document by specific key value pair
     * @param  string $key Mongo document key
     * @param  mix $value
     * @return \Hexcores\MongoLite\Document|null
     */
    public function getBy($key, $value)
    {
        return $this->getCollection()->first([$key, $value]);
    }

    /**
     * Get one document by mongo id
     * @param $id
     * @return \Hexcores\MongoLite\Document|null
     */
    public function find($id)
    {
        return $this->getCollection()->first($id);
    }

    /**
     * @return array
     */
    public function get()
    {
        return $this->getCollection()->get();
    }

    /**
     * Get count of all documents from specific collection
     * @return int
     */
    public function count()
    {
        return $this->getCollection()->count();
    }

    /**
     * Find items with like search
     * @param $key
     * @param $value
     * @param string $opt
     * @return \App\Model\AbstractModel
     */
    public function like($key, $value, $opt = 'im')
    {
        $value  = new MongoRegex('/' . $value . '/' . $opt);

        $this->collection = $this->getCollection()->where($key, '=', $value);

        return $this;
    }

    /**
     * Where keyword to find documents
     * @param $key
     * @param null $operator
     * @param null $value
     * @return \App\Model\AbstractModel
     */
    public function where($key, $operator = null, $value = null)
    {
        $this->collection = $this->getCollection()->where($key, $operator, $value);

        return $this;
    }

    /**
     * Get items in pagination form
     * @return LengthAwarePaginator
     */
    public function paginate(array $field = [])
    {
        $page = (int)app('request')->input('page', 1);
        $perPage = (int)app('request')->input('per_page', 15);
        $skip = $perPage * ($page - 1);
        
        $result = $this->getCollection()
                        ->limit($perPage)
                        ->skip($skip)
                        ->get($field);

        return new LengthAwarePaginator($result, $this->getCollection()->count(), $perPage, $page);
    }

    /**
     * Return mongo collection name to be connected
     *
     * <code>
     *     public function getCollectionName()
     *     {
     *         return 'user';
     *     }
     * </code>
     * 
     * @return string
     */
    abstract public function getCollectionName();
}
