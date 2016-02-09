<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    /**
     * @param $message
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function respondWithMissingField($message)
    {
        return response()->json([
            'status' => 400,
            'message' => $message,
        ], 400);
    }

    /**
     * @param $validator
     * @return string
     */
    protected function getMessageFromValidator($validator)
    {
        $required = [];
        $messages = $validator->errors()->toArray();
        foreach($messages as $field => $message) {
            if (strpos($message[0], 'required')) {
                $required[] = $field;
            }
        }

        if (count($required) > 0) {
            $fields = implode(', ', $required);
            $message = "Missing required fields $fields";
        }

        return $message;
    }
}
