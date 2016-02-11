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

    public function tearDown()
    {
        parent::tearDown();
        unset($this->repository);
    }

    public function testIsInstantiable_ReturnsVerificationRepositoryInstance()
    {
        $this->assertInstanceOf(
            Legit\Verification\VerificationRepository::class,
            $this->repository
        );
    }

    public function testIsPhoneNumberVerified_ReturnsFalseOnUnverifiedNewNumber()
    {
        // Straight call, will add it to the DB as unverified
        $this->assertFalse($this->repository->isPhoneNumberVerified([
            'phone_number' => '27848118111',
            'client_user_id' => 1,
        ]));
    }

    public function testIsPhoneNumberVerified_ReturnsFalseOnUnverifiedExistingNumber()
    {
        factory(\Legit\Verification\Verification::class)->create([
            'phone_number' => '27848118112',
            'client_user_id' => 1,
            'verification_status' => 'unverified',
        ]);
        // Straight call, Already added to the db, not verified
        $this->assertFalse($this->repository->isPhoneNumberVerified([
            'phone_number' => '27848118112',
            'client_user_id' => 1,
        ]));
    }

    public function testIsPhoneNumberVerified_ReturnsTrueOnVerifiedNumber()
    {
        // Straight call, Verified
        factory(\Legit\Verification\Verification::class)->create([
            'phone_number' => '27848118113',
            'client_user_id' => 1,
            'verification_status' => 'verified',
        ]);
        // Straight call, Already added to the db, not verified
        $this->assertTrue($this->repository->isPhoneNumberVerified([
            'phone_number' => '27848118113',
            'client_user_id' => 1,
        ]));
    }

    public function testFindWithAttributes_ReturnsExistingModelIfFound()
    {
        // Create one first
        factory(\Legit\Verification\Verification::class)->create([
            'phone_number' => '27848118113',
            'client_user_id' => 1,
            'verification_status' => 'unverified',
        ]);

        $data = [
            'phone_number' => '27848118111',
            'client_user_id' => 1,
        ];

        $verification = $this->repository->findWithAttributes($data);

        $this->seeInDatabase('verification', $data);
        $this->assertInstanceOf(\Legit\Verification\Verification::class, $verification);
    }

    public function testFindWithAttributes_ReturnsModelAfterCreatingItIfNotFound()
    {
        $data = [
            'phone_number' => '27848118111',
            'client_user_id' => 1,
        ];

        $verification = $this->repository->findWithAttributes($data);

        $this->seeInDatabase('verification', $data);
        $this->assertInstanceOf(\Legit\Verification\Verification::class, $verification);
    }

    public function testSetAwaitingVerificationStatus_ReturnsUpdatedModelVerificationStatus()
    {
        $verification = factory(\Legit\Verification\Verification::class)->create([
            'phone_number' => '27848118113',
            'client_user_id' => 1,
            'verification_status' => 'unverified',
        ]);

        $this->repository->setAwaitingVerificationStatus($verification);

        $this->assertEquals('awaiting verification', $verification->verification_status);
    }
}
