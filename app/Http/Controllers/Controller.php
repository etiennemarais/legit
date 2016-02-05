<?php

namespace App\Http\Controllers;

use EllipseSynergie\ApiResponse\Laravel\Response;
use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    /**
     * @var Response
     */
    private $response;

    /**
     * Controller constructor.
     * @param Response $response
     */
    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    /**
     * @return Response
     */
    protected function response()
    {
        return $this->response;
    }
}
