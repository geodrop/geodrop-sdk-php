 <?php
require_once 'GeodropRequest.php';
require_once 'dropPay/ContentDeliveryMessage_Response.php';

/**
 * Request used to
 * send a content as a smart message free of charge for customer
 *
 * @author A-Tono s.r.l.
 * @since 1.0
 *
 *
 */
class ContentDeliveryMessage extends GeodropRequest
{
  /**
   * DropPay port id
   * @var int
   */
  private $port;
  /**
   * Customer phone number in E.164 format (without +)
   * @var string
   */
  private $msisdn;
  /**
   * Unique CP request id
   * @var string
   */
  private $custom;
  
  /**
   * Text to send, encoded in UTF-8, up to 160 characters
   * @var string
   */
  private $text;
  
  /**
   * Creates a new <CODE>ContentDeliveryMessage</CODE> instance
   * 
   * @param int port DropPay port id
   * @param string msisdn Customer phone number
   * @param string custom Unique CP request id
   * @param string text Text to send
   *
   * @throws Exception If parameters are not valid
   */
  public function __construct($port,$msisdn,$text,$custom="customFromSDK") 
  {
    if(!isset($port,$msisdn,$text,$custom))
    {
        throw new Exception(ErrorType::MISSING_PARAMETERS);
    }
    
    //check port
    if(!$this->checkIfIntegerOrDigitString($port))
    {
      throw new Exception(ErrorType::MALFORMED_PORT);
    }
    
    //check msisdn
    if(!$this->checkMsisdnE164Format($msisdn,false))
    {
      throw new Exception(ErrorType::MALFORMED_MSISDN);
    }
    
    //set parameters
    $this->uri = Uri::PAY_CONTENT_MESSAGE;
    $this->httpMethod = HttpMethod::POST;
    $this->contentType = ContentType::RAW;
    $this->port = $port;
    $this->msisdn = $msisdn;
    $this->custom = $custom;
    $this->text = utf8_encode($text);
  
    $this->createParams();
  }
  
  public function __destruct()
  {
    parent::__destruct();    
    unset($this->port);
    unset($this->msisdn);
    unset($this->custom);
    unset($this->text);
  }
  
  protected function createParams()
  {
    //set URI parameters
    $this->params = array(
      "port" 	=> $this->port,
      "msisdn"	=> $this->msisdn,
      "custom" => $this->custom,
      "text" => $this->text,
    );
  }
  
  public function decodeResponse( $http_response )
  {
    //echo "ContentDeliveryMessage::decodeResponse: \n".$http_response."\n";
    $this->response = new ContentDeliveryMessage_Response();
    return $this->response->fillParameters($http_response);
  }
}

?>
