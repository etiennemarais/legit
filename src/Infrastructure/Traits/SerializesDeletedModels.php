<?php
namespace Infrastructure\Traits;

use Illuminate\Contracts\Database\ModelIdentifier;

trait SerializesDeletedModels
{
    protected function getRestoredPropertyValue($value)
    {
        if ($value instanceof ModelIdentifier) {
            $model = new $value->class;

            if (method_exists($model, 'withTrashed')) {
                return $model->withTrashed()->findOrFail($value->id);
            }

            // TODO figure out why findOrFail does not work here
            return $model->findOrFail($value->id);
        } else {
            return $value;
        }
    }
}