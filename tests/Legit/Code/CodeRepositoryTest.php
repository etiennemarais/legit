<?php

use Laravel\Lumen\Testing\DatabaseMigrations;

class CodeRepositoryTest extends TestCase
{
    use DatabaseMigrations;

    private $repository;

    public function setUp()
    {
        parent::setUp();

        $this->turnOffMiddleware();
        $this->setupCountryDependency();

        $this->repository = new Legit\Code\CodeRepository(
            new \Legit\Code\Code()
        );
    }

    public function tearDown()
    {
        parent::tearDown();
        unset($this->repository);
    }

    public function testSeed()
    {
    }
}
