<?php namespace App\Model;

use Mongo;
use App\Model\AbstractModel;
use Illuminate\Support\Collection;

/**
* Model for the Upper House API Application.
*
* @package Geo Endpoint
* @license
* @author Thet Paing Oo <thetpaing@hexcores.com>
*/
class UpperHouseModel extends AbstractModel
{
    /**
     * model name
     * @return string
     */
    public function getCollectionName()
    {
        return 'upper_api';
    }

    /**
     * create upper house model
     * @param  array  $data [description]
     * @return \Hexcores\MongoLite\Document|bool
     */
    public function create(array $data)
    {
        return $this->getCollection()->insert($data);
    }

    /**
     * Find By longitude latitude with getoIntersects for UpperHouse
     * @param  string $long
     * @param  string $lat
     * @param  boolean $noGeo
     * @return array
     */
    public function findIntersects($long, $lat, $noGeo)
    {
        $this->getCollection()->collection()->ensureIndex(["geometry"=>"2dsphere"]);
        
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

    /**
     * get Single Geo Document By AM_PCODE
     * @param  string $pcode
     * @param  string $noGeo
     * @return \Hexcores\MongoLite\Document|bool
     */
    public function getByAM($pcode, $noGeo)
    {
        $field = [];

        if ($noGeo == 'true') {

            $field = ['type','properties'];
                
        }

        $data = $this->getCollection()->collection()->findOne(['properties.AM_PCODE' => $pcode],$field);
        
        return $data;
    }
}