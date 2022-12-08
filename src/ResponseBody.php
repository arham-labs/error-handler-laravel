<?php
namespace Arhamlabs\ApiResponse;

trait ResponseBody {
    public $statusCode;
    public $status;
    public $message;
    public $file;
    public $line;
    public $isResponseInteractive;
    public $userMessageTitle;
    public $userMessageText;
    public $userInteractionPrimaryAction;
    public $userInteractionPrimaryActionLabel;
    public $userInteractionSecondaryAction;
    public $userInteractionSecondaryActionLabel;
    public $data;
    public $errors;

    /**
     * This function returns a response body according to the
     * status code provided.
     */
    public function getResponseBody($statusCode, $data=null, $message=null, $file=null, $line=null, $errors=null) 
    {
        #Set Common attributes 
        $this->message = $message;
        #Check if error is set to convert it into array
        if (isset($errors) && !is_array($errors)) {
            $errors = array("errors" => array($errors));
        }
        $this->errors = $errors;
        $this->file = $file;
        $this->line = $line;
        $this->data = $data;
        $this->errors = $errors;

        #Switch status code to get the response body
        switch ($statusCode) {
            case 200:
                $this->statusCode = 200;
                $this->status = "SUCCESS";
                $this->isResponseInteractive = false;
                $this->userMessageTitle = "Successful";
                $this->userMessageText = "Request is successful";
                $this->userInteractionPrimaryAction = "dismiss";
                $this->userInteractionPrimaryActionLabel = "Okay";
                $this->userInteractionSecondaryAction = "dismiss";
                $this->userInteractionSecondaryActionLabel = "Cancel";
                break;
            case 400:
                $this->statusCode = 400;
                $this->status = "BAD_REQUEST";
                $this->isResponseInteractive = false;
                $this->userMessageTitle = "Error";
                $this->userMessageText = "Request is a bad request";
                $this->userInteractionPrimaryAction = "dismiss";
                $this->userInteractionPrimaryActionLabel = "Okay";
                $this->userInteractionSecondaryAction = "dismiss";
                $this->userInteractionSecondaryActionLabel = "Cancel";
                break;
            case 401:
                $this->statusCode = 401;
                $this->status = "UNAUTHORIZED_REQUEST";
                $this->isResponseInteractive = false;
                $this->userMessageTitle = "Error";
                $this->userMessageText = "Request is a unauthorized";
                $this->userInteractionPrimaryAction = "login";
                $this->userInteractionPrimaryActionLabel = "Login";
                $this->userInteractionSecondaryAction = "dismiss";
                $this->userInteractionSecondaryActionLabel = "Cancel";
                break;
            case 403:
                $this->statusCode = 403;
                $this->status = "FORBIDDEN_REQUEST";
                $this->isResponseInteractive = false;
                $this->userMessageTitle = "Error";
                $this->userMessageText = "Request is a forbidden";
                $this->userInteractionPrimaryAction = "dismiss";
                $this->userInteractionPrimaryActionLabel = "Okay";
                $this->userInteractionSecondaryAction = "dismiss";
                $this->userInteractionSecondaryActionLabel = "Cancel";
                break;
            case 404:
                $this->statusCode = 404;
                $this->status = "REQUEST_RESOURCE_NOT_FOUND";
                $this->isResponseInteractive = false;
                $this->userMessageTitle = "Error";
                $this->userMessageText = "Requested resource does not exist";
                $this->userInteractionPrimaryAction = "dismiss";
                $this->userInteractionPrimaryActionLabel = "Okay";
                $this->userInteractionSecondaryAction = "dismiss";
                $this->userInteractionSecondaryActionLabel = "Cancel";
                break;
            case 422:
                $this->statusCode = 422;
                $this->status = "UNPROCESSABLE_ENTITY";
                $this->isResponseInteractive = false;
                $this->userMessageTitle = "Error";
                $this->userMessageText = "Request cannot be processed at the moment";
                $this->userInteractionPrimaryAction = "retry";
                $this->userInteractionPrimaryActionLabel = "Retry";
                $this->userInteractionSecondaryAction = "dismiss";
                $this->userInteractionSecondaryActionLabel = "Cancel";
                break;
            case 500:
                $this->statusCode = 500;
                $this->status = "ERROR";
                $this->isResponseInteractive = true;
                $this->userMessageTitle = "Error";
                $this->userMessageText = "Unexpected error occurred";
                $this->userInteractionPrimaryAction = "dismiss";
                $this->userInteractionPrimaryActionLabel = "Okay";
                $this->userInteractionSecondaryAction = "dismiss";
                $this->userInteractionSecondaryActionLabel = "Cancel";
                break;
            default:
                $this->statusCode = 500;
                $this->status = "ERROR";
                $this->isResponseInteractive = true;
                $this->userMessageTitle = "Error";
                $this->userMessageText = "Unexpected error occurred";
                $this->userInteractionPrimaryAction = "dismiss";
                $this->userInteractionPrimaryActionLabel = "Okay";
                $this->userInteractionSecondaryAction = "dismiss";
                $this->userInteractionSecondaryActionLabel = "Cancel";
                break;
        }
        
        #Return response
        return [
            'statusCode' => $this->statusCode,
            'status' => $this->status,
            'message' => $this->message,
            'file' => $this->file,
            'line' => $this->line,
            'userMessageTitle' => $this->userMessageTitle,
            'userMessageText' => $this->userMessageText,
            'isResponseInteractive' => $this->isResponseInteractive,
            'handling' => array(
                "primaryAction" => $this->userInteractionPrimaryAction,
                "primaryActionLabel" => $this->userInteractionPrimaryActionLabel,
                "secondaryAction" => $this->userInteractionSecondaryAction,
                "secondaryActionLabel" => $this->userInteractionSecondaryActionLabel
            ),
            'data' => $this->data,
            'errors' => $this->errors
        ];
    }
}

?>