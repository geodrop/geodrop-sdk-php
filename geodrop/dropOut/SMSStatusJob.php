 <?php

require_once 'GeodropRequest.php';
require_once 'dropOut/RequestTypeOutStatus.php';
require_once 'dropOut/SMSStatus_Response.php';

/**
 * Request used to get the overall status of a job
 *
 * @author A-Tono s.r.l.
 * @since 1.0
 *
 *
 */
class SMSStatusJob extends GeodropRequest
{
   /**
    * The request type (<CODE>JOB</CODE>)
    * @var string
    */
   private static $requestType;
   /**
   * The Global Unique IDentifier (GUID) of the job
   * @var string
   */
   private $job_orderid;
   /**
   * Used to paginate the result,
   * it consist of two integers separated by a comma,
   * the first one indicates the position of the first required result
   * and the second the total number of result to return
   * @var string
   */
   private $job_limit;
   /**
   * The client id
   * @var string
   */
   private $client_id;
   
   /**
    * Creates a new <CODE>SMSStatusJob</CODE> instance
    * 
    * @param string job_orderid GUID of the job
    * @param string job_limit (optional) Used to paginate the result
    * @param string client_id (optional) Client id
    *
    * @throws Exception If parameters are not valid
    */
   public function __construct($job_orderid,$client_id=null,$job_limit = "0,100")
   {
      if(!isset($job_orderid))
      {
	throw new Exception(ErrorType::MISSING_ORDERID);
      }
      
      //check job_limit
      if(isset($job_limit))
      {
	 if(!$this->checkLimitFormat($job_limit))
	 {
	    throw new Exception(ErrorType::MALFORMED_LIMIT);
	 }
      }
      
      //set parameters
      $this->uri = Uri::OUT_SMS_STATUS;
      $this->httpMethod = HttpMethod::PUT;
      $this->contentType = ContentType::XML;
      $this->requestType = RequestTypeOutStatus::JOB;
      $this->job_orderid = $job_orderid;
      $this->job_limit = $job_limit;
      $this->client_id = $client_id;
      $this->createParams();
   }
   
   public function __destruct()
   {
      parent::__destruct();
      unset($this->requestType);
      unset($this->job_limit);
      unset($this->job_orderid);
      unset($this->client_id);
   }
   
   protected function createParams()
   { 
      //set body
      $this->body = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>';
      $this->body.= '<GEOSMSSTATUS>';
      if(isset($this->client_id))
      {
	 $this->body.= '<ID_CLIENT>'.$this->client_id.'</ID_CLIENT>';//optional
      }
      $this->body.= '<REQUEST_TYPE>'.$this->requestType.'</REQUEST_TYPE>';
      $this->body.= '<JOB orderid="'.$this->job_orderid;
      $this->body.= '" limit="'.$this->job_limit.'" />';
      $this->body.= '</GEOSMSSTATUS>';
   }
   
   public function decodeResponse( $http_response )
   {
      //echo "SMSStatusRange::decodeResponse: ".$http_response."\n";
      $this->response = new SMSStatus_Response();
      return $this->response->fillParameters($http_response);
   }
 }
 
 ?>
