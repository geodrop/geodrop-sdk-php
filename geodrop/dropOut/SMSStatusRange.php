 <?php

require_once 'GeodropRequest.php';
require_once 'dropOut/RequestTypeOutStatus.php';
require_once 'dropOut/SMSStatus_Response.php';

/**
 * Request used to retrieve
 * the status of all SMS messages sent in a given time interval.
 *
 * @author A-Tono s.r.l.
 * @since 1.0
 *
 *
 */
class SMSStatusRange extends GeodropRequest
{
   /**
    * The request type (<CODE>RANGE</CODE>)
    * @var string
    */
   private static $requestType;
   /**
    * End date of the time interval
    * @var date
    */
   private $range_dateTo;
   /**
    * Start date of the time interval
    * @var date
    */
   private $range_dateFrom;
   /**
   * Used to paginate the result,
   * it consist of two integers separated by a comma,
   * the first one indicates the position of the first required result
   * and the second the total number of result to return
   * @var string
   */
   private $range_limit;
   /**
    * The client id
    * @var string
    */
   private $client_id;
   
   /**
    * Creates a new <CODE>SMSStatusRange</CODE> instance
    * 
    * @param date range_dateFrom Start date
    * @param date range_dateTo End date
    * @param float job_limit (optional) Used to paginate the result
    * @param stringORint client_id (optional) Client id
    *
    * @throws Exception If parameters are not valid
    */
   public function __construct($range_dateFrom,$range_dateTo,$client_id=null,$range_limit = "0,100")
   {
      if(!isset($range_dateTo,$range_dateFrom))
      {
	throw new Exception(ErrorType::MISSING_PARAMETERS);
      }
      
      //check range_dateFrom and range_dateTo
      if(!$this->checkDatetimeFormat($range_dateFrom) || !$this->checkDatetimeFormat($range_dateTo))
      {
	throw new Exception(ErrorType::MALFORMED_TIME);
      }
      
      //check range_limit
      if(isset($range_limit))
      {
	 if(!$this->checkLimitFormat($range_limit))
	 {
	    throw new Exception(ErrorType::MALFORMED_LIMIT);
	 }
      }

      //set parameters
      $this->uri = Uri::OUT_SMS_STATUS;
      $this->httpMethod = HttpMethod::PUT;
      $this->contentType = ContentType::XML;
      $this->requestType = RequestTypeOutStatus::RANGE;
      $this->range_dateTo = date('Y-m-d H:i:s',strtotime(trim($range_dateTo)));
      $this->range_dateFrom = date('Y-m-d H:i:s',strtotime(trim($range_dateFrom)));
      $this->range_limit = $range_limit;
      $this->client_id = $client_id;
      $this->createParams();
   }
   
   public function __destruct()
   {
      parent::__destruct();      
      unset($this->requestType);
      unset($this->range_dateTo);
      unset($this->range_dateFrom);
      unset($this->range_limit);
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
      $this->body.= '<RANGE date_from="'.$this->range_dateFrom;
      $this->body.= '" date_to="'.$this->range_dateTo;
      $this->body.= '" limit="'.$this->range_limit.'" />';
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
