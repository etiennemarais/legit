<?php

use App\Jobs\Code\SendCodeJob;
use Legit\Sending\Clickatell\exceptions\ClickatellSendingException;
use Legit\Verification\Verification;

class SendCodeJobTest extends TestCase
{
    private $codeRepository;
    private $verificationModel;
    private $sendingProvider;
    private $sendCodeJob;

    public function setUp()
    {
        parent::setUp();
        $this->codeRepository = Mockery::mock('Legit\Code\CodeRepository');
        $this->setVerificationModelStub();
        $this->sendingProvider = $this->mock('Legit\Sending\Contracts\SendingProvider');
        $this->sendCodeJob = new SendCodeJob($this->verificationModel);
    }

    public function tearDown()
    {
        parent::tearDown();
        unset(
            $this->codeRepository,
            $this->verificationModel,
            $this->sendingProvider,
            $this->sendCodeJob
        );
    }

    public function testHandle_ThrowsClickatellSendingExceptionOnBadAuth()
    {
        $authException = new ClickatellSendingException('Authentication failed', 1);
        $this->setExpectedException(get_class($authException), $authException->getMessage());

        $codeObject = $this->setupCodeRepository();

        $this->sendingProvider->shouldReceive('sendOTP')
            ->with(
                $this->verificationModel->phone_number,
                $codeObject->code
            )->once()
                ->andThrow($authException);

        $this->returnEmptyRetryCodes();
        $this->runSendCodeJobHandleMethod();
    }

    public function testHandle_ThrowsClickatellSendingExceptionOnNoCreditLeft()
    {
        $noCreditsException = new ClickatellSendingException('No credits left', 301);

        $codeObject = $this->setupCodeRepository();

        $this->sendingProvider->shouldReceive('sendOTP')
            ->with(
                $this->verificationModel->phone_number,
                $codeObject->code
            )->once()
            ->andThrow($noCreditsException);

        $this->sendingProvider->shouldReceive('getRetryQueueCodes')
            ->once()
            ->andReturn([301]);

        $this->runSendCodeJobHandleMethod();
    }

    public function testHandle_ReturnsTrueOnSuccessfulSend()
    {
        $codeObject = $this->setupCodeRepository();

        $this->sendingProvider->shouldReceive('sendOTP')
            ->with(
                $this->verificationModel->phone_number,
                $codeObject->code
            )->once();

        $this->runSendCodeJobHandleMethod();
    }

    protected function setVerificationModelStub()
    {
        $this->verificationModel = new Verification();
        $this->verificationModel->country_id = 1;
        $this->verificationModel->id = 1;
        $this->verificationModel->phone_number = '27848118111';
    }

    /**
     * @return \Legit\Code\Code
     */
    protected function setupCodeRepository()
    {
        $codeObject = new \Legit\Code\Code();
        $codeObject->code = '123456';

        $this->codeRepository->shouldReceive('createWithAttributes')
            ->once()
            ->andReturn($codeObject);
        return $codeObject;
    }

    protected function runSendCodeJobHandleMethod()
    {
        $this->sendCodeJob->handle(
            $this->sendingProvider,
            $this->codeRepository
        );
    }

    protected function returnEmptyRetryCodes()
    {
        $this->sendingProvider->shouldReceive('getRetryQueueCodes')
            ->once()
            ->andReturn([]);
    }
}
