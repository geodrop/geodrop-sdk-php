 <?php
require_once 'GeodropRequest.php';
require_once 'dropPay/CustomerCheck_Response.php';

/**
 * Request used to return the full status of customer,
 * if the port type is SUBSCRIPTION
 *
 * @author A-Tono s.r.l.
 * @since 1.0
 *
 *
 */
class CustomerCheck extends GeodropRequest
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
   * Creates a new <CODE>CustomerCheck</CODE> instance
   * 
   * @param int port DropPay port id
   * @param string msisdn Customer phone number
   *
   * @throws Exception If parameters are not valid
   */
  public function __construct($port,$msisdn) 
  {
    if(!isset($port,$msisdn))
    {
      throw new Exception(ErrorType::MISSING_PARAMETERS);
    }
    
    //set parameters
    $this->uri = Uri::PAY_CUSTOMERS_CHECK;
    $this->httpMethod = HttpMethod::POST;
    $this->contentType = ContentType::RAW;
    $this->set_port($port);
    $this->set_msisdn($msisdn);
  }
  
  public function __destruct()
  {
    parent::__destruct();    
    unset($this->port);
    unset($this->msisdn);
  }
  
  public function createParams()
  {
    //set URI parameters
    $this->params = array(
      "port" 	=> $this->port,
      "msisdn"	=> $this->msisdn,
    );
  }
  
  public function decodeResponse( $http_response )
  {
    //echo "CustomerCheck::decodeResponse: \n".$http_response."\n";
    $this->response = new CustomerCheck_Response();
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
  
  //setters
  /**
   * Sets the DropPay port id
   * @param int port The DropPay port id
   * @return void
   * @throws Exception If parameters are not valid
   */
  public function set_port($port)
  {
    //check port
    if(!$this->checkIfIntegerOrDigitString($port))
    {
      throw new Exception(ErrorType::MALFORMED_PORT);
    }
    $this->port = $port;
  }
  /**
   * Sets the customer phone number in E.164 format (without +)
   * @param string msisdn The customer phone number in E.164 format (without +)
   * @return void
   * @throws Exception If parameters are not valid
   */
  public function set_msisdn($msisdn)
  {
    //check msisdn
    if(!$this->checkMsisdnE164Format($msisdn,false))
    {
      throw new Exception(ErrorType::MALFORMED_MSISDN);
    }
    $this->msisdn = $msisdn;
  }
}

?>
