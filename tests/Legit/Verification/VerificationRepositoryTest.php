<?php

use Laravel\Lumen\Testing\DatabaseMigrations;

class VerificationRepositoryTest extends TestCase
{
    use DatabaseMigrations;

    private $repository;

    public function setUp()
    {
        parent::setUp();

        $this->turnOffMiddleware();
        $this->setupCountryDependency();

        $this->repository = new Legit\Verification\VerificationRepository(
            new \Legit\Verification\Verification()
        );
    }

    public function testIsInstantiable_ReturnsVerificationRepositoryInstance()
    {
        $this->assertInstanceOf(
            Legit\Verification\VerificationRepository::class,
            $this->repository
        );
    }

    
}
