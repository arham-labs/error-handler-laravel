Error Handler Package
=====================

This package is a template used to create API responses which can be overwritten as per the developer's requirements.

<br/>

## Installation:

In order to install the package use the command specified below - 
> composer require arhamlabs/error-handler

<br/>

## Usage:

After installing the package, you may need to import the package in the file you want to use. Use the following import syntax
> use Arhamlabs\ApiResponse\ApiResponse;

<br/>

*For using the package refer the image given below -*

![Code Block](/assets/example-code.JPG?raw=true "Title")


### ***Get Response function:***

In the above example, we can see a function getResponse being called, this function returns a body response in a certain format that contains data and acknowledgement to users on success, whereas debugging information on errors.

> public function getResponse($statusCode=null, $data=null, $message=null, $file=null, $line=null, $errors=null)

<br/>

- ### ***statusCode***
This parameter is the error code that we get in the response, typically 200 on success, 500 on error an so on. If this parameter is not defined, then the function sent a default status code of 500 


- ### ***data***
Typically defined when we need to send some data along with the response **(mostly used in status code 200)**.

- ### ***message***
Message parameter contains a string that summarizes the response status.

- ### ***file***
The file in which the error has occurred can be specified in this parameter

- ### ***line***
We can also provide the line on which the error has occurred by ***$e->getLine()*** function in catch block ***(where $e is the object on class Exception)***

- ### ***errors***
This parameter can either be a string or an array errors, this is made standardized so that the front end team don't have to code for different type of datatype and structures

<br/>

### ***Set Custom Response function:***

In a scenario where the developer needs to use their own response and user message instead of the default configured messages, we may use this function to set those parameters in the response.

> public function setCustomResponse($messageTitle=null, $messageText=null, $primaryAction=null, $primaryActionLabel=null, $secondaryAction=null, $secondaryActionLabel=null)

<br/>

- ### ***messageTitle***

First parameter is the message title that is shown to the user.

- ### ***messageText***

This is the text that is shown to the user.

- ### ***primaryAction***

The primary action is something that the user needs to do after they get a response from the api for instance - if a user tries to login with incorrect credentials, the primary action for this response was dismiss and try again with different credentials.

- ### ***primaryActionLabel***

The name on the widget that triggers the primary action

- ### ***secondaryAction***

The secondary action is an alternative action that a user can proceed with like cancel or dismiss and so on.

- ### ***secondaryActionLabel***

The name on the widget that triggers the secondary action

<br/>

### ***Set Custom Error function:***

Where the developer needs to override default errors execution for instance, if a developer is not using default request files for validation and need to validate inside a controller itself, in this situation they may have to overwrite the errors to the validator errors before throwing to the catch block. Here the set custom error function may be useful.

> public function setCustomErrors($errors)

<br/>

- ### ***errors***

The parameter passed to overwrite the default error, this can be either a string or an array (preferably validators errors object)