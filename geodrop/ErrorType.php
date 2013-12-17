<?php
/**
 * Contains the description of possible errors
 *
 * @author A-Tono s.r.l.
 * @since 1.0
 *
 */
final class ErrorType
{
  const MISSING_PARAMETERS        = "Error: one or more required parameters are not specified!\n";
  const MISSING_REQUEST           = "Error: request must be created!\n";
  const MISSING_ORDERID           = "Error: insert order id!\n";
  const MISSING_JOBID             = "Error: insert job id!\n";
  
  const MALFORMED_PARAMETERS      = "Error: malformed parameters!\n";
  const MALFORMED_OR_PAST_TIME    = "Error: time is past or malformed!\n";
  const MALFORMED_TIME            = "Error: time is malformed!\n";
  const MALFORMED_LIMIT           = "Error: limit is malformed!\n";
  const MALFORMED_MSISDN          = "Error: msisdn malformed!\n";
  const MALFORMED_MSISDNS         = "Error: msisdns malformed!\n";
  const MALFORMED_ORDERID         = "Error: order id malformed!\n";
  const MALFORMED_SUBSCRIBER      = "Error: subscriber malformed!\n";
  const MALFORMED_TEXT_CHALLENGE  = "Error: text must contain $\$PIN$$!\n";
  const MALFORMED_PORT            = "Error: port number must be an integer!\n";
  
  const TOKEN_NOT_VALID           = "Error: invalid token!\n";
  const AUTHENTICATION_FAILED     = "Error: authentication failed!\n";
  const INVALID_HTTP_RESPONSE     = "Error: invalid http response!\n";
  const RESPONSE_NOT_XML          = "Error: response not be parsed as XML!\n";
  
  
  /**
   * Returns the verbose description of a DropPay error code
   *
   * @param string code Error code
   * @return string Verbose description of the error
   */
  public static function getDropPayErrorDescription($code)
  {
    $errorDropPay = array (
      "0"     =>  "OK",
      "-99"   =>  "Internal Error",
      "-1"    =>  "Invalid Port ID",
      "-2"    =>  "Invalid msisdn",
      "-3"    =>  "Invalid custom id",
      "-4"    =>  "Invalid text",
      "-5"    =>  "Invalid subscriber id",
      "-6"    =>  "Invalid order id",
      "-7"    =>  "Customer Blacklisted",
      "-8"    =>  "Customer Already Active",
      "-9"    =>  "Customer Not Active",
      "-10"   =>  "Max free ratio message number reached",
      "-11"   =>  "Was not possible subscribe user",
      "-12"   =>  "Was not possible unsubscribe user",
      "-13"   =>  "Invalid Geodrop user",
      "-14"   =>  "Invalid XML with DropPay Geodrop Descriptor",
      "-15"   =>  "Invalid Date Format",
      "-16"   =>  "Invalid content id",
    );
    return $errorDropPay[(string)$code];
  }
  
  /**
   * Returns the verbose description of a DropOut
   * error code of a <CODE>SMSJobsScheduled_Response</CODE>
   *
   * @param string code Error code
   * @return string Verbose description of the error
   */
  public static function getDropOutSMSJobsScheduledErrorDescription($code)
  {
    $error = array (
      "0"       =>  "OK",
      "1"       =>  "XML malformed",
      "2"       =>  "XML ok",
      "3"       =>  "Deferred time date out",
      "4"       =>  "Deferred time date too old",
      "5"       =>  "Deferred time bad",
      "6"       =>  "Text not valid",
      "7"       =>  "Text too long",
      "8"       =>  "Text empty",
      "9"       =>  "Msisdn list empty",
      "10"      =>  "Msisdn list too long",
      "11"      =>  "Drop unavailable",
      "12"      =>  "Message malformed",
      "13"      =>  "Invalid action",
      "14"      =>  "Invalid job id",
      "15"      =>  "Invalid tpoa",
      "16"      =>  "Invalid tid",
      "17"      =>  "Internal error",
      "18"      =>  "Job not found",
      "19"      =>  "Invalid run range",
      "20"      =>  "invalid notify url",
    );
    return $error[(string)$code];
  }
}


?>
