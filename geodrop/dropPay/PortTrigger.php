 <?php
require_once 'GeodropRequest.php';
require_once 'dropPay/PortTrigger_Response.php';

/**
 * Request used to charge all subscribed customers
 * with the previously defined price
 *
 * @author A-Tono s.r.l.
 * @since 1.0
 *
 */
class PortTrigger extends GeodropRequest
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
   * Time to schedule trigger, default is now
   * @var date
   */
  private $time;
  
  /**
   * Creates a new <CODE>PortTrigger</CODE> instance
   * 
   * @param int port DropPay port id
   * @param string msisdn Customer phone number
   * @param string custom Unique CP request id
   * @param string text Text to send
   * @param date time (optional) Time to schedule trigger
   *
   * @throws Exception If parameters are not valid
   */
  public function __construct($port,$msisdn,$text,$time=null,$custom="customFromSDK")
  {
    if(!isset($port,$msisdn,$custom,$text))
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
    
    //check time
    if(isset($time))
    {
      if(!$this->checkIfFutureDatetime($time))
      {
	throw new Exception(ErrorType::MALFORMED_OR_PAST_TIME);
      }
    }
    
    //set parameters
    $this->uri = Uri::PAY_PORT_TRIGGER;
    $this->httpMethod = HttpMethod::POST;
    $this->contentType = ContentType::RAW;
    $this->port = $port;
    $this->msisdn = $msisdn;
    $this->custom = $custom;
    $this->text = utf8_encode($text);
    if(isset($time))
      $this->time = date('Y-m-d H:i:s',strtotime(trim($time)));
    $this->createParams();
  }
  
  public function __destruct()
  {
    parent::__destruct();    
    unset($this->port);
    unset($this->msisdn);
    unset($this->custom);
    unset($this->text);
    unset($this->time);
  }
  
  protected function createParams()
  {
    //set URI parameters
    $this->params = array(
      "port" 	=> $this->port,
      "msisdn"	=> $this->msisdn,
      "custom"	=> $this->custom,
      "text"	=> $this->text,
      "time"	=> $this->time,
    );
  }
  
  public function decodeResponse( $http_response )
  {
    //echo "PortTrigger::decodeResponse: \n".$http_response."\n";
    $this->response = new PortTrigger_Response();
    return $this->response->fillParameters($http_response);
  }
}

?>
