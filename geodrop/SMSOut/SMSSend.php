<?php
require_once 'GeodropRequest.php';
require_once 'SMSOut/SMSSend_Response.php';
require_once 'SMSOut/Report.php';

/**
 * Request to send SMS messages
 * specifying a text for the message,
 * a list of recipients and a personalized sender
 *
 * @author A-Tono s.r.l.
 * @since 1.0
 *
 *
 */
class SMSSend extends GeodropRequest
{
  /**
   * The text of the message to send
   * @var string
   */
  private $message_text;
  /**
   * Contains the msisdn of the recipient or an array of msisdns if there are many recipients;
   * each msisdn is in E.164 format with '+'
   * @var string|string[]
   */
  private $dest_msisdns;
  /**
   * Used to specify the personalized sender
   * @var string
   */
  private $tpoa;
  /**
   * Date and time in the format "Y-m-d H:i:s",
   * used to send the message to a certain date,
   * if not specified the message is sent immediately
   * @var date
   */
  private $deferred;
  /**
   * Url for job notification
   * @var string
   */
  private $jobnotify;
  /**
   * Url for dlr notification
   * @var string
   */
  private $dlrnotify;
  /**
   * Time from which messages are not sent (HH:MM)
   * @var date
   */
  private $runrange_ltime;
  /**
   * Number of hours during which messages are not sent
   * @var int
   */
  private $runrange_timewindow;
  
  /**
   * Creates a new <CODE>SMSSend</CODE> instance
   * 
   * @param string/array dest_msisdns Msisdn of the recipient or array of msisdns
   * @param string message_text Text of the message
   * @param string tpoa Used to specify the personalized sender
   * @param string deferred (optional) Date and time in the format "Y-m-d H:i:s",
   * used to send the message to a certain date,
   * if not specified the message is sent immediately
   * @param string jobnotify Url for job notification
   * @param string dlrnotify Url for dlr notification
   * @param string runrange_ltime Time from which messages are not sent (HH:MM)
   * @param int runrange_timewindow Number of hours during which messages are not sent
   * 
   * @throws Exception If parameters are not valid
   */
  public function __construct(
    $dest_msisdns,
    $message_text,
    $tpoa,
    $deferred=null,
    $jobnotify=null,
    $dlrnotify=null,
    $runrange_ltime=null,
    $runrange_timewindow=null)
  {
    if(!isset($dest_msisdns,$message_text,$tpoa))
    {
      throw new Exception(ErrorType::MISSING_PARAMETERS);
    }

    //set parameters
    $this->uri = Uri::OUT_SMS_SEND;
    $this->httpMethod = HttpMethod::POST;
    $this->contentType = ContentType::XML;
    $this->set_dest_msisdns($dest_msisdns);
    $this->set_message_text(utf8_encode($message_text));
    $this->set_tpoa($tpoa);
    if(isset($deferred))
      $this->set_deferred(date('Y-m-d H:i:s',strtotime(trim($deferred))));
    if(isset($jobnotify))
      $this->set_jobnotify($jobnotify);
    if(isset($dlrnotify))
      $this->set_dlrnotify($dlrnotify);
    if(isset($runrange_ltime))
      $this->set_runrange_ltime(date('H:i',strtotime(trim($runrange_ltime))));
    if(isset($runrange_timewindow))
      $this->set_runrange_timewindow($runrange_timewindow);
  }
  
  public function __destruct()
  {
    parent::__destruct();
    unset($this->message_text);
    unset($this->dest_msisdns);
    unset($this->tpoa);
    unset($this->deferred);
    unset($this->jobnotify);
    unset($this->dlrnotify);
    unset($this->runrange_ltime);
    unset($this->runrange_timewindow);
  }

  public function decodeResponse( $http_response )
  {
    //echo "SMSSend::decodeResponse: ".$http_response."\n";    
    $this->response = new SMSSend_Response();
    return $this->response->fillParameters($http_response);
  }
  
  public function createParams()
  {      
    //set body
    $this->body = '<?xml version="1.0" encoding="UTF-8"?>';
    $this->body.= '<GEOSMS>';
    $this->body.= '<MESSAGE content="text"><TEXT><![CDATA['.$this->message_text.']]></TEXT></MESSAGE>';
    $this->body.= '<LIST>';
    //decode $dest_msisdns, that is an array if there are more msisdns
    if(is_string($this->dest_msisdns))
    {
      $this->body.= '<DEST msisdn="'.$this->dest_msisdns.'" />';
    }
    elseif(is_array($this->dest_msisdns))
    {
      foreach($this->dest_msisdns as &$msisdn)
      {
	      $this->body.= '<DEST msisdn="'.$msisdn.'" />';
      }
    }
    $this->body.= '</LIST>';
    $this->body.= '<TPOA>'.$this->tpoa.'</TPOA>'; 
    if(isset($this->deferred))
      $this->body.= '<DEFERRED>'.$this->deferred.'</DEFERRED>';
    if(isset($this->jobnotify))
    {
      $this->body.= '<JOBNOTIFY>';
      if(is_string($this->jobnotify))
      {
        $this->body.= '<NOTIFYURL>'.$this->jobnotify.'</NOTIFYURL>';
      }
      elseif(is_array($this->jobnotify))
      {
        foreach ($this->jobnotify as $jobnot) 
        {
          $this->body.= '<NOTIFYURL>'.$jobnot.'</NOTIFYURL>';
        }
      }
      $this->body.= '</JOBNOTIFY>';
    }
    if(isset($this->dlrnotify))
    {
      $this->body.= '<DLRNOTIFY>';
      if(is_string($this->dlrnotify))
      {
        $this->body.= '<NOTIFYURL>'.$this->dlrnotify.'</NOTIFYURL>';
      }
      elseif (is_array($this->dlrnotify))
      {
        foreach ($this->dlrnotify as $dlrnot) 
        {
          $this->body.= '<NOTIFYURL>'.$dlrnot.'</NOTIFYURL>';
        }
      }
      $this->body.= '</DLRNOTIFY>';
    }
    if(isset($this->runrange_ltime) && isset($this->runrange_timewindow))
    {
      $this->body.= '<RUNRANGE>';
      $this->body.= '<L_TIME>'.$this->runrange_ltime.'</L_TIME>';
      $this->body.= '<TIME_WINDOW>'.$this->runrange_timewindow.'</TIME_WINDOW>';
      $this->body.= '</RUNRANGE>';
    }
    $this->body.= '</GEOSMS>';
  }

