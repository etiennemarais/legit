<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Legit\Sending\Clickatell\exceptions\ClickatellSendingException;
use lygav\slackbot\Slackbot;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $e
     * @return void
     */
    public function report(Exception $e)
    {
        if ($e instanceof ClickatellSendingException) {
            Log::error(ClickatellSendingException::class . ": " . $e->getMessage() . " (code:{$e->getCode()})");
            if (env('APP_ENV') !== 'testing') {
                $options = array(
                    'username' => 'legit-bot',
                    'icon_emoji' => ':mushroom:',
                    'channel' => '#legit'
                );
                $bot = new Slackbot(env('SLACK_WEBHOOK_URL'), $options);
                $attachment = $bot->buildAttachment("Legit Error"/* mandatory by slack */)
                    ->setPretext("Something went wrong trying to send an SMS with Legit")
                    ->setText(ClickatellSendingException::class . ": " . $e->getMessage() . " (code:{$e->getCode()})")
                    ->setColor("red");

                $bot->attach($attachment)->send();
                return;
            }
        }

        parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
        return parent::render($request, $e);
    }
}
