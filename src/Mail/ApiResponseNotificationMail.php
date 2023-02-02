<?php

namespace Arhamlabs\ApiResponse\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ApiResponseNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    protected array $mailObject;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(array $mailObject)
    {
        $this->mailObject = $mailObject;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = "Api Error in " . $this->mailObject["project_name"] . "!";
        return $this->view('vendor.apiResponse.api_response_notification_email')
                    ->subject($subject)
                    ->with([
                        'data' => $this->mailObject,
                    ]);
    }
}
