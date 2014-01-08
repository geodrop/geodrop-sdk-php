 <?php
require_once 'GeodropRequest.php';
require_once 'dropPay/CustomerGoodbye_Response.php';

/**
 * Request used to  unsubscribe a customer to a specific Port
 *
 * @author A-Tono s.r.l.
 * @since 1.0
 *
 *
 */
class CustomerGoodbye extends GeodropRequest
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
   * Creates a new <CODE>CustomerGoodbye</CODE> instance
   * 
   * @param int port DropPay port id
   * @param string msisdn Customer phone number
   * @param string custom Unique CP request id
   * @param string text Text to send
   * @param int subscriber Subscriber id
   *
   * @throws Exception If parameters are not valid
   */
  public function __construct($port,$msisdn,$text,$subscriber,$custom="customFromSDK") 
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
    $this->uri = Uri::PAY_CUSTOMERS_GOODBYE;
    $this->httpMethod = HttpMethod::POST;
    $this->contentType = ContentType::RAW;
    $this->port = $port;
    $this->msisdn = $msisdn;
    $this->custom = $custom;
    $this->text = utf8_encode($text);
    $this->subscriber = $subscriber;
  
    $this->createParams();
  }
  
  public function __destruct()
  {
    parent::__destruct();    
    unset($this->port);
    unset($this->msisdn);
    unset($this->custom);
    unset($this->text);
    unset($this->subscriber);
  }
  
  protected function createParams()
  {
    //set URI parameters
    $this->params = array(
      "port" 	=> $this->port,
      "msisdn"	=> $this->msisdn,
      "custom" => $this->custom,
      "text" => $this->text,
      "subscriber" => $this->subscriber,
    );
  }
  
  public function decodeResponse( $http_response )
  {
    //echo "CustomerGoodbye::decodeResponse: \n".$http_response."\n";
    $this->response = new CustomerGoodbye_Response();
    return $this->response->fillParameters($http_response);
  }
  
  //getters
  /**
   * Returns the DropPay port id
   * @access public
   * @return int
   */
  public function get_port()
  {
    return $this->port;
  }
  /**
   * Returns the customer phone number in E.164 format (without +)
   * @access public
   * @return string
   */
  public function get_msisdn()
  {
    return $this->msisdn;
  }
  /**
   * Returns the unique CP request id
   * @access public
   * @return string
   */
  public function get_custom()
  {
    return $this->custom;
  }
  /**
   * Returns the text to send, encoded in UTF-8, up to 160 characters
   * @access public
   * @return string
   */
  public function get_text()
  {
    return $this->text;
  }
  /**
   * Returns the subscriber id required in subscription payments
   * @access public
   * @return int
   */
  public function get_subscriber()
  {
    return $this->subscriber;
  }
}

?>
