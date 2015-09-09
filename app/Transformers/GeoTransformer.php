<?php namespace App\Transformers;

use App\Transformers\Contracts\TransformerInterface;
use League\Fractal\TransformerAbstract;

/**
* Transformer class for the Geo Location API Application.
*
* @package Geo Endpoint
* @license
* @author Thet Paing Oo <thetpaing@hexcores.com>
*/
class GeoTransformer extends TransformerAbstract implements TransformerInterface{

	public function transform($geo)
    {
        $result = [
            'id'            => (string)$geo['_id'],
            'type'          => $geo['type'],
            'properties'    => $geo['properties'],
            
        ];
        
        if (isset($geo['geometry'])) {
            
            $result['geometry'] = $geo['geometry'];
        }

    	return $result;
    }
	
}