 <?php

require_once 'GeodropRequest.php';
require_once 'SMSOut/RequestTypeOutStatus.php';
require_once 'SMSOut/SMSStatus_Response.php';

/**
 * Request used to retrieve the status of any set of SMS messages
 *
 * @author A-Tono s.r.l.
 * @since 1.0
 *
 *
 */
class SMSStatusAdhoc extends GeodropRequest
{
   /**
    * The request type (<CODE>ADHOC</CODE>)
    * @var string
    */
   private static $requestType;
   /**
    * Array of pair <order id, msisdn>
    * that uniquely identifies a single SMS message in the Geodrop archive
    * @var string[][]
    */
   private $dest;
   /**
    * The client id
    * @var string
    */
   private $client_id;
   
   /**
    * Creates a new <CODE>SMSStatusAdhoc</CODE> instance
    * 
    * @param string[][] dest Array of pair <order id, msisdn>
    * @param string client_id (optional) Client id
    *
    * @throws Exception If parameters are not valid
    */
   public function __construct($dest,$client_id=null)
   {
      if(!isset($dest))
      {
	throw new Exception(ErrorType::MISSING_PARAMETERS);
      }
      
      //set parameters
      $this->uri = Uri::OUT_SMS_STATUS;
      $this->httpMethod = HttpMethod::PUT;
      $this->contentType = ContentType::XML;
      $this->requestType = RequestTypeOutStatus::ADHOC;
      $this->set_dest($dest);
      $this->client_id = $client_id;
   }
    
   public function __destruct()
   {
      parent::__destruct();
      unset($this->requestType);
      unset($this->dest);
      unset($this->client_id);
   }
   
   public function createParams()
   { 
      //set body
      $this->body = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>';
      $this->body.= '<GEOSMSSTATUS>';
      if(isset($this->client_id))
      {
	 $this->body.= '<ID_CLIENT>'.$this->client_id.'</ID_CLIENT>';//optional
      }
      $this->body.= '<REQUEST_TYPE>'.$this->requestType.'</REQUEST_TYPE>';
      //$dest = <orderid,msisdn>
      $this->body.= '<LIST>';
      foreach($this->dest as $tuple)
      {
	 $this->body.= '<DEST msisdn="'.$tuple[1].'" orderid="'.$tuple[0].'"/>';
      }
      $this->body.= '</LIST>';
      $this->body.= '</GEOSMSSTATUS>';
   }
    
   public function decodeResponse( $http_response )
   {
      //echo "SMSStatusRange::decodeResponse: ".$http_response."\n";
      $this->response = new SMSStatus_Response();
      return $this->response->fillParameters($http_response);
   }
   
   //getters
   /**
    * Returns the array of pair <order id, msisdn>
    * that uniquely identifies a single SMS message in the Geodrop archive
    * @access public
    * @return string[][]
    */
   public function get_dest()
   {
      return $this->dest;
   }
   /**
    * Returns the client id
    * @access public
    * @return string
    */
   public function get_client_id()
   {
      return $this->client_id;
   }
   
   //setters
   /**
    * Sets the array of pair <order id, msisdn>
    * that uniquely identifies a single SMS message in the Geodrop archive
    * @param string[][] dest The array of pair <order id, msisdn>
    * @return void
    * @throws Exception If parameters are not valid
    */
   public function set_dest($dest)
   {
      //check dest
      if(!$this->checkIfArrayOfPairOfOrderIdMsisdn($dest,true))
      {
	throw new Exception(ErrorType::MALFORMED_PARAMETERS);
      }
      $this->dest = $dest;
   }
   /**
    * Sets the client id
    * @param string client_id The client id
    * @return void
    */
   public function set_client_id($client_id)
   {
      $this->client_id = $client_id;
   }
 }
 
 ?>
