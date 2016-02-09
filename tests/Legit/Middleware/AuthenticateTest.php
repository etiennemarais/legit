<?php

class AuthenticateTest extends TestCase
{
    private $countriesRepository;
    private $request;
    private $closureNext;
    private $authMiddleware;
    private $multiTenantScope;

    public function setUp()
    {
        parent::setUp();
        $this->countriesRepository = Mockery::mock('Legit\Countries\CountriesRepository');
        $this->request = Mockery::mock('Illuminate\Http\Request');
        $this->multiTenantScope = $this->mock('Infrastructure\TenantScope\TenantScope');

        $request = $this->request;

        $this->closureNext = function() use ($request) {
            return $request;
        };

        $this->authMiddleware = new \App\Http\Middleware\Authenticate(
            $this->countriesRepository
        );
    }

    public function testInitialize_ReturnsInstanceOfAuthenticateMiddleware()
    {
        $this->assertInstanceOf('App\Http\Middleware\Authenticate', $this->authMiddleware);
    }

    public function testHandle_ReturnsUnauthorizedResponse()
    {
        $this->request->shouldReceive('header')
            ->with('Authorization')
            ->once()
            ->andReturn(null);

        $this->countriesRepository->shouldReceive('findWithApiKey')
            ->once()
            ->andReturn(null);

        $this->response = $this->authMiddleware->handle($this->request, $this->closureNext);
        $this->assertResponseStatus(401);
        $this->seeJsonEquals([
            'status' => 401,
            'message' => 'Invalid API Key',
        ]);
    }

    public function testHandle_ValidatesCountryAndFollowsThroughRouter()
    {
        $apiKey = 'Token someValidApiKey';

        $this->request->shouldReceive('header')
            ->with('Authorization')
            ->once()
            ->andReturn($apiKey);

        $country = Mockery::mock('Country');
        $country->id = 1;

        $this->countriesRepository->shouldReceive('findWithApiKey')
            ->once()
            ->andReturn($country);

        $this->multiTenantScope->shouldReceive('addTenant')
            ->with('country_id', $country->id)
            ->once();

        $request = $this->authMiddleware->handle($this->request, $this->closureNext);
        $this->assertInstanceOf(get_class($this->request), $request);
    }
}
