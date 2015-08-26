<?php namespace App\Model;

use Mongo;
use App\Model\AbstractModel;
use Illuminate\Support\Collection;

/**
* Model for the LowerHouse API Application.
*
* @package Geo Endpoint
* @license
* @author Thet Paing Oo <thetpaing@hexcores.com>
*/
class LowerHouseModel extends AbstractModel
{
    /**
     * model name
     * @return string
     */
    public function getCollectionName()
    {
        return 'lower_api';
    }

    /**
     * create lowerhouse model
     * @param  array  $data [description]
     * @return \Hexcores\MongoLite\Document|bool
     */
    public function create(array $data)
    {
        return $this->getCollection()->insert($data);
    }

    public function findIntersects($long, $lat)
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

        return ! is_null($cursor) ? $this->changeArray($cursor) : null;
    }
    
    public function changeArray($cursor)
    {
        $results = [];

        foreach ($cursor as $key => $value) {
            $results[] = $value;
        }

        return $results;
    }
}