 <?php
require_once 'GeodropRequest.php';
require_once 'dropPay/CustomerWelcome_Response.php';

/**
 * Request used to  subscribe a customer to a specific Port
 *
 * @author A-Tono s.r.l.
 * @since 1.0
 *
 *
 */
class CustomerWelcome extends GeodropRequest
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
   * Pin code sent to customer in challenge action,
   * it is mandatory if transaction was initiated by provider calling challenge,
   * it is not mandatory if transaction was initiated by customer sending MO
   * @var string
   */
  private $pin;
  /**
   * Order id appended by Geodrop
   * @var int
   */
  private $order;
  
  /**
   * Creates a new <CODE>CustomerWelcome</CODE> instanceBuild the CustomerWelcome request
   * 
   * @param int port DropPay port id
   * @param string msisdn Customer phone number
   * @param string custom Unique CP request id
   * @param string text Text to send
   * @param string pin (optional) Pin code
   * @param int order Order id appended by Geodrop
   *
   * @throws Exception If parameters are not valid
   */
  public function __construct($port,$msisdn,$text,$order,$pin=null,$custom="customFromSDK") 
  {
    if(!isset($port,$msisdn,$custom,$text,$order))
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
    
    //check order
    if(!$this->checkIfIntegerOrDigitString($order))
    {
      throw new Exception(ErrorType::MALFORMED_ORDERID);
    }
    
    //set parameters
    $this->uri = Uri::PAY_CUSTOMERS_WELCOME;
    $this->httpMethod = HttpMethod::POST;
    $this->contentType = ContentType::RAW;
    $this->port = $port;
    $this->msisdn = $msisdn;
    $this->custom = $custom;
    $this->text = utf8_encode($text);
    $this->order = $order;
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
    unset($this->order);
  }
  
  protected function createParams()
  {
    //set URI parameters
    $this->params = array(
      "port" 	=> $this->port,
      "msisdn"	=> $this->msisdn,
      "custom" => $this->custom,
      "text" => $this->text,
      "pin" => $this->pin,
      "order" => $this->order,
    );
  }
  
  public function decodeResponse( $http_response )
  {
    //echo "CustomerWelcome::decodeResponse: \n".$http_response."\n";
    $this->response = new CustomerWelcome_Response();
    return $this->response->fillParameters($http_response);
  }
}

?>
