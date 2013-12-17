<?php
require_once 'GeodropResponse.php';

/**
 * Response
 * to a <CODE>SMSStatusJob</CODE> request,
 * to a <CODE>SMSStatusAdhoc</CODE> request and
 * to a <CODE>SMSStatusRange</CODE> request
 *
 * @author A-Tono s.r.l.
 * @since 1.0
 *
 *
 */
class SMSStatus_Response extends GeodropResponse
{
  /**
   * The client id
   * @var string
   */
  private $client_id;
  /**
   * The total of the requested recipients 
   * @var int
   */
  private $report_requested;
  /**
   * Number of effectively sent SMS messages
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
    unset($this->client_id);
    unset($this->report_requested);
    unset($this->report_posted);
    unset($this->report_list);
  }
  
  public function toString()
  {
    $result = "client_id: ";
    $result.= $this->client_id."\n";
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
    //echo $http_response;
    try
    {
      $xml = new SimpleXMLElement($http_response);
    }
    catch (Exception $e)
    {
      error_log(ErrorType::RESPONSE_NOT_XML);
      return false;
    }
    
    $this->client_id 		= trim($xml->ID_CLIENT);
    $this->report_requested 	= (int)trim($xml->REPORT['requested']);
    $this->report_posted 	= (int)trim($xml->REPORT['posted']);
    
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
	if(isset($error['reason']))
	  $r->set_status((int)trim($error['reason']));
	else
	  $r->set_status(-1);
	array_push($report_list,$r);
      }
      $this->report_list = $report_list;
    }
    unset($xml);
    return true;
  }
  
  //getters
  /**
   * Returns the client id
   * 
   * @return string Client id
   */
  public function get_client_id()
  {
    return $this->client_id;
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
