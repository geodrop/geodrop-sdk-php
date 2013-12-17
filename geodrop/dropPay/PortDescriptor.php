 <?php
require_once 'GeodropRequest.php';
require_once 'dropPay/PortDescriptor_Response.php';
require_once 'dropPay/Port.php';
require_once 'dropPay/Mno.php';
require_once 'dropPay/Listener.php';

/**
 * Request used to return the full Port configuration's properties
 *
 * @author A-Tono s.r.l.
 * @since 1.0
 *
 */
class PortDescriptor extends GeodropRequest
{
  /**
   * DropPay port id
   * @var int
   */
  private $port;
  
  /**
   * Creates a new <CODE>PortDescriptor</CODE> instance
   * 
   * @param int port (optional) DropPay port id
   *
   * @throws Exception If parameters are not valid
   */
  public function __construct($port = null) 
  {
    //check port
    if(isset($port))
    {
      if(!$this->checkIfIntegerOrDigitString($port))
      {
        throw new Exception(ErrorType::MALFORMED_PORT);
      }
    }
    
    //set parameters
    $this->uri = Uri::PAY_PORT_DESCRIPTOR;
    $this->httpMethod = HttpMethod::POST;
    $this->contentType = ContentType::RAW;
    $this->port = $port;
    $this->createParams();
  }
  
  public function __destruct()
  {
    parent::__destruct();    
    unset($this->port);
  }
  
  protected function createParams()
  {
    //set URI parameters
    $this->params = array(
      "port" => $this->port,
    );
  }
  
  public function decodeResponse( $http_response )
  {
    //echo "PortDescriptor::decodeResponse: \n".$http_response."\n";
    $this->response = new PortDescriptor_Response();
    return $this->response->fillParameters($http_response);
  }
}

?>
