<?php
    /**
     * It is used to parser the Events.
     * Events are asynchronous messages posted to the URLs you previously communicated to Geodrop.
     * In order to catch an event posted by DropPay you must provide us with one or more URLs we call Listeners.
     * 
     * @author A-Tono s.r.l.
     * @since 1.0
     *
     */
    class EventObject
    {       
        /**
	 * The Event Class 
	 * (it can be: Message, Call, Click, Billing, Customer, Kit)
	 * @var string
	 */
	private $class_event;
	/**
	 * The Event type
	 * (it can be: MO, DLR, IN, ACK, SUBSCRIBE, UNSUBSCRIBE, SUNSPENSION, DETECT, SUBSCRIPTION, BILLING)
	 * @var string
	 */
	private $type;
	/**
	 * The port
	 * @var int
	 */
	private $port;
	/**
	 * The time
	 * @var date
	 */
	private $time;
	/**
	 * The order ID
	 * @var int
	 */
	private $order;
	/**
	 * The session ID
	 * @var string
	 */
	private $session;
	/**
	 * The message characteristics
	 * @var Message
	 */
	private $message;
	/**
	 * The call characteristics
	 * @var Call
	 */
	private $call;
	/**
	 * The click characteristics
	 * @var Click
	 */
	private $click;
	/**
	 * The subscriber characteristics
	 * @var Subscriber
	 */
	private $subscriber;
	/**
	 * The detection characteristics
	 * @var Detection
	 */
	private $detection;
        
        /**
	 * Creates a new <CODE>EventObject</CODE> instance
	 * 
	 * @param string xmlString The event to be notified encoded in XML
	 */
        public function __construct($xmlString) 
        {
            if(!isset($xmlString))
            {
              throw new Exception(ErrorType::MISSING_PARAMETERS);
            }
            
            //parsing
            try
            {
              $xml = new SimpleXMLElement($xmlString);
            }
            catch (Exception $e)
            {
              error_log(ErrorType::RESPONSE_NOT_XML);
              return false;
            }
            $this->class_event      = trim($xml->pay->event['class']);
            $this->type             = trim($xml->pay->event['type']);
            $this->port             = (int)trim($xml->pay->event['port']);
            $this->time             = date('Ymd H:i:s',strtotime(trim($xml->pay->event['time'])));
            $this->order            = (int)trim($xml->pay->event['order']);
            $this->session          = trim($xml->pay->event['session']);
            
            $this->message = new Message($xml);
            $this->call = new Call($xml);
            $this->click = new Click($xml);
            $this->subscriber = new Subscriber($xml);
            $this->detection = new Detection($xml);
	    
            unset($xml);
        }
        
	/**
	 * Returns a string representation of the object.
	 * In general, the toString method returns a string that "textually represents" this object.
	 * The result should be a concise but informative representation that is easy for a person to read.
	 * 
	 * @return string A string representation of the object
	 */
        public function toString()
        {
            $result = "class: ";
            $result.= $this->class_event."\n";
            $result.= "type: ";
            $result.= $this->type."\n";
            $result.= "port: ";
            $result.= $this->port."\n";
            $result.= "time: ";
            $result.= $this->time."\n";
            $result.= "order: ";
            $result.= $this->order."\n";
            $result.= "session: ";
            $result.= $this->session."\n";
            $result.= "MESSAGE: ";
            $result.= $this->message->toString()."\n";
            $result.= "CALL: ";
            $result.= $this->call->toString()."\n";
            $result.= "CLICK: ";
            $result.= $this->click->toString()."\n";
            $result.= "SUBSCRIBER: ";
            $result.= $this->subscriber->toString()."\n";
            $result.= "DETECTION: ";
            $result.= $this->detection->toString();
            $result.= "\n";
            
            return $result;
        }
        
        //getters
        /**
	 * Returns the Event Class 
	 * (it can be: Message, Call, Click, Billing, Customer, Kit)
	 * @return string
	 */
	public function get_class_event()
        {
            return $this->class_event;
        }
	/**
	 * Returns the Event type
	 * (it can be: MO, DLR, IN, ACK, SUBSCRIBE, UNSUBSCRIBE, SUNSPENSION, DETECT, SUBSCRIPTION, BILLING)
	 * @return string
	 */
	public function get_type()
        {
            return $this->type;
        }
	/**
	 * Returns the port
	 * @return int
	 */
	public function get_port()
        {
            return $this->port;
        }
	/**
	 * Returns the time
	 * @return date
	 */
	public function get_time()
        {
            return $this->time;
        }
	/**
	 * Returns the order ID
	 * @return int
	 */
	public function get_order()
        {
            return $this->order;
        }
	/**
	 * Returns the session ID
	 * @return string
	 */
	public function get_session()
        {
            return $this->session;
        }
	/**
	 * Returns the message characteristics
	 * @return Message
	 */
	public function get_message()
        {
            return $this->message;
        }
	/**
	 * Returns the call characteristics
	 * @return Call
	 */
	public function get_call()
        {
            return $this->call;
        }
	/**
	 * Returns the click characteristics
	 * @return Click
	 */
	public function get_click()
        {
            return $this->click;
        }
	/**
	 * Returns the subscriber characteristics
	 * @return Subscriber
	 */
	public function get_subscriber()
        {
            return $this->subscriber;
        }
	/**
	 * Returns the detection characteristics
	 * @return Detection
	 */
	public function get_detection()
        {
            return $this->detection;
        }
    }
    
    //used classes
    /**
     * It is used by <CODE>EventObject</CODE> to parser the Events.
     * Events are asynchronous messages posted to the URLs you previously communicated to Geodrop.
     * In order to catch an event posted by DropPay you must provide us with one or more URLs we call Listeners.
     * 
     * @author A-Tono s.r.l.
     * @since 1.0
     *
     */
    class Message
    {
        private $msisdn;
        private $mno;
        private $shortcode;
        private $order;
        private $id;
        private $text;
        private $status;
        private $description;
        private $session;
        
	/**
	 * Creates a new <CODE>Message</CODE> instance
	 * 
	 * @param SimpleXMLElement xml The event to be notified encoded in XML
	 */
	public function __construct($xml)
	{
	    //set parameters
	    $this->msisdn      = trim($xml->pay->event->message['msisdn']);
            $this->mno         = trim($xml->pay->event->message['mno']);
            $this->shortcode   = trim($xml->pay->event->message['shortcode']);
            $this->order       = (int)trim($xml->pay->event->message['order']);
            $this->id          = trim($xml->pay->event->message['id']);
            $this->text        = trim($xml->pay->event->message);
            $this->status      = trim($xml->pay->event->message->status);
            $this->description = trim($xml->pay->event->message->description);
            $this->session     = trim($xml->pay->event->message['session']);
	}
	
	/**
	 * Returns a string representation of the object.
	 * In general, the toString method returns a string that "textually represents" this object.
	 * The result should be a concise but informative representation that is easy for a person to read.
	 * 
	 * @return string A string representation of the object
	 */
        public function toString()
        {
            $result = "msisdn: ";
            $result.= $this->msisdn."\n";
            $result.= "mno: ";
            $result.= $this->mno."\n";
            $result.= "shortcode: ";
            $result.= $this->shortcode."\n";
            $result.= "order: ";
            $result.= $this->order."\n";
            $result.= "id: ";
            $result.= $this->id."\n";
            $result.= "text: ";
            $result.= $this->text."\n";
            $result.= "status: ";
            $result.= $this->status."\n";
            $result.= "description: ";
            $result.= $this->description."\n";
            $result.= "session: ";
            $result.= $this->session;
            
            return $result;
        }
	
	//getters
	/**
	 * Returns the msisdn
	 * @return string
	 */
	public function get_msisdn()
	{
	    return $this->msisdn;
	}
	/**
	 * Returns the mno
	 * @return string
	 */
        public function get_mno()
	{
	    return $this->mno;
	}
	/**
	 * Returns the shortcode
	 * @return string
	 */
        public function get_shortcode()
	{
	    return $this->shortcode;
	}
	/**
	 * Returns the order
	 * @return int
	 */
        public function get_order()
	{
	    return $this->order;
	}
	/**
	 * Returns the id
	 * @return string
	 */
        public function get_id()
	{
	    return $this->id;
	}
	/**
	 * Returns the text
	 * @return string
	 */
        public function get_text()
	{
	    return $this->text;
	}
	/**
	 * Returns the status
	 * @return string
	 */
        public function get_status()
	{
	    return $this->status;
	}
	/**
	 * Returns the description
	 * @return string
	 */
        public function get_description()
	{
	    return $this->description;
	}
	/**
	 * Returns the session
	 * @return string
	 */
        public function get_session()
	{
	    return $this->session;
	}
    }
    
    /**
     * It is used by <CODE>EventObject</CODE> to parser the Events.
     * Events are asynchronous messages posted to the URLs you previously communicated to Geodrop.
     * In order to catch an event posted by DropPay you must provide us with one or more URLs we call Listeners.
     * 
     * @author A-Tono s.r.l.
     * @since 1.0
     *
     */
    class Call 
    {
        private $msisdn;
        private $mno;
        private $order;
        
	/**
	 * Creates a new <CODE>Call</CODE> instance
	 * 
	 * @param SimpleXMLElement xml The event to be notified encoded in XML
	 */
	public function __construct($xml)
	{
	    //set parameters
	    $this->msisdn = trim($xml->pay->event->call['msisdn']);
            $this->mno    = trim($xml->pay->event->call['mno']);
            $this->order  = (int)trim($xml->pay->event->call['order']);
	}
	
	/**
	 * Returns a string representation of the object.
	 * In general, the toString method returns a string that "textually represents" this object.
	 * The result should be a concise but informative representation that is easy for a person to read.
	 * 
	 * @return string A string representation of the object
	 */
        public function toString()
        {
            $result = "msisdn: ";
            $result.= $this->msisdn."\n";
            $result.= "mno: ";
            $result.= $this->mno."\n";
            $result.= "order: ";
            $result.= $this->order;
            
            return $result;
        }
	
	//getters
	/**
	 * Returns the msisdn
	 * @return string
	 */
	public function get_msisdn()
	{
	    return $this->msisdn;
	}
	/**
	 * Returns the mno
	 * @return string
	 */
        public function get_mno()
	{
	    return $this->mno;
	}
	/**
	 * Returns the order
	 * @return int
	 */
        public function get_order()
	{
	    return $this->order;
	}
    }
    
    /**
     * It is used by <CODE>EventObject</CODE> to parser the Events.
     * Events are asynchronous messages posted to the URLs you previously communicated to Geodrop.
     * In order to catch an event posted by DropPay you must provide us with one or more URLs we call Listeners.
     * 
     * @author A-Tono s.r.l.
     * @since 1.0
     *
     */
    class Click 
    {
        private $msisdn;
        private $mno;
        private $media;
	
	/**
	 * Creates a new <CODE>Click</CODE> instance
	 * 
	 * @param SimpleXMLElement xml The event to be notified encoded in XML
	 */
	public function __construct($xml)
	{
	    //set parameters
	    $this->msisdn    = trim($xml->pay->event->click['msisdn']);
            $this->mno       = trim($xml->pay->event->click['mno']);
            $this->media     = (int)trim($xml->pay->event->click['media']);
	}
        
	/**
	 * Returns a string representation of the object.
	 * In general, the toString method returns a string that "textually represents" this object.
	 * The result should be a concise but informative representation that is easy for a person to read.
	 * 
	 * @return string A string representation of the object
	 */
        public function toString()
        {
            $result = "msisdn: ";
            $result.= $this->msisdn."\n";
            $result.= "mno: ";
            $result.= $this->mno."\n";
            $result.= "media: ";
            $result.= $this->media;
            
            return $result;
        }
	
	//getters
	/**
	 * Returns the msisdn
	 * @return string
	 */
	public function get_msisdn()
	{
	    return $this->msisdn;
	}
	/**
	 * Returns the mno
	 * @return string
	 */
        public function get_mno()
	{
	    return $this->mno;
	}
	/**
	 * Returns the media
	 * @return int
	 */
        public function get_media()
	{
	    return $this->media;
	}
    }
    
    /**
     * It is used by <CODE>EventObject</CODE> to parser the Events.
     * Events are asynchronous messages posted to the URLs you previously communicated to Geodrop.
     * In order to catch an event posted by DropPay you must provide us with one or more URLs we call Listeners.
     * 
     * @author A-Tono s.r.l.
     * @since 1.0
     *
     */
    class Subscriber 
    {
        private $msisdn;
        private $id;
        
	/**
	 * Creates a new <CODE>Subscriber</CODE> instance
	 * 
	 * @param SimpleXMLElement xml The event to be notified encoded in XML
	 */
	public function __construct($xml)
	{
	    //set parameters
            $this->msisdn   = trim($xml->pay->event->subscriber['msisdn']);
            $this->id       = trim($xml->pay->event->subscriber['id']);
	}
	
	/**
	 * Returns a string representation of the object.
	 * In general, the toString method returns a string that "textually represents" this object.
	 * The result should be a concise but informative representation that is easy for a person to read.
	 * 
	 * @return string A string representation of the object
	 */
        public function toString()
        {
            $result = "msisdn: ";
            $result.= $this->msisdn."\n";
            $result.= "id: ";
            $result.= $this->id;
            
            return $result;
        }
	
	//getters
	/**
	 * Returns the msisdn
	 * @return string
	 */
	public function get_msisdn()
	{
	    return $this->msisdn;
	}
	/**
	 * Returns the id
	 * @return string
	 */
        public function get_id()
	{
	    return $this->id;
	}
    }
    
    /**
     * It is used by <CODE>EventObject</CODE> to parser the Events.
     * Events are asynchronous messages posted to the URLs you previously communicated to Geodrop.
     * In order to catch an event posted by DropPay you must provide us with one or more URLs we call Listeners.
     * 
     * @author A-Tono s.r.l.
     * @since 1.0
     *
     */
    class Detection 
    {
        private $msisdn;
        private $mno;
        private $session;
        
	/**
	 * Creates a new <CODE>Detection</CODE> instance
	 * 
	 * @param SimpleXMLElement xml The event to be notified encoded in XML
	 */
	public function __construct($xml)
	{
	    //set parameters
	    $this->msisdn    = trim($xml->pay->event->detection['msisdn']);
            $this->mno       = trim($xml->pay->event->detection['mno']);
            $this->session   = trim($xml->pay->event->detection['session']);
	}
	
	/**
	 * Returns a string representation of the object.
	 * In general, the toString method returns a string that "textually represents" this object.
	 * The result should be a concise but informative representation that is easy for a person to read.
	 * 
	 * @return string A string representation of the object
	 */
        public function toString()
        {
            $result = "msisdn: ";
            $result.= $this->msisdn."\n";
            $result.= "mno: ";
            $result.= $this->mno."\n";
            $result.= "session: ";
            $result.= $this->session;
            
            return $result;
        }
	
	//getters
	/**
	 * Returns the msisdn
	 * @return string
	 */
	public function get_msisdn()
	{
	    return $this->msisdn;
	}
	/**
	 * Returns the mno
	 * @return string
	 */
        public function get_mno()
	{
	    return $this->mno;
	}
	/**
	 * Returns the session
	 * @return string
	 */
        public function get_session()
	{
	    return $this->session;
	}
    }
?>