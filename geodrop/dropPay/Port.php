<?php
/**
 * Port configuration's properties
 *
 * @author A-Tono s.r.l.
 * @since 1.0
 *
 */
class Port
{
  /**
   * Specifies the port type (pay or premium)
   * @var string
   */
  private $drop;
  /**
   * An unique name you can assign to port, suitable as keyword in alternative to port number (id)
   * @var string
   */
  private $alias;
  /**
   * Identifier of price valid for this port
   * @var string
   */
  private $price_tag;
  /**
   * Price value corresponding to the tag
   * @var float
   */
  private $price_value;
  /**
   * Type of port that can be ONDEMAND or SUBSCRIPTION
   * @var string
   */
  private $type;
  /**
   * Date and time since when port results active
   * @var date
   */
  private $open_since;
  /**
   * Date and time when port will be disabled
   * @var date
   */
  private $open_until;
  /**
   * State of port, can be ACTIVE or SUSPENDED if any compliance issues have been raised
   * @var string
   */
  private $open_state;
  /**
   * Each of MNO item indicates MNO settings for which the payment port is enabled
   * @var Mno[]
   */
  private $mno_list;
  /**
   * Max amount of money the customer can be charged within the "period"
   * @var float
   */
  private $subscription_max;
  /**
   * Time window that limit the "max" amount of money the customer can be charged in days
   * @var int
   */
  private $subscription_period;
  /**
   * Settings that content provider can modify during the lifetime of the service,
   * regarding event notification receiving
   * @var Listener[]
   */
  private $listener_list;
     
  public function __destruct()
  {
    unset($this->drop);
    unset($this->alias);
    unset($this->price_tag);
    unset($this->price_value);
    unset($this->type);
    unset($this->open_since);
    unset($this->open_until);
    unset($this->open_state);
    unset($this->mno_list);
    unset($this->subscription_max);
    unset($this->subscription_period);
    unset($this->listener_list);
  }
  
  /**
   * Returns a string representation of the object;
   * in general, the toString method returns a string that "textually represents" this object;
   * the result should be a concise but informative representation that is easy for a person to read
   * 
   * @return string A string rapresentation of the object
   */
  public function toString()
  {
    $result = "drop: ";
    $result.= $this->drop."\n";
    $result.= "alias: ";
    $result.= $this->alias."\n";
    $result.= "price_tag: ";
    $result.= $this->price_tag."\n";
    $result.= "price_value: ";
    $result.= $this->price_value."\n";
    $result.= "type: ";
    $result.= $this->type."\n";
    $result.= "open_since: ";
    $result.= $this->open_since."\n";
    $result.= "open_until: ";
    $result.= $this->open_until."\n";
    $result.= "open_state: ";
    $result.= $this->open_state."\n";
    $result.= "mno list: \n";
    foreach($this->mno_list as $mno)
    {
      $result.= $mno->toString()."\n";
    }
    $result.= "subscription_max: ";
    $result.= $this->subscription_max."\n";
    $result.= "subscription_period: ";
    $result.= $this->subscription_period."\n";
    $result.= "listener_list: \n";
    foreach($this->listener_list as $l)
    {
      $result.= $l->toString()."\n";
    }
    
    return $result;
  }
     
  //setters
  /**
   * Set the drop
   *
   * @param string drop Drop
   * @return void
   */
  public function set_drop($drop)
  {
    $this->drop = trim($drop);
  }
  
  /**
   * Set the alias
   *
   * @param string alias Alias
   * @return void
   */
  public function set_alias($alias)
  {
    $this->alias = trim($alias);
  }
  
  /**
   * Set the price tag
   *
   * @param string price_tag Price tag
   * @return void
   */
  public function set_price_tag($price_tag)
  {
    $this->price_tag = trim($price_tag);
  }
  
  /**
   * Set the price value
   *
   * @param float price_value Price value
   * @return void
   */
  public function set_price_value($price_value)
  {
    $this->price_value = trim($price_value);
  }
  
  /**
   * Set the type
   *
   * @param string type Type
   * @return void
   */
  public function set_type($type)
  {
    $this->type = trim($type);
  }
  
  /**
   * Set open since
   *
   * @param date open_since Open since
   * @return void
   */
  public function set_open_since($open_since)
  {
    $this->open_since = trim($open_since);
  }
  
  /**
   * Set Open until
   *
   * @param date open_until Open until
   * @return void
   */
  public function set_open_until($open_until)
  {
    $this->open_until = trim($open_until);
  }
  
  /**
   * Set the open state
   *
   * @param string open_state Open state
   * @return void
   */
  public function set_open_state($open_state)
  {
    $this->open_state = trim($open_state);
  }
  
  /**
   * Set the mno array
   *
   * @param Mno[] mno_list Mno array
   * @return void
   */
  public function set_mno_list($mno_list)
  {
    $this->mno_list = $mno_list;
  }
  
  /**
   * Set the subscription max
   *
   * @param float subscription_max Subscription max
   * @return void
   */
  public function set_subscription_max($subscription_max)
  {
    $this->subscription_max = trim($subscription_max);
  }
  
  /**
   * Set the subscription period
   *
   * @param int subscription_period Subscription period
   * @return void
   */
  public function set_subscription_period($subscription_period)
  {
    $this->subscription_period = trim($subscription_period);
  }
  
  /**
   * Set the listener array
   *
   * @param Listener[] listener_list Listener array
   * @return void
   */
  public function set_listener_list($listener_list)
  {
    $this->listener_list = $listener_list;
  }
  
  //getters
  /**
   * Returns the drop
   * 
   * @return string Drop
   */
  public function get_drop()
  {
    return $this->drop;
  }
  
  /**
   * Returns the alias
   * 
   * @return string Alias
   */
  public function get_alias()
  {
    return $this->alias;
  }
  
  /**
   * Returns the price tag
   * 
   * @return string Price tag
   */
  public function get_price_tag()
  {
    return $this->price_tag;
  }
  
  /**
   * Returns the price value
   * 
   * @return float Price value
   */
  public function get_price_value()
  {
    return $this->price_value;
  }
  
  /**
   * Returns the type
   * 
   * @return string Type
   */
  public function get_type()
  {
    return $this->type;
  }
  
  /**
   * Returns the open since
   * 
   * @return date Open since
   */
  public function get_open_since()
  {
    return $this->open_since;
  }
  
  /**
   * Returns the open until
   * 
   * @return date Open until
   */
  public function get_open_until()
  {
    return $this->open_until;
  }
  
  /**
   * Returns the open state
   * 
   * @return string Open state
   */
  public function get_open_state()
  {
    return $this->open_state;
  }
  
  /**
   * Returns the mno array
   * 
   * @return Mno[] Array of MNO
   */
  public function get_mno_list()
  {
    return $this->mno_list;
  }
  
  /**
   * Returns the subscription max
   * 
   * @return float Subscription max
   */
  public function get_subscription_max()
  {
    return $this->subscription_max;
  }
  
  /**
   * Returns the subscription period
   * 
   * @return int Subscription period
   */
  public function get_subscription_period()
  {
    return $this->subscription_period;
  }
  
  /**
   * Returns the listener array
   * 
   * @return Listener[] Array of Listener
   */
  public function get_listener_list()
  {
    return $this->listener_list;
  }
}
?>