  //getters
  /**
   * Returns the text of the message to send
   * @access public
   * @return string
   */
  public function get_message_text()
  {
    return $this->message_text;
  }
  /**
   * Returns the msisdn of the recipient or an array of msisdns if there are many recipients;
   * each msisdn is in E.164 format with '+'
   * @access public
   * @return string|string[]
   */
  public function get_dest_msisdns()
  {
    return $this->dest_msisdns;
  }
  /**
   * Returns the personalized sender
   * @access public
   * @return string
   */
  public function get_tpoa()
  {
    return $this->tpoa;
  }
  /**
   * Returns date and time in the format "Y-m-d H:i:s",
   * used to send the message to a certain date,
   * if not specified the message is sent immediately
   * @access public
   * @return date
   * 
   */
  public function get_deferred()
  {
     return $this->deferred;
  }

  /**
   * Returns the url for job notification
   * 
   * @access public
   * @return string
   */
  public function get_jobnotify()
  {
    return $this->jobnotify;
  }

  /**
   * Returns the url for dlr notification
   * 
   * @access public
   * @return string
   */
  public function get_dlrnotify()
  {
    return $this->dlrnotify;
  }

  /**
   * Returns the time from which messages are not sent
   * 
   * @access public
   * @return date
   */
  public function get_runrange_ltime()
  {
    return $this->runrange_ltime;
  }

  /**
   * Returns the number of hours during which messages are not sent
   * 
   * @access public
   * @return int
   */
  public function get_runrange_timewindow()
  {
    return $this->runrange_timewindow;
  }
  
  //setters
  /**
   * Sets the text of the message to send
   * 
   * @param string message_text The text of the message to send
   * @return void
   */
  public function set_message_text($message_text)
  {
    $this->message_text = $message_text;
  }
  /**
   * Sets the msisdn of the recipient or an array of msisdns if there are many recipients;
   * each msisdn is in E.164 format with '+'
   * 
   * @param string|string[] The msisdn of the recipient or an array of msisdns
   * @return void
   * @throws Exception If parameters are not valid
   */
  public function set_dest_msisdns($dest_msisdns)
  {
    //check dest_msisdns
    if(!$this->checkIfMsisdnsIsArrayOrString($dest_msisdns,true))
    {
      throw new Exception(ErrorType::MALFORMED_MSISDNS);
    }
    $this->dest_msisdns = $dest_msisdns;
  }
  /**
   * Sets the personalized sender
   * 
   * @param string dest_msisdns The personalized sender
   * @return void
   */
  public function set_tpoa($tpoa)
  {
    $this->tpoa = $tpoa;
  }
  /**
   * Sets date and time in the format "Y-m-d H:i:s",
   * used to send the message to a certain date,
   * if not specified the message is sent immediately
   * 
   * @param date deferred The deferred time
   * @return void
   * @throws Exception If parameters are not valid
   */
  public function set_deferred($deferred)
  {
    //check deferred
    if(isset($deferred))
    {
      if(!$this->checkIfFutureDatetime($deferred))
      {
        throw new Exception(ErrorType::MALFORMED_OR_PAST_TIME);
      }
    }
    $this->deferred = $deferred;
  }

  /**
   * Sets the url for job notification
   * 
   * @param string jobnotify Url for job notification
   * @return void
   */
  public function set_jobnotify($jobnotify)
  {
    $this->jobnotify = $jobnotify;
  }

  /**
   * Sets the url for dlr notification
   * 
   * @param string jobnotify Url for dlr notification
   * @return void
   */
  public function set_dlrnotify($dlrnotify)
  {
    $this->dlrnotify = $dlrnotify;
  }

  /**
   * Sets the time from which messages are not sent (HH:MM)
   * 
   * @param date runrange_ltime Time from which messages are not sent (HH:MM)
   * @return void
   */
  public function set_runrange_ltime($runrange_ltime)
  {
    $this->runrange_ltime = $runrange_ltime;
  }

  /**
   * Sets the number of hours during which messages are not sent
   * 
   * @param int runrange_timewindow Number of hours during which messages are not sent
   * @return void
   */
  public function set_runrange_timewindow($runrange_timewindow)
  {
    $this->runrange_timewindow = $runrange_timewindow;
  }
}

?>