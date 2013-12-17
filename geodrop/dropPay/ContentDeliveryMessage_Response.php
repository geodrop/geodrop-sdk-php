<?php
require_once 'GeodropResponse.php';

/**
 * Response to a <CODE>ContentDeliveryMessage</CODE> request
 *
 * @author A-Tono s.r.l.
 * @since 1.0
 *
 *
 */
class ContentDeliveryMessage_Response extends GeodropResponse
{
  /**
   * To be used to match with notification of listeners
   * @var string
   */
  private $id;
  /**
   * Error code
   * @var int
   */
  private $code;
  /**
   * Verbose description of the error
   * @var int
   */
  private $description; 
  
  
  public function __destruct()
  {
    parent::__destruct();    
    unset($this->id);
    unset($this->code);
    unset($this->description);
  }
  
  public function toString()
  {
    $result = "id: ";
    $result.= $this->id."\n";
    $result.= "code: ";
    $result.= $this->code."\n";
    $result.= "description: ";
    $result.= $this->description;

    return $result;
  }
  
  public function fillParameters($http_response)
  {
    try
    {
      $xml = new SimpleXMLElement($http_response);
    }
    catch (Exception $e)
    {
      error_log(ErrorType::RESPONSE_NOT_XML);
      return false;
    }
    
    $this->id           = trim($xml->pay->{'send-rsp'}['id']);
    $this->code         = (int)trim($xml->pay->{'send-rsp'}['code']);
    $this->description  = trim($xml->pay->{'send-rsp'}['description']);
    
    unset($xml);
    return true;
  }
  
  //getters
  /**
   * Returns the id
   * 
   * @return string Id
   */
  public function get_id()
  {
    return $this->id;
  }
  /**
   * Returns the error code
   * 
   * @return int Error code
   */
  public function get_code()
  {
    return $this->code;
  }
  /**
   * Returns the description of the error
   * 
   * @return string Description of the error
   */
  public function get_description()
  {
    return $this->description;
  }
}

?>
