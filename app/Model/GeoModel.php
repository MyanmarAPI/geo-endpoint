<?php namespace App\Model;

use Mongo;
use App\Model\AbstractModel;
use Illuminate\Support\Collection;

/**
* Model for the Geo API Application.
*
* @package Geo Endpoint
* @license
* @author Thet Paing Oo <thetpaing@hexcores.com>
*/
class GeoModel extends AbstractModel
{
    /**
     * model name
     * @return string
     */
    public function getCollectionName()
    {
        return 'geo_api';
    }

    /**
     * create geo model
     * @param  array  $data [description]
     * @return \Hexcores\MongoLite\Document|bool
     */
    public function create(array $data)
    {
        return $this->getCollection()->insert($data);
    }

    /**
     * Find By longitude latitude with getoIntersects
     * @param  string $long
     * @param  string $lat
     * @param  boolean $noGeo
     * @return array
     */
    public function findIntersects($long, $lat, $noGeo)
    {
        //$this->getCollection()->collection()->ensureIndex(["geometry"=>"2dsphere"]);
        
        $opt = [ 'geometry'  =>
                    ['$geoIntersects' => [
                        '$geometry' => [
                            'type'  => 'Point',
                             'coordinates' => [$long,$lat]  
                        ]
                     ]
                    ],
                    
                ];

        $cursor = $this->getCollection()->collection()->find($opt);

        if ($noGeo == 'true') {

            $cursor = $cursor->fields(['type' => true , 'properties' => true]);
        }

        return ! is_null($cursor) ? $this->changeArray($cursor) : null;
    }
    
    /**
     * change mongocursor to array
     * @param  MongoCursor $cursor
     * @return array
     */
    public function changeArray($cursor)
    {
        $results = [];

        foreach ($cursor as $key => $value) {
            $results[] = $value;
        }

        return $results;
    }

    /**
     * get Single Geo Document By Id
     * @param  string $id
     * @param  boolean $noGeo
     * @return \Hexcores\MongoLite\Document|bool
     */
    public function getById($id, $noGeo)
    {
        $data = $this->find($id);

        if ($noGeo == 'true') {

            $data->__unset('geometry');
                
        }

        return $data;
    }
}