<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Model\GeoModel as Geo;
use App\Transformers\GeoTransformer;

/**
* Controller for the Geo Location API Application.
*
* @package Geo Endpoint
* @license
* @author Thet Paing Oo <thetpaing@hexcores.com>
*/
class GeoController extends Controller
{
    /**
     * $model
     * @var [type]
     */
	protected $model;

    /**
     * construct method
     * @param App\Model\GeoModel  $model          
     */
	function __construct(Geo $model)
	{
		$this->model = $model;
	}


    /**
     * Index Function for geo api ( get all geo locaction of myanmar)
     * @param  Illuminate\Http\Request $request
     * @return Hexcores\Api\Facades\Response
     */
    public function district(Request $request)
    {
        $test = $this->filter($request);

        $data = $this->transform($this->filter($request), new GeoTransformer(), true);

        return response_ok($data);
    }


    public function find(Request $request)
    {
        $lat = (float) $request->input('lat');

        $long = (float) $request->input('lon');

        $data = $this->transform($this->model->findIntersects($long, $lat), new GeoTransformer(), true);
        

        return response_ok($data);
    }

    /**
     * Filter For FAQ
     * @param  Illuminate\Http\Request $request
     * @return LengthAwarePaginator
     */
    protected function filter($request)
    {
        if ($st_name = $request->input('st_name')) {

            $this->model = $this->model->like('properties.ST', $st_name);

        }

        if ($dt_name = $request->input('dt_name')) {

            $this->model = $this->model->like('properties.DT', $dt_name);

        }

        if ($object_id = $request->input('object_id')) {

            $this->model = $this->model->where('properties.OBJECTID', (int) $object_id);
        }

        if ($st_pcode = $request->input('st_pcode')) {

            $this->model = $this->model->where('properties.ST_PCODE', $st_pcode);
        }

        if ($dt_pcode = $request->input('dt_pcode')) {

            $this->model = $this->model->where('properties.DT_PCODE', $dt_pcode);
        }

        if ($noGeo = $request->input('no_geo'))
        {
            if ($noGeo == 'true') {

                return $this->model->paginate(['type','properties']);
            }
        }

        return $this->model->paginate();
    }
}
