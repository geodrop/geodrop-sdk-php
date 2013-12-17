<?php
require_once 'GeodropResponse.php';

/**
 * Response to a <CODE>PortDescriptor</CODE> request
 *
 * @author A-Tono s.r.l.
 * @since 1.0
 *
 *
 */
class PortDescriptor_Response extends GeodropResponse
{
  /**
   * Array of ports
   * @var Port[]
   */
  private $ports;
  
  public function __destruct()
  {
    parent::__destruct();    
    unset($this->ports);
  }
  
  public function toString()
  {
    $result = "";
    foreach($this->ports as $key => $value)
    {
      $result.= "Port ".$key."\n";
      $result.= $value->toString()."\n";
    }
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
    
    $ports = array();
    if(isset($xml->pay->ports))
    {
      foreach($xml->pay->ports->port as $port)
      {     
	$p = $this->fillPortParameters($port);
	//add a port
	$ports[(string)trim($port['id'])] = $p;
      }  
    }
    else
    {
      $p = $this->fillPortParameters($xml->pay->port);
      $ports[(string)trim($xml->pay->port['id'])] = $p;
    }
    unset($xml);
    $this->ports = $ports;
    return true;
  }
  
  /**
   * Performs the parsing of the http response
   * and fills the field of a single <CODE>Port</CODE> object
   *
   * @param string http_response Response to the http request, relating to the port
   *  
   * @return boolean Returns <CODE>true</CODE> on success, <CODE>false</CODE> otherwise
   */
  private function fillPortParameters($port)
  {
    //Port object
    $p = new Port();
    $p->set_drop(trim($port['drop']));
    $p->set_alias(trim($port->alias));
    $p->set_price_tag(trim($port->price['tag']));
    $p->set_price_value(trim($port->price['value']));
    $p->set_type(trim($port['type']));
    $p->set_open_since(trim($port->open['since']));
    $p->set_open_until(trim($port->open['until']));
    $p->set_open_state(trim($port->open['state']));
    //mno_list
    $mnos = array();
    foreach($port->mno as $mno)
    {
      $m = new Mno();
      $m->set_brand(trim($mno['brand']));
      $m->set_mcnc(trim($mno['mcnc']));
      $m->set_operator(trim($mno['operator']));
      $m->set_shortcode(trim($mno['shortcode']));
      array_push($mnos, $m);
    }
    $p->set_mno_list($mnos);
    //subscription
    $p->set_subscription_max(trim($port->subscription['max']));
    $p->set_subscription_period(trim($port->subscription['period']));
    //listener_list
    $listeners = array();
    foreach($port->events->listener as $listener)
    {
      $l = new Listener();
      $l->set_uri(trim($listener['URI']));
      $l->set_type(trim($listener['type']));
      $l->set_lclass(trim($listener['class']));
      array_push($listeners, $l);
    }
    $p->set_listener_list($listeners);
    
    return $p;
  }
  
  //getters
  /**
   * Returns the ports
   * 
   * @return Port[] Ports
   */
  public function get_ports()
  {
    return $this->ports;
  }
}

?>
