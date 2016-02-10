<?php

use Laravel\Lumen\Testing\DatabaseMigrations;

class CodeTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp()
    {
        parent::setUp();

        $this->turnOffMiddleware();
        $this->setupCountryDependency();
    }

    public function testCodeSend_ReturnsPhoneNumberErrorOnMissingFields()
    {
        $data = [
            'client_user_id' => 1,
        ];

        $this->post('api/v1/code/send', $data)
            ->seeJsonEquals([
                "status" => 400,
                "message" => "Missing required fields phone_number"
            ]);
        $this->assertResponseStatus(400);
    }

    public function testCodeSend_ReturnsClientUserIdentifierErrorOnMissingFields()
    {
        $data = [
            'phone_number' => '27848118111',
        ];

        $this->post('api/v1/code/send', $data)
            ->seeJsonEquals([
                "status" => 400,
                "message" => "Missing required fields client_user_id"
            ]);
        $this->assertResponseStatus(400);
    }

    public function testCodeSend_ReturnsErrorOnMissingFields()
    {
        $data = [
            # No data passed through
        ];

        $this->post('api/v1/code/send', $data)
            ->seeJsonEquals([
                "status" => 400,
                "message" => "Missing required fields client_user_id, phone_number"
            ]);
        $this->assertResponseStatus(400);
    }
}
