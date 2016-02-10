<?php
namespace Legit;

use Illuminate\Database\Eloquent\Model;

class Repository
{
    protected $model;

    /**
     * @param array $attributes
     * @return Model
     */
    public function findWithAttributes(array $attributes)
    {
        return $this->model->where($attributes)->first();
    }
}
