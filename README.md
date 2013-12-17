geodrop-sdk-php
===============

PHP SDK to use [Geodrop](https://geodrop.com/) SMS services. Except as otherwise noted, it is licensed under the [Apache Licence, Version 2.0](http://www.apache.org/licenses/LICENSE-2.0.html).  
Geodrop is a provider of services and applications for the mobile market. You can use the Geodrop API to send and receive SMS from web or from your application and to build new Premium Service with your digital content.

How to run
-----
Download the folder [geodrop](https://github.com/geodrop/geodrop-sdk-php/tree/master/geodrop). 
Each php file that uses the SDK must include the path of the SDK:
```php
set_include_path('geodrop_sdk_path/');
```

Usage
-----
The [examples](https://github.com/geodrop/geodrop-sdk-php/tree/master/examples) are a good place to start.
To create a new session you must include:
```php
require_once 'SessionFactory.php';
require_once 'GeodropSession.php';
```
Then create a session:
```php
$session = SessionFactory::buildSession_ResourceOwnerPasswordCredentials(
                      $application_id,$application_secret,$username,$password);
```
To send an SMS:
```php
require_once 'dropOut/SMSSend.php';

$requestSend = new SMSSend($recipient,$message,$sender);
$session->runMethod($requestSend);
echo "Response: ".$requestSend->getResponse()->toString()."\n";
```
To challenge a customer to confirm the telephone number in content provider initiated transaction
and to charge the customer:

```php
$requestChallenge = new PortChallenge($port,$recipient,$textChallenge,$custom);
if($session->runMethod($requestChallenge))
{
    echo "Insert PIN:\n";
    $pin = ""; /*retrieve the pin*/
    $order = $requestChallenge->getResponse()->get_order();
    $requestChargeOnDemand = new PortChargeOnDemandPurchases($port,$recipient,$text,$order,$pin);
    $session->runMethod($requestChargeOnDemand);
    echo "Response: ".$requestChargeOnDemand->getResponse()->toString()."\n";
}
```

Documentation
-----
The documentation is available in the folder [doc](https://github.com/geodrop/geodrop-sdk-php/tree/master/doc).

Versioning
-----
The [semantic versioning](http://semver.org/) is used.
The format of the version number is MAJOR.MINOR.PATCH, where:
* MAJOR is incremented when incompatible API changes are done;
* MINOR is incremented when functionality in a backwards-compatible manner are added;
* PATCH is incremented when backwards-compatible bug fixes are done.  

Additional labels for pre-release and build metadata are available as extensions to the MAJOR.MINOR.PATCH format.  

Bug report
-----
If you find a bug [open an issue](https://github.com/geodrop/geodrop-sdk-php/issues).
