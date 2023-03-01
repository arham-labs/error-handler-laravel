<?php
namespace Arhamlabs\ApiResponse;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;

use Arhamlabs\ApiResponse\ResponseBody;

#Job Import
// use Arhamlabs\ApiResponse\Jobs\ApiResponseNotificationJob;
use Arhamlabs\NotificationHandler\Jobs\MailNotificationHandlerJob;
use Arhamlabs\NotificationHandler\Jobs\SlackNotificationHandlerJob;

class ApiResponse
{
    use ResponseBody;

    public $customUserMessageTitle;
    public $customUserMessageText;
    public $customUserInteractionPrimaryAction;
    public $customUserInteractionPrimaryActionLabel;
    public $customUserInteractionSecondaryAction;
    public $customUserInteractionSecondaryActionLabel;
    public $customData;
    public $customErrors;
    public $isCustomResponse = false;
    public $isCustomData = false;
    public $isCustomErrors = false;

    /**
     * This function is provides an option for the user
     * to overwrite the default user end interaction in the response.
     */
    public function setCustomResponse($customUserMessageTitle=null, $customUserMessageText=null, $customUserInteractionPrimaryAction=null, $customUserInteractionPrimaryActionLabel=null, $customUserInteractionSecondaryAction=null, $customUserInteractionSecondaryActionLabel=null)
    {
        $this->isCustomResponse = true;
        $this->customUserMessageTitle = $customUserMessageTitle;
        $this->customUserMessageText = $customUserMessageText;
        $this->customUserInteractionPrimaryAction = $customUserInteractionPrimaryAction;
        $this->customUserInteractionPrimaryActionLabel = $customUserInteractionPrimaryActionLabel;
        $this->customUserInteractionSecondaryAction = $customUserInteractionSecondaryAction;
        $this->customUserInteractionSecondaryActionLabel = $customUserInteractionSecondaryActionLabel;
    }

    /**
     * This function is provides an option for the user
     * to overwrite the default errors in the response.
     */
    public function setCustomErrors($errors) 
    {
        $this->isCustomErrors = true;
        #Check if error is set to convert it into array
        if (isset($errors) && is_string($errors)) {
            $this->customErrors = array("errors" => array($errors));
        } else {
            $this->customErrors = $errors;
        }
    }

    /**
     * This function is used to send the response body as the result
     * of the api request.
     */
    public function getResponse($statusCode=null, $data=null, $message=null, $file=null, $line=null, $errors=null)
    {
        #Get the response body from the function
        $body = $this->getResponseBody($statusCode, $data, $message, $file, $line, $errors);

        #Check if user has set any custom response params then need to overwrite the default response
        if ($this->isCustomResponse) 
        {
            $body["userMessageTitle"] = isset($this->customUserMessageTitle) ? $this->customUserMessageTitle : $body["userMessageTitle"];

            $body["userMessageText"] = isset($this->customUserMessageText) ? $this->customUserMessageText : $body["userMessageText"];

            $body["handling"]["primaryAction"] = isset($this->customUserInteractionPrimaryAction) ? $this->customUserInteractionPrimaryAction : $body["handling"]["primaryAction"];

            $body["handling"]["primaryActionLabel"] = isset($this->customUserInteractionPrimaryActionLabel) ? $this->customUserInteractionPrimaryActionLabel : $body["handling"]["primaryActionLabel"];
            
            $body["handling"]["secondaryAction"] = isset($this->customUserInteractionSecondaryAction) ? $this->customUserInteractionSecondaryAction : $body["handling"]["secondaryAction"];

            $body["handling"]["secondaryActionLabel"] = isset($this->customUserInteractionSecondaryActionLabel) ? $this->customUserInteractionSecondaryActionLabel : $body["handling"]["secondaryActionLabel"];
        }

        #Check if user has set any custom errors then overwrite the default error object
        if ($this->isCustomErrors) {
            $body["errors"] = $this->customErrors;
        }

        #Get Config flag to check if notification if enabled for the project
        $isNotificationEnabled = config('alNotificationConfig.enable_notification');

        $statusCodeArray = config('apiResponse.notifiable_status_codes');
        
        #If status code is 500 and notification is set to true in config dispatch to Notification Job
        if (in_array($statusCode, $statusCodeArray) && $isNotificationEnabled) {
            #Get Config for which of the notifications to send
            $isSlackNotificationEnabled = config('alNotificationConfig.notification_type.slack');
            $isEmailNotificationEnabled = config('alNotificationConfig.notification_type.email');

            #Dispatch to job with the notification object
            $notificationObject = array();
            $notificationObject["body"] = json_encode($body);

            #Check if slack is enabled
            if ($isSlackNotificationEnabled) {
                SlackNotificationHandlerJob::dispatch($notificationObject);
            }

            #Check if email is enabled
            if ($isEmailNotificationEnabled) {
                MailNotificationHandlerJob::dispatch($notificationObject);
            }
        }

        #Return response as a json
        return response()->json($body, (int)$body["statusCode"]);
    }
}

?>