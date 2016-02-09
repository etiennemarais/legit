<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Config;
use Legit\Countries\CountriesRepository;
use Symfony\Component\HttpFoundation\Response;

class Authenticate
{
    private $repository;

    /**
     * @param CountriesRepository $repository
     */
    public function __construct(CountriesRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param $request
     * @param Closure $next
     * @return Response
     */
    public function handle($request, Closure $next)
    {
        $authHeader = $request->header('Authorization');
        $authHeader = str_replace('Token ', '', $authHeader);

        $country = $this->repository->findWithApiKey($authHeader);

        // Checks the API key against the country and the auth header exists
        if ($authHeader && $country) {
            $this->addMultitenantIdentifier($country);
            return $next($request);
        }

        return $this->returnWithInvalidApiKey();
    }

    /**
     * @return mixed
     */
    public function returnWithInvalidApiKey()
    {
        return response()->json([
            "status" => 401,
            "message" => "Invalid API Key",
        ], 401);
    }

    /**
     * @param $country
     */
    protected function addMultitenantIdentifier($country)
    {
        app('Infrastructure\TenantScope\TenantScope')->addTenant('country_id', $country->id);
        Config::set('country_iso', $country->country_iso);
    }
}
