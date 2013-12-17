<?php

require_once 'GeodropRequest.php';
require_once 'dropOut/ActionOutScheduled.php';
require_once 'dropOut/SMSJobsScheduled_Response.php';

/**
 * Request used to retrieve the overall status of a scheduled job
 *
 * @author A-Tono s.r.l.
 * @since 1.0
 *
 *
 */
class SMSJobsScheduledStatus extends GeodropRequest
{
   /**
    * The action to perform (<CODE>STATUS</CODE>)
    * @var string
    */
   private static $action;
   /**
    * The job id
    * @var string
    */
   private $job_id;
   
   /**
    * Creates a new <CODE>SMSJobsScheduledStatus</CODE> instance
    * 
    * @param string job_id The job id
    *
    * @throws Exception If parameters are not valid
    */
   public function __construct($job_id)
   {
      if(!isset($job_id))
      {
	throw new Exception(ErrorType::MISSING_JOBID);
      }
      
      //set parameters
      $this->uri = Uri::OUT_SMS_JOBS_SCHEDULED;
      $this->httpMethod = HttpMethod::POST;
      $this->contentType = ContentType::XML;
      $this->action = ActionOutScheduled::STATUS;
      $this->job_id = $job_id;
      
      $this->createParams();
   }
   
   public function __destruct()
   {
      parent::__destruct();
      unset($this->action);
      unset($this->job_id);
   }
   
   protected function createParams()
   { 
      //set body
      $this->body = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>';
      $this->body.= '<GEOSMSSCHEDULER>';
      $this->body.= '<JOB JOBID="'.$this->job_id;
      $this->body.= '" ACTION="'.$this->action.'" />';
      $this->body.= '</GEOSMSSCHEDULER>';
  }
    
   public function decodeResponse( $http_response )
   {
     //echo "SMSJobsScheduledStatus::decodeResponse: ".$http_response."\n";
     $this->response = new SMSJobsScheduled_Response();
     return $this->response->fillParameters($http_response);
   }
}
 
?>
