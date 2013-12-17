 <?php
require_once 'GeodropRequest.php';
require_once 'dropPay/PortChargePurchases_Response.php';

/**
 * Request used to charge customer with the previously defined price
 * if port type is SUBSCRIPTION
 *
 * @author A-Tono s.r.l.
 * @since 1.0
 *
 *
 */
class PortChargeSubscriptionPurchases extends GeodropRequest
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
   * Subscriber id required in subscription payments
   * @var int
   */
  private $subscriber;
  /**
   * Pin code sent to customer in challenge action,
   * it is mandatory if transaction was initiated by provider calling challenge,
   * it is not mandatory if transaction was initiated by customer sending MO
   * @var string
   */
  private $pin;
  
  /**
   * Creates a new <CODE>PortChargeSubscriptionPurchases</CODE> instance
   * 
   * @param int port DropPay port id
   * @param string msisdn Customer phone number
   * @param string custom Unique CP request id
   * @param string text Text to send
   * @param string pin (optional) Pin code
   * @param int subscriber Subscriber id
   *
   * @throws Exception If parameters are not valid
   */
  public function __construct($port,$msisdn,$text,$subscriber,$pin=null,$custom="customFromSDK") 
  {
    if(!isset($port,$msisdn,$custom,$text,$subscriber))
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
    
    //check subscriber
    if(!$this->checkIfIntegerOrDigitString($subscriber))
    {
      throw new Exception(ErrorType::MALFORMED_SUBSCRIBER);
    }
    
    //set parameters
    $this->uri = Uri::PAY_PORT_CHARGE;
    $this->httpMethod = HttpMethod::POST;
    $this->contentType = ContentType::RAW;
    $this->port = $port;
    $this->msisdn = $msisdn;
    $this->custom = $custom;
    $this->text = utf8_encode($text);
    $this->subscriber = $subscriber;
    $this->pin = $pin;
    $this->createParams();
  }
  
  public function __destruct()
  {
    parent::__destruct();    
    unset($this->port);
    unset($this->msisdn);
    unset($this->custom);
    unset($this->text);
    unset($this->pin);
    unset($this->subscriber);
  }
  
  protected function createParams()
  {
    //set URI parameters
    $this->params = array(
      "port" 	=> $this->port,
      "msisdn"	=> $this->msisdn,
      "custom"	=> $this->custom,
      "text"	=> $this->text,
      "subscriber" => $this->subscriber,
      "pin"	=> $this->pin,
    );
  }
  
  public function decodeResponse( $http_response )
  {
    //echo "PortChargeSubscriptionPurchases::decodeResponse: \n".$http_response."\n";
    $this->response = new PortChargePurchases_Response();
    return $this->response->fillParameters($http_response);
  }
}

?>
