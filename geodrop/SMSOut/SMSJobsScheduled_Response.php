<?php
require_once 'GeodropResponse.php';

/**
 * Response
 * to a <CODE>SMSJobsScheduledStatus</CODE> request,
 * to a <CODE>SMSJobsScheduledModify</CODE> request and
 * to a <CODE>SMSJobsScheduledDelete</CODE> request
 *
 * @author A-Tono s.r.l.
 * @since 1.0
 *
 *
 */
class SMSJobsScheduled_Response extends GeodropResponse
{
  /**
   * The job id of the job
   * @var string
   */
  private $job_id;
  /**
   * “OK” or “KO” values,
   * indicating respectively the global success or failure of the transaction
   * @var string
   */
  private $job_transaction;
  /**
   * A short description of any problems
   * @var string
   */
  private $job_cause;
  /**
   * Date and time used to send the message
   * @var date
   */
  private $deferred;
  /**
   * Text of the message
   * @var string
   */
  private $message_text;
  /**
   * The personalized sender
   * @var string
   */
  private $tpoa;
  /**
   * The internal code of the error if any
   * @var int
   */
  private $error_code;
  /**
   * The description of the error if any
   * @var string
   */
  private $error_description;
  
  public function __destruct()
  {
    parent::__destruct();
    unset($this->job_id);
    unset($this->job_transaction);
    unset($this->job_cause);
    unset($this->deferred);
    unset($this->message_text);
    unset($this->tpoa);
    unset($this->error_code);
    unset($this->error_description);
  }
  
  public function toString()
  {
    $result = "job id: ";
    $result.= $this->job_id."\n";
    $result.= "job transaction: ";
    $result.= $this->job_transaction."\n";
    $result.= "job cause: ";
    $result.= $this->job_cause."\n";
    $result.= "deferred: ";
    $result.= $this->deferred."\n";
    $result.= "message_text: ";
    $result.= $this->message_text."\n";
    $result.= "tpoa: ";
    $result.= $this->tpoa."\n";
    $result.= "error_code: ";
    $result.= $this->error_code."\n";
    $result.= "error_description: ";
    $result.= $this->error_description;
    return $result;
  }
  
  public function fillParameters($http_response)
  {
    //echo "\n".$http_response."\n";
    try
    {
      $xml = new SimpleXMLElement($http_response);
    }
    catch (Exception $e)
    {
      error_log(ErrorType::RESPONSE_NOT_XML);
      return false;
    }

    $this->job_id           = trim($xml->JOB['JOBID']);
    $this->job_transaction  = trim($xml->JOB['TRANSACTION']);
    $this->job_cause        = trim($xml->JOB['CAUSE']);
    $this->deferred         = trim($xml->DEFERREDTIME);
    $this->message_text     = trim($xml->MESSAGETEXT);
    $this->tpoa             = trim($xml->TPOA);
    $this->error_code       = (int)trim($xml->JOB['ERRORCODE']);
    $this->error_description= ErrorType::getDropOutSMSJobsScheduledErrorDescription($this->error_code);
    
    unset($xml);
    
    return true;
  }
  
  //getters
  /**
   * Returns the job id
   * 
   * @return string Job id
   */
  public function get_job_id()
  {
    return $this->job_id;
  }
  /**
   * Returns the job transaction
   * 
   * @return string Job transaction
   */
  public function get_job_transaction()
  {
    return $this->job_transaction;
  }
  /**
   * Returns the description of any problems
   * 
   * @return string Job cause
   */
  public function get_job_cause()
  {
    return $this->job_cause;
  }
  /**
   * Returns the date and time used to send the message
   * 
   * @return date Date and time used to send the message
   */
  public function get_deferred()
  {
    return $this->deferred;
  }
  /**
   * Returns the text of the message
   * 
   * @return string Text of the message
   */
  public function get_message_text()
  {
    return $this->message_text;
  }
  /**
   * Returns the personalized sender
   * 
   * @return string Personalized sender
   */
  public function get_tpoa()
  {
    return $this->tpoa;
  }
  /**
   * Returns the internal code of the error if any
   * 
   * @return int Internal code of the error
   */
  public function get_error_code()
  {
    return $this->error_code;
  }
  /**
   * Returns the description of the error if any
   * 
   * @return string Description of the error
   */
  public function get_error_description()
  {
    return $this->error_description;
  }
}

?>
