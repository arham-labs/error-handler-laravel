<?php

namespace Arhamlabs\ApiResponse\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Http;

#Import Mail
use Illuminate\Support\Facades\Mail;
use Arhamlabs\ApiResponse\Mail\ApiResponseNotificationMail;

class ApiResponseNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $notificationObject;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($notificationObject)
    {
        $this->notificationObject = $notificationObject;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        #Check if slack is enabled then notify the slack channel
        if ($this->notificationObject["is_slack_notification_enabled"]) {
            $response = Http::withHeaders([
                'Content-type' => 'application/json',
            ])
            ->post(config('apiResponse.slack_webhook_url'), [
                'text' => json_encode($this->notificationObject["body"])
            ]);
    
            #If Http request failed to send message to slack log the message
            if (!$response->ok()) {
                Log::alert($response->body());
            }
        }

        #Check if email is enabled then notify emails in the config
        if ($this->notificationObject["is_email_notification_enabled"]) {
            $emailsToNotify = config('apiResponse.notifiable_emails');

            #Get Project name
            $projectName = config("apiResponse.project_name");

            #Create mail object to send to developer
            $mailObject = array();
            $mailObject["project_name"] = $projectName;
            $mailObject["body"] = $this->notificationObject["body"];

            #Iterate and send emails
            foreach ($emailsToNotify as $email) {
                Mail::to($email)->send(new ApiResponseNotificationMail($mailObject));
            }
        }
        return true;
    }
}
