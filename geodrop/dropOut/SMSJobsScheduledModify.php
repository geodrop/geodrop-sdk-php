<?php

require_once 'GeodropRequest.php';
require_once 'dropOut/ActionOutScheduled.php';
require_once 'dropOut/SMSJobsScheduled_Response.php';

/**
 * Request used to modify a scheduled message,
 * adding or removing recipients,
 * changing its message text,
 * its custom sender or its scheduled time
 *
 * @author A-Tono s.r.l.
 * @since 1.0
 *
 *
 */
class SMSJobsScheduledModify extends GeodropRequest
{
   /**
    * The action to perform (<CODE>MODIFY</CODE>)
    * @var string
    */
   private static $action;
   /**
    * The job id of the job to modify
    * @var string
    */
   private $job_id;
   /**
    * Text of the message
    * @var string
    */
   private $message_text;
   /**
    * Array of msisdns to delete; each msisdn is in E.164 format with '+'
    * @var string[]
    */
   private $msisdn_to_delete;
   /**
    * Array of msisdns to add; each msisdn is in E.164 format with '+'
    * @var string[]
    */
   private $msisdn_to_add;
   /**
    * Used to specify the personalized sender
    * @var string
    */
   private $tpoa;
   /**
    * Date and time in the format "Y-m-d H:i:s",
    * used to send the message to a certain date,
    * if not specified the message is sent immediately
    * @var date
    */
   private $deferred_time;
   
   /**
    * Creates a new <CODE>SMSJobsScheduledModify</CODE> instance
    * 
    * @param string job_id The job id of the job to modify
    * @param string deferred (optional) Date and time in the format "Y-m-d H:i:s"
    * @param string message_text (optional) Text of the message
    * @param array msisdn_to_delete (optional) Array of msisdns to delete
    * @param array msisdn_to_add (optional) Array of msisdns to add
    * @param string tpoa (optional) Personalized sender
    *
    * @throws Exception If parameters are not valid
    */
   public function __construct($job_id,$deferred_time=null,$message_text=null,$msisdn_to_delete=null,$msisdn_to_add=null,$tpoa = null)
   {
      //check job_id
      if(!isset($job_id))
      {
	throw new Exception(ErrorType::MISSING_JOBID);
      }

      //check deferred
      if(isset($deferred_time))
      {
	 if(!$this->checkIfFutureDatetime($deferred_time))
	 {
	    throw new Exception(ErrorType::MALFORMED_OR_PAST_TIME);
	 }
      }
      
      //check msisdn_to_delete
      if(isset($msisdn_to_delete))
      {
	 if(!$this->checkMsisdnsArray($msisdn_to_delete,true))
	 {
	    throw new Exception(ErrorType::MALFORMED_MSISDNS);
	 }
      }
      //check msisdn_to_add
      if(isset($msisdn_to_add))
      {
	 if(!$this->checkMsisdnsArray($msisdn_to_add,true))
	 {
	    throw new Exception(ErrorType::MALFORMED_MSISDNS);
	 }
      }
      
      //set parameters
      $this->uri = Uri::OUT_SMS_JOBS_SCHEDULED;
      $this->httpMethod = HttpMethod::POST;
      $this->contentType = ContentType::XML;
      $this->action = ActionOutScheduled::MODIFY;
      $this->job_id = $job_id;
      $this->message_text = utf8_encode($message_text);
      $this->msisdn_to_delete = $msisdn_to_delete;
      $this->msisdn_to_add = $msisdn_to_add;
      $this->tpoa = $tpoa;
      if(isset($deferred_time))
	 $this->deferred_time = date('Y-m-d H:i:s',strtotime(trim($deferred_time)));
      $this->createParams();
   }
   
   public function __destruct()
   {
      parent::__destruct();
      unset($this->action);
      unset($this->job_id);
      unset($this->action);
      unset($this->job_id);
      unset($this->message_text);
      unset($this->msisdn_to_delete);
      unset($this->msisdn_to_add);
      unset($this->tpoa);
      unset($this->deferred_time);
   }
   
   protected function createParams()
   { 
      //set body
      $this->body = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>';
      $this->body.= '<GEOSMSSCHEDULER>';
      $this->body.= '<JOB JOBID="'.$this->job_id;
      $this->body.= '" ACTION="'.$this->action.'">';
      //set message_text
      if(isset($this->message_text))
      {
	 $this->body.= '<MESSAGETEXT><![CDATA['.$this->message_text.']]></MESSAGETEXT>';
      }
      $this->body.= '<LISTMSISDN>';
      //set msisdn_to_add
      if(isset($this->msisdn_to_add))
      {
	 foreach($this->msisdn_to_add as $a)
	 {
	   $this->body.='<ADD msisdn="'.$a.'" />';
	 }
      }
      //set msisdn_to_delete
      if(isset($this->msisdn_to_delete))
      {
	 foreach($this->msisdn_to_delete as $d)
	 {
	   $this->body.='<DEL msisdn="'.$d.'" />';
	 }
      }
      $this->body.= '</LISTMSISDN>';
      //set tpoa
      if(isset($this->tpoa))
      {
	 $this->body.= '<TPOA>'.$this->tpoa.'</TPOA>';
      }
      //set deferred_time
      if(isset($this->deferred_time))
      {
	 $this->body.= '<DEFERREDTIME>'.$this->deferred_time.'</DEFERREDTIME>';
      }
      $this->body.= '</JOB>';
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
    * Returns the job id of the job to modify
    * @access public
    * @return string
    */
   public function get_job_id()
   {
      return $this->job_id;
   }
   /**
    * Returns the text of the message
    * @access public
    * @return string
    */
   public function get_message_text()
   {
      return $this->message_text;
   }
   /**
    * Returns the array of msisdns to delete; each msisdn is in E.164 format with '+'
    * @access public
    * @return string[]
    */
   public function get_msisdn_to_delete()
   {
      return $this->msisdn_to_delete;
   }
   /**
    * Returns the array of msisdns to add; each msisdn is in E.164 format with '+'
    * @access public
    * @return string[]
    */
   public function get_msisdn_to_add()
   {
      return $this->get_msisdn_to_add;
   }
   /**
    * Returns the personalized sender
    * @access public
    * @return string
    */
   public function get_tpoa()
   {
      return $this->tpoa;
   }
   /**
    * Returns the date and time in the format "Y-m-d H:i:s",
    * used to send the message to a certain date,
    * if not specified the message is sent immediately
    * @access public
    * @return date
    */
   public function get_deferred_time()
   {
      return $this->deferred_time;
   }
}

?>
