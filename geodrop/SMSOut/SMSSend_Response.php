<?php
require_once 'GeodropResponse.php';

/**
 * Response to a <CODE>SMSSend</CODE> request
 *
 * @author A-Tono s.r.l.
 * @since 1.0
 *
 *
 */
class SMSSend_Response extends GeodropResponse
{
  /**
   * Code that indicates the result of the request, it is set to 0 on success
   * @var int
   */
  private $code;
  /**
   * Description of error
   * @var string
   */
  private $description;
  /**
   * Total of the requested recipients
   * @var int
   */
  private $report_requested;
  /**
   * Total of the effectively sent SMS messages
   * @var int
   */
  private $report_posted;
  /**
   * Contains a <CODE>Report</CODE> for each recipient's mobile phone number
   * @var Report[]
   */
  private $report_list;

  public function __destruct()
  {
    parent::__destruct();
    unset($this->code);
    unset($this->description);
    unset($this->report_requested);
    unset($this->report_posted);
    unset($this->report_list);
  }
   
  public function toString()
  {
    $result = "code: ";
    $result.= $this->code."\n";
    $result.= "description: ";
    $result.= $this->description."\n";
    $result.= "requested: ";
    $result.= $this->report_requested."\n";
    $result.= "posted: ";
    $result.= $this->report_posted."\n";
    $result.= "report list: \n";
    foreach($this->report_list as $report)
    {
      $result.= $report->toString();
      $result.="\n";
    }
    return $result;
  }
  
  public function fillParameters($http_response)
  {
    //echo "SMSSend::decodeResponse: ".$http_response."\n";
    try
    {
      $xml = new SimpleXMLElement($http_response);
    }
    catch (Exception $e)
    {
      error_log(ErrorType::RESPONSE_NOT_XML);
      return false;
    }
    
    //tag USER if ok, tag BAD if error
    if(isset($xml->BAD))
    {
      $this->code = 		(int)trim($xml->BAD['code']);
      $this->description = 	trim($xml->BAD['description']);
    }
    elseif (isset($xml->USER))
    {
      $this->code 		= 0;
      $this->description 	= "ok";
    }
    
    $this->report_requested = 	(int)trim($xml->REPORT['requested']);
    $this->report_posted = 	(int)trim($xml->REPORT['posted']);
    
    if(isset($xml->REPORT))
    {
      $report_list = array();
      foreach($xml->REPORT->OK as $ok)
      {
	$r = new Report();
	$r->set_order_id(trim($ok['orderid']));
	$r->set_msisdn(trim($ok['msisdn']));
	$r->set_status(0);
	array_push($report_list,$r);
      }
      foreach($xml->REPORT->ERROR as $error)
      {
	$r = new Report();
	$r->set_msisdn(trim($error['msisdn']));
	$r->set_status((int)$error['reason']);
	array_push($report_list,$r);
      }
      $this->report_list = $report_list;
    }
    unset($xml);
    return true;
  }
  
  //getters
  /**
   * Returns the code
   * 
   * @return int Code
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
  /**
   * Returns the total of the requested recipients
   * 
   * @return int Total of the requested recipients
   */
  public function get_report_requested()
  {
    return $this->report_requested;
  }
  /**
   * Returns the total of the effectively sent SMS messages
   * 
   * @return int Total of the effectively sent SMS messages
   */
  public function get_report_posted()
  {
    return $this->report_posted;
  }
  /**
   * Returns the <CODE>Report</CODE> array
   * 
   * @return Report[] The <CODE>Report</CODE> array
   */ 
  public function get_report_list()
  {
    return $this->report_list;
  }
}

?>
