<?
set_include_path('geodrop/');
require_once 'SessionFactory.php';
require_once 'GeodropSession.php';
require_once 'dropPay/PortChargeSubscriptionPurchases.php';
require_once 'dropPay/CustomerCheck.php';


$application_id = '';
$application_secret = '';
$username = '';
$password = '';
$port = "";
$msisdn = '';
$custom = "";
$textChallenge = '$$PIN$$';
$text = "";


//Creation of the GeodropSession
$session = SessionFactory::buildSession_ResourceOwnerPasswordCredentials($application_id,$application_secret,$username,$password);
$requestCheck = new CustomerCheck($port,$msisdn);
if($session->runMethod($requestCheck))
{
  $subscriber = $requestCheck->getResponse()->get_subscriber();
  $requestChargeSubscription = new PortChargeSubscriptionPurchases($port,$msisdn,$text,$subscriber);
  $session->runMethod($requestChargeSubscription);
  echo "Response: ".$requestChargeSubscription->getResponse()->toString()."\n";
}


?>