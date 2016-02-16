<?php

use Laravel\Lumen\Testing\DatabaseMigrations;

class VerificationTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp()
    {
        parent::setUp();

        $this->turnOffMiddleware();
        $this->setupCountryDependency();
    }

    public function testVerificationCheck_ReturnsPhoneNumberErrorOnMissingFields()
    {
        factory(\Legit\Verification\Verification::class)->create();

        $this->get('api/v1/verification/check?client_user_id=1')
            ->seeJsonEquals([
                "status" => 400,
                "message" => "Missing required fields phone_number"
            ]);
        $this->assertResponseStatus(400);
    }

    public function testVerificationCheck_ReturnsClientIdErrorOnMissingFields()
    {
        factory(\Legit\Verification\Verification::class)->create();

        $this->get('api/v1/verification/check?phone_number=27848118111');
        $this->seeJsonEquals([
            "status" => 400,
            "message" => "Missing required fields client_user_id"
        ]);
        $this->assertResponseStatus(400);
    }

    public function testVerificationCheck_ReturnsErrorsOnMissingFields()
    {
        factory(\Legit\Verification\Verification::class)->create();

        $this->get('api/v1/verification/check')
            ->seeJsonEquals([
                "status" => 400,
                "message" => "Missing required fields client_user_id, phone_number"
            ]);
        $this->assertResponseStatus(400);
    }

    public function testVerificationCheck_ReturnsNotVerifiedError()
    {
        factory(\Legit\Verification\Verification::class)->create([
            'phone_number' => '27848118111',
            'client_user_id' => 1,
            'verification_status' => 'unverified',
        ]);

        $this->get('api/v1/verification/check?phone_number=27848118111&client_user_id=1')
            ->seeJsonEquals([
                "status" => 403,
                "message" => "This phone number is not verified",
                "data" => [
                    "client_user_id" => "1",
                    "phone_number" => "27848118111",
                ],
            ]);

        $this->assertResponseStatus(403);
    }

    public function testVerificationCheck_ReturnsPhoneNumberIsVerified()
    {
        factory(\Legit\Verification\Verification::class)->create([
            'phone_number' => '27848118111',
            'client_user_id' => 1,
            'verification_status' => 'verified',
        ]);

        $this->get('api/v1/verification/check?phone_number=27848118111&client_user_id=1')
            ->seeJsonEquals([
                "status" => 200,
                "message" => "This phone number is verified",
                "data" => [
                    "client_user_id" => "1",
                    "phone_number" => "27848118111",
                ],
            ]);

        $this->assertResponseStatus(200);
    }

    public function testVerificationCheck_ReturnsPhoneNumberValidationErrors()
    {
        $this->get('api/v1/verification/check?phone_number=8484811811&client_user_id=1')
            ->seeJsonEquals([
                "status" => 406,
                "message" => "The phone number is not the correct format for ZA,"
                    . " The phone number must be at least 11 characters.",
            ]);

        $this->assertResponseStatus(406);
    }

    public function testVerificationVerify_ReturnsPhoneNumberErrorOnMissingFields()
    {
        $data = [
            'client_user_id' => 1,
        ];

        $this->post('api/v1/verification/verify', $data)
            ->seeJsonEquals([
                "status" => 400,
                "message" => "Missing required fields phone_number, code"
            ]);
        $this->assertResponseStatus(400);
    }

    public function testVerificationVerify_ReturnsClientUserIdentifierErrorOnMissingFields()
    {
        $data = [
            'phone_number' => '27848118111',
        ];

        $this->post('api/v1/verification/verify', $data)
            ->seeJsonEquals([
                "status" => 400,
                "message" => "Missing required fields client_user_id, code"
            ]);
        $this->assertResponseStatus(400);
    }

    public function testVerificationVerify_ReturnsCodeErrorOnMissingFields()
    {
        $data = [
            'phone_number' => '27848118111',
            'client_user_id' => 1,
        ];

        $this->post('api/v1/verification/verify', $data)
            ->seeJsonEquals([
                "status" => 400,
                "message" => "Missing required fields code"
            ]);
        $this->assertResponseStatus(400);
    }

    public function testVerificationVerify_ReturnsCodeErrorOnInvalidCodeProvided()
    {
        $data = [
            'phone_number' => '27848118111',
            'client_user_id' => 1,
            'code' => 'someBadCode',
        ];

        $this->post('api/v1/verification/verify', $data)
            ->seeJsonEquals([
                "status" => 406,
                "message" => "The code that you provided is invalid",
            ]);
        $this->assertResponseStatus(406);
    }

    public function testVerificationVerify_ReturnsNotVerifiedResponseErrorOnInvalidCodeProvided()
    {
        factory(\Legit\Code\Code::class)->create();

        $data = [
            'phone_number' => '27848118111',
            'client_user_id' => 1,
            'code' => '123455', # Intentional wrong code
        ];

        $this->post('api/v1/verification/verify', $data)
            ->seeJsonEquals([
                'status' => 403,
                'message' => 'This phone number is not verified',
                'data' => [
                    'phone_number' => '27848118111',
                    'client_user_id' => 1,
                ],
            ]);
        $this->assertResponseStatus(403);
    }

    public function testVerificationVerify_ReturnsVerifiedResponseValidCodeProvided()
    {
        factory(\Legit\Code\Code::class)->create();

        $data = [
            'phone_number' => '27848118111',
            'client_user_id' => 1,
            'code' => '123456', # Correct code
        ];

        $this->post('api/v1/verification/verify', $data)
            ->seeJsonEquals([
                'status' => 200,
                'message' => 'This phone number is verified',
                'data' => [
                    'phone_number' => '27848118111',
                    'client_user_id' => 1,
                ],
            ]);
        $this->assertResponseStatus(200);
    }
}
