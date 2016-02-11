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

    public function testCodeSend_ReturnsPhoneNumberValidationErrors()
    {
        $data = [
            'phone_number' => '8484811811', # bad format
            'client_user_id' => 1,
        ];

        $this->post('api/v1/code/send', $data)
            ->seeJsonEquals([
                "status" => 406,
                "message" => "The phone number is not the correct format for ZA,"
                    . " The phone number must be at least 11 characters.",
            ]);

        $this->assertResponseStatus(406);
    }

    public function testCodeSend_ReturnsOnCodeSuccessfullySent()
    {
        factory(\Legit\Verification\Verification::class)->create([
            'phone_number' => '27848118111',
            'client_user_id' => 1,
            'verification_status' => 'unverified',
        ]);

        $data = [
            'phone_number' => '27848118111',
            'client_user_id' => 1,
        ];

        $this->post('api/v1/code/send', $data)
            ->seeJsonEquals([
                "status" => 200,
                "message" => "Successfully sent verification code",
                "data" => [
                    'verification_status' => "awaiting verification",
                    'expires_at' => 'I still need to do this part',
                ],
            ]);

        $this->assertResponseStatus(200);
    }
}
