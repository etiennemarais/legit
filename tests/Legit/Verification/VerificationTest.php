<?php

use Laravel\Lumen\Testing\DatabaseMigrations;

class VerificationTest extends TestCase
{
    use DatabaseMigrations;

    public function testVerificationCheck_ReturnsPhoneNumberErrorOnMissingFields()
    {
        factory(\Legit\Verification\Verification::class)->create();

        $this->get('api/v1/verification/check?client_user_id=1', [])
            ->seeJsonEquals([
                "status" => 400,
                "message" => "Missing required field 'phone_number'"
            ]);
        $this->assertResponseStatus(400);
    }

    public function testVerificationCheck_ReturnsClientIdErrorOnMissingFields()
    {
        factory(\Legit\Verification\Verification::class)->create();

        $this->get('api/v1/verification/check?phone_number=278118111', [])
            ->seeJsonEquals([
                "status" => 400,
                "message" => "Missing required field 'client_user_id'"
            ]);
        $this->assertResponseStatus(400);
    }
}
