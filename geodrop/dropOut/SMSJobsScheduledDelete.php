<?php

require_once 'GeodropRequest.php';
require_once 'dropOut/ActionOutScheduled.php';
require_once 'dropOut/SMSJobsScheduled_Response.php';

/**
 * Request used to delete a scheduled job 
 *
 * @author A-Tono s.r.l.
 * @since 1.0
 *
 *
 */
class SMSJobsScheduledDelete extends GeodropRequest
{
   /**
    * The action to perform (<CODE>DELETE</CODE>)
    * @var string
    */
   private static $action;
   /**
    * The job id of the job to delete
    * @var string
    */
   private $job_id;
    
   /**
    * Creates a new <CODE>SMSJobsScheduledDelete</CODE> instance
    * 
    * @param string job_id Job id
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
      $this->action = ActionOutScheduled::DELETE;
      $this->job_id = $job_id;
      
      $this->createParams();
   }
    
   public function __destruct()
   {
     parent::__destruct();
     unset($this->job_id);
     unset($this->action);
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
