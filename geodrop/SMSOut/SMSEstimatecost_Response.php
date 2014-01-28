<?php
require_once 'GeodropResponse.php';

/**
 * Response to a <CODE>SMSEstimatecost</CODE> request
 *
 * @author A-Tono s.r.l.
 * @since 1.0
 *
 *
 */
class SMSEstimatecost_Response extends GeodropResponse
{
  /**
   * Estimate of the final cost of the message
   * @var int
   */
  private $estimate_cost;
 
  public function __destruct()
  {
    parent::__destruct();
    unset($this->estimate_cost);
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
    
    $this->estimate_cost = (int)trim($xml->ESTIMATECOST);
    unset($xml);
    return true;
  }
  
  public function toString()
  {
    return $this->estimate_cost;
  }
  
  //getters
  /**
   * Returns the estimate of the final cost of the message
   *
   * @access public
   * @return int
   */
  public function getEstimateCost()
  {
    return $this->estimate_cost;
  }
}

?>
