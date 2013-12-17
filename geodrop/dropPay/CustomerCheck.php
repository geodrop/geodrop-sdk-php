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
    $this->uri = Uri::PAY_CUSTOMERS_CHECK;
    $this->httpMethod = HttpMethod::POST;
    $this->contentType = ContentType::RAW;
    $this->port = $port;
    $this->msisdn = $msisdn;
    $this->createParams();
  }
  
  public function __destruct()
  {
    parent::__destruct();    
    unset($this->port);
    unset($this->msisdn);
  }
  
  protected function createParams()
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
}

?>
