<?php

namespace TechnonextPlugin;

use Throwable;

/**
 * This php file contains a custom exception class
 * Here , a constructor is used for get throwing string , exception instance and others
 * Inside that constructor customFunction() and exceptionInfo() called
 * Here exceptionInfo() method used from ExceptionInfoService trait
 *
 * @author Technonext Software Pvt Ltd
 * @since 2026-01-01
 */
class TechnonextpayException extends \Exception
{
    // Redefine the exception so message isn't optional
    public function __construct($message, $code = 0, $e = null, Throwable $previous = null)
    {
        $this->customFunction();
        $this->exceptionInfo($message, $e);
        // make sure everything is assigned properly
        parent::__construct($message, $code, $previous);

    }

// This is a method which prints a default exception for every custom exception message.
    public function customFunction()
    {
        echo "<u> <br> <h3> A Technonextpay Message For This Type Of Exception :- </h3>  </u>";
    }

    public function exceptionInfo($message, $e)
    {
        if ($e instanceof \Throwable) {
            $System_error_msg = $e->getMessage();
            $problem_file_name = $e->getFile();
            $problem_LineNumber = $e->getLine();
        } else {
            $System_error_msg = 'No additional error information';
            $problem_file_name = 'Unknown';
            $problem_LineNumber = 'Unknown';
        }

        $Technonextpay_help = $message;
        $myResponse = [
            "System_error_msg" => $System_error_msg,
            "TechnonextPay_help" => $Technonextpay_help,
            "problem_file_name" => $problem_file_name,
            "problem_LineNumber" => $problem_LineNumber
        ];

        foreach ($myResponse as $attribute => $value) {
            echo $attribute . "=>" . $value . "<br>";
        }
        exit;
    }


}
