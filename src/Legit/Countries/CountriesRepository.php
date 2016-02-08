<?php
namespace Legit\Countries;

use Legit\Countries;
use Legit\Repository;

class CountriesRepository extends Repository
{
    /**
     * CountriesRepository constructor.
     * @param Country $model
     */
    public function __construct(Country $model)
    {
        $this->model = $model;
    }

    /**
     * @param $apiKey
     * @return Country
     */
    public function findWithApiKey($apiKey)
    {
        return $this->model->where('api_key', $apiKey)->first();
    }
}
