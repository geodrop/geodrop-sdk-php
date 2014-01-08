 <?php
require_once 'GeodropRequest.php';
require_once 'dropPay/PortChallenge_Response.php';

/**
 * Request used to challenge customer to confirm the telephone number
 * in content provider initiated transaction
 *
 * @author A-Tono s.r.l.
 * @since 1.0
 *
 *
 */
class PortChallenge extends GeodropRequest
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
   * Text to send, encoded in UTF-8, up to 160 characters,
   * it must contain $$PIN$$ placeholder
   * @var string
   */
  private $text;
  
  /**
   * Creates a new <CODE>PortChallenge</CODE> instance
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
    
    //check text
    {
      if(strstr($text,'$$PIN$$') == false)
      {
        throw new Exception(ErrorType::MALFORMED_TEXT_CHALLENGE);
      }
    }
    
    //set parameters
    $this->uri = Uri::PAY_PORT_CHALLENGE;
    $this->httpMethod = HttpMethod::POST;
    $this->contentType = ContentType::RAW;
    $this->port = $port;
    $this->msisdn = $msisdn;
    $this->custom = $custom;
    $this->text = utf8_encode($text);
    $this->createParams();
  }
  
  protected function createParams()
  {
    //set URI parameters
    $this->params = array(
      "port" 	=> $this->port,
      "msisdn"	=> $this->msisdn,
      "custom"	=> $this->custom,
      "text"	=> $this->text,
    );
  }
  
  public function __destruct()
  {
    parent::__destruct();    
    unset($this->port);
    unset($this->msisdn);
    unset($this->custom);
    unset($this->text);
  }
  
  public function decodeResponse( $http_response )
  {
    //echo "PortChallenge::decodeResponse: \n".$http_response."\n";
    $this->response = new PortChallenge_Response();
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
   * Returns the text to send, encoded in UTF-8, up to 160 characters,
   * it must contain $$PIN$$ placeholder
   * @access public
   * @return string
   */
  public function get_text()
  {
    return $this->text;
  }
}

?>
