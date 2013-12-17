<?
set_include_path('geodrop/');
require_once 'SessionFactory.php';
require_once 'GeodropSession.php';
require_once 'dropPay/PortChallenge.php';
require_once 'dropPay/CustomerWelcome.php';

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
$requestChallenge = new PortChallenge($port,$msisdn,$textChallenge,$custom);
if($session->runMethod($requestChallenge))
{
    echo "Insert PIN:\n";
    $pin = trim(fgets(STDIN));
    $order = $requestChallenge->getResponse()->get_order();
    $requestWelcome = new CustomerWelcome($port,$msisdn,$text,$order,$pin,$custom);
    $session->runMethod($requestWelcome);
    echo "Response: ".$requestWelcome->getResponse()->toString()."\n";
}


?>