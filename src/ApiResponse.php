<?php
namespace Arhamlabs\ApiResponse;

use Illuminate\Http\Response;
use Arhamlabs\ApiResponse\ResponseBody;

class ApiResponse
{
    use ResponseBody;

    public $customUserMessageTitle;
    public $customUserMessageText;
    public $customUserInteractionPrimaryAction;
    public $customUserInteractionPrimaryActionLabel;
    public $customUserInteractionSecondaryAction;
    public $customUserInteractionSecondaryActionLabel;
    public $isCustomResponse = false;

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

        #Return response as a json
        return response()->json($body, (int)$body["statusCode"]);
    }
}

?>