<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Model\UpperHouseModel as Upper;
use App\Transformers\GeoTransformer;

/**
* Controller for the Upper House Location API Application.
*
* @package Geo Endpoint
* @license
* @author Thet Paing Oo <thetpaing@hexcores.com>
*/
class UpperHouseController extends Controller
{
    /**
     * $model
     * @var [type]
     */
	protected $model;

    /**
     * construct method
     * @param App\Model\UpperHouseModel  $model          
     */
	function __construct(Upper $model)
	{
		$this->model = $model;
	}


    /**
     * Index Function for geo api ( get all UpperHouse locaction of myanmar)
     * @param  Illuminate\Http\Request $request
     * @return Hexcores\Api\Facades\Response
     */
    public function index(Request $request)
    {
        $data = $this->transform($this->filter($request), new GeoTransformer(), true);

        return response_ok($data);
    }


    /**
     * Find By longitude latitude
     * @param  Request $request [description]
     * @return Hexcores\Api\Facades\Response
     */
    public function find(Request $request)
    {
        $lat = (float) $request->input('lat');

        $long = (float) $request->input('lon');

        $data = $this->transform($this->model->findIntersects($long, $lat), new GeoTransformer(), true);
        

        return response_ok($data);
    }

    /**
     * Filter For UpperHouse
     * @param  Illuminate\Http\Request $request
     * @return LengthAwarePaginator
     */
    protected function filter($request)
    {
        if ($st_name = $request->input('st_name')) {

            $this->model = $this->model->like('properties.ST', $st_name);

        }

        if ($st_pcode = $request->input('st_pcode')) {

            $this->model = $this->model->where('properties.ST_PCODE', $st_pcode);
        }

        if ($noGeo = $request->input('no_geo'))
        {
            if ($noGeo == 'true') {

                return $this->model->paginate(['type','properties']);
            }
        }

        return $this->model->paginate();
    }

    /**
     * get Single data of UpperHouse geo location by mongo id
     * @param  string $id
     * @return Hexcores\Api\Facades\Response
     */
    public function getById($id)
    {
        $geo = $this->model->find($id);

        if (!$geo) {

            return response_missing();
        }

        return response_ok($this->transform($geo, new GeoTransformer()));
    }
}
