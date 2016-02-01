<?php

/**
 * Generated by OutlineTestGenerator on 2016-02-01 at 14:55:27.
 */
class FeaturesTest extends TestCase
{

    public function testPost_Sending_a_code_Returns_200()
    {
        $this->post("/code/send")
            ->assertResponseStatus(200);
    }

    public function testPost_Sending_a_code_Returns_400()
    {
        $this->post("/code/send")
            ->assertResponseStatus(400);
    }

    public function testPost_Sending_a_code_Returns_400()
    {
        $this->post("/code/send")
            ->assertResponseStatus(400);
    }

    public function testPost_Sending_a_code_Returns_401()
    {
        $this->post("/code/send")
            ->assertResponseStatus(401);
    }

    public function testPost_Sending_a_code_Returns_406()
    {
        $this->post("/code/send")
            ->assertResponseStatus(406);
    }

    public function testPost_Resending_a_code_Returns_200()
    {
        $this->post("/code/resend")
            ->assertResponseStatus(200);
    }

    public function testPost_Resending_a_code_Returns_400()
    {
        $this->post("/code/resend")
            ->assertResponseStatus(400);
    }

    public function testPost_Resending_a_code_Returns_400()
    {
        $this->post("/code/resend")
            ->assertResponseStatus(400);
    }

    public function testPost_Resending_a_code_Returns_401()
    {
        $this->post("/code/resend")
            ->assertResponseStatus(401);
    }

    public function testPost_Resending_a_code_Returns_406()
    {
        $this->post("/code/resend")
            ->assertResponseStatus(406);
    }

    public function testPost_Resending_a_code_Returns_412()
    {
        $this->post("/code/resend")
            ->assertResponseStatus(412);
    }

    public function testGet_Verification_check_Returns_200()
    {
        $this->get("/verification/check?phone_number={phone_number}&client_user_id={client_user_id}")
            ->assertResponseStatus(200);
    }

    public function testGet_Verification_check_Returns_400()
    {
        $this->get("/verification/check?phone_number={phone_number}&client_user_id={client_user_id}")
            ->assertResponseStatus(400);
    }

    public function testGet_Verification_check_Returns_401()
    {
        $this->get("/verification/check?phone_number={phone_number}&client_user_id={client_user_id}")
            ->assertResponseStatus(401);
    }

    public function testGet_Verification_check_Returns_403()
    {
        $this->get("/verification/check?phone_number={phone_number}&client_user_id={client_user_id}")
            ->assertResponseStatus(403);
    }

    public function testGet_Verification_check_Returns_406()
    {
        $this->get("/verification/check?phone_number={phone_number}&client_user_id={client_user_id}")
            ->assertResponseStatus(406);
    }

    public function testPut_Verifying_a_phone_number_Returns_200()
    {
        $this->put("/verification/verify")
            ->assertResponseStatus(200);
    }

    public function testPut_Verifying_a_phone_number_Returns_400()
    {
        $this->put("/verification/verify")
            ->assertResponseStatus(400);
    }

    public function testPut_Verifying_a_phone_number_Returns_400()
    {
        $this->put("/verification/verify")
            ->assertResponseStatus(400);
    }

    public function testPut_Verifying_a_phone_number_Returns_400()
    {
        $this->put("/verification/verify")
            ->assertResponseStatus(400);
    }

    public function testPut_Verifying_a_phone_number_Returns_401()
    {
        $this->put("/verification/verify")
            ->assertResponseStatus(401);
    }

    public function testPut_Verifying_a_phone_number_Returns_404()
    {
        $this->put("/verification/verify")
            ->assertResponseStatus(404);
    }

    public function testPut_Verifying_a_phone_number_Returns_406()
    {
        $this->put("/verification/verify")
            ->assertResponseStatus(406);
    }

    public function testPut_Blocking_a_phone_number_Returns_200()
    {
        $this->put("/verification/block")
            ->assertResponseStatus(200);
    }

    public function testPut_Blocking_a_phone_number_Returns_400()
    {
        $this->put("/verification/block")
            ->assertResponseStatus(400);
    }

    public function testPut_Blocking_a_phone_number_Returns_400()
    {
        $this->put("/verification/block")
            ->assertResponseStatus(400);
    }

    public function testPut_Blocking_a_phone_number_Returns_401()
    {
        $this->put("/verification/block")
            ->assertResponseStatus(401);
    }

    public function testPut_Blocking_a_phone_number_Returns_406()
    {
        $this->put("/verification/block")
            ->assertResponseStatus(406);
    }

    public function testGet_Fetching_credits_available_Returns_200()
    {
        $this->get("/status/credits")
            ->assertResponseStatus(200);
    }

    public function testGet_Fetching_credits_available_Returns_401()
    {
        $this->get("/status/credits")
            ->assertResponseStatus(401);
    }

    public function testGet_Fetching_credits_available_Returns_402()
    {
        $this->get("/status/credits")
            ->assertResponseStatus(402);
    }

}
