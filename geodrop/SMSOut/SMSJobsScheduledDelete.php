<?php

require_once 'GeodropRequest.php';
require_once 'SMSOut/ActionOutScheduled.php';
require_once 'SMSOut/SMSJobsScheduled_Response.php';

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
      $this->set_job_id($job_id);
   }
    
   public function __destruct()
   {
     parent::__destruct();
     unset($this->job_id);
     unset($this->action);
   }
    
   public function createParams()
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
   
   //getters
   /**
    * Returns the job id of the job to delete
    * @access public
    * @return string
    */
   public function get_job_id()
   {
      return $this->job_id;
   }
   
   //setters
   /**
    * Sets the job id of the job to delete
    * @param string job_id The job id
    * @return void
    */
   public function set_job_id($job_id)
   {
      $this->job_id = $job_id;
   }
}
 
?>
