<?php
namespace App\Http\Controllers;

use Parsedown;

class ApiController extends Controller
{
    /**
     * @param Parsedown $parsedown
     * @return \Laravel\Lumen\Http\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function welcome(Parsedown $parsedown)
    {
        $docs = "<!DOCTYPE html><html>
<head><link rel=\"stylesheet\" href=\"https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css\"></head>
<body class=\"container\">";
        $docs .= $parsedown->parse(file_get_contents(base_path() . '/apiary.apib'));
        $docs .= "</body></html>";
        return response($docs);
    }

    /**
     * @return \Illuminate\Contracts\Routing\ResponseFactory
     */
    public function index()
    {
        return $this->response()->withArray([
            'status' => 200,
            'message' => 'Legit is a simple service for easy client(eg. Atlas) user verification via a unique user identifier.',
            'data' => $this->getEndpoints(),
        ]);
    }

    /**
     * @return array
     */
    private function getEndpoints()
    {
        return [
            'prefix' => '/api/v1',
            'version' => 'v1',
            'endpoints' => [
                [
                    'uri' => '/code/send',
                    'method' => 'POST',
                ],
                [
                    'uri' => '/code/resend',
                    'method' => 'POST',
                ],
                [
                    'uri' => '/verification/check',
                    'method' => 'GET',
                ],
                [
                    'uri' => '/verification/verify',
                    'method' => 'PUT',
                ],
                [
                    'uri' => '/verification/block',
                    'method' => 'PUT',
                ],
                [
                    'uri' => '/status/credits',
                    'method' => 'GET',
                ],
            ]
        ];
    }
}
