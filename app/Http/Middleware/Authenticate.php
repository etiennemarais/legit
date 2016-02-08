<?php

namespace App\Http\Middleware;

use Closure;
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

        # Checks the APi key against the country and the auth header exists
        if ($authHeader && $country) {
            // Add the multi-tenant country identifier
            app('Infrastructure\TenantScope\TenantScope')->addTenant('country_id', $country->id);

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
}
