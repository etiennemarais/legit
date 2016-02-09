<?php

use Illuminate\Support\Facades\Config;
use Laravel\Lumen\Testing\DatabaseMigrations;

class VerificationTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp()
    {
        parent::setUp();

        $this->turnOffMiddleware();
        factory(\Legit\Countries\Country::class)->create();
        Config::set('country_iso', 'ZA');
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
}
