<?php namespace App\Http\Controllers;

use App\Transformers\Contracts\TransformerInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Laravel\Lumen\Routing\Controller as BaseController;
use League\Fractal\Manager;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;

class Controller extends BaseController
{
    /**
     * Transform data to json array with specific transformer class
     * @param mixed $data Data from model
     * @param \App\Transformers\Contracts\TransformerInterface $transformer Specific transformer class
     * @param bool $collection Data is a collection or pagination or single item
     * @return array
     */
    public function transform($data, TransformerInterface $transformer, $collection = false)
    {
        if ($collection)
        {
            return $this->collection($data, $transformer);
        }

        return $this->item($data, $transformer);
    }

    /**
     * @param $data
     * @param TransformerInterface $transformer
     * @return array
     */
    protected function item($data, TransformerInterface $transformer)
    {
        $resource = new Item($data, $transformer);

        $manager = new Manager();

        if ( $includes = app('request')->input('_with')) {
            $manager->parseIncludes($includes);
        }

        $data = $manager->createData($resource)->toArray();

        return $data;
    }

    /**
     * @param $data
     * @param TransformerInterface $transformer
     * @return array|\Illuminate\Support\Collection
     */
    protected function collection($data, TransformerInterface $transformer)
    {
        if ($data instanceof LengthAwarePaginator)
        {
            $pagination = $data;

            $data = $pagination->getCollection();
        }

        $resource = new Collection($data, $transformer);

        if ( isset($pagination)) {
            $resource->setPaginator(new IlluminatePaginatorAdapter($pagination));
        }

        $manager = new Manager();

        if ( $includes = app('request')->input('_with')) {
            $manager->parseIncludes($includes);
        }

        $data = $manager->createData($resource)->toArray();

        return $data;
    }
}
