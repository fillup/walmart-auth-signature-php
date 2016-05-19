<?php
namespace WalmartTests;

include __DIR__ . '/../vendor/autoload.php';

use Walmart\Auth\Signature;

class SignatureTest extends \PHPUnit_Framework_TestCase
{
    /*
     * Dummy credentials for testing, these are not valid credentials for calling Walmart APIs, sorry hackers.
     */
    public $consumerId = 'hw30cqp3-35fi-1bi0-3312-hw9fgm30d2p4';
    public $privateKey = 'MIICdgIBADANBgkqhkiG9w0BAQEFAASCAmAwggJcAgEAAoGBAKzXEfCYdnBNkKAwVbCpg/tR40WixoZtiuEviSEi4+LdnYAAPy57Qw6+9eqJGTh9iCB2wP/I8lWh5TZ49Hq/chjTCPeJiOqi6bvX1xzyBlSq2ElSY3iEVKeVoQG/5f9MYQLEj5/vfTWSNASsMwnNeBbbHcV1S1aY9tOsXCzRuxapAgMBAAECgYBjkM1j1OA9l2Ed9loWl8BQ8X5D6h4E6Gudhx2uugOe9904FGxRIW6iuvy869dchGv7j41ki+SV0dpRw+HKKCjYE6STKpe0YwIm/tml54aNDQ0vQvF8JWILca1a7v3Go6chf3Ib6JPs6KVsUuNo+Yd+jKR9GAKgnDeXS6NZlTBUAQJBANex815VAySumJ/n8xR+h/dZ2V5qGj6wu3Gsdw6eNYKQn3I8AGQw8N4yzDUoFnrQxqDmP3LOyr3/zgOMNTdszIECQQDNIxiZOVl3/Sjyxy9WHMk5qNfSf5iODynv1OlTG+eWao0Wj/NdfLb4pwxRsf4XZFZ1SQNkbNne7+tEO8FTG1YpAkAwNMY2g/ty3E6iFl3ea7UJlBwfnMkGz8rkye3F55f/+UCZcE2KFuIOVv4Kt03m3vg1h6AQkaUAN8acRl6yZ2+BAkEAke2eiRmYANiR8asqjGqr5x2qcm8ceiplXdwrI1kddQ5VUbCTonSewOIszEz/gWp6arLG/ADHOGWaCo8rptAyiQJACXd1ddXUAKs6x3l752tSH8dOde8nDBgF86NGvgUnBiAPPTmJHuhWrmOZmNaB68PsltEiiFwWByGFV+ld9VKmKg==';

    public function testCalculateSignature()
    {
        $requestMethod = 'GET';

        $timestamp = '1462475614410';
        $requestUrl = 'https://marketplace.stg.walmartapis.com/v2/feeds?offset=0&limit=1';
        $expected = 'IIeNSuFsBGpEQE7OWcprahLC8mk54ljlMFrKdRP2zo2Kil7t1knhb4+WmNq6sg1zZSOo9IjKwtu1eIgqM5Isf8UvcEQYV44ighfDBOLkDmqvc/BJRm6erZ5A/n5gbhIssnv8CtuQvQUdLTw0wAG0sW48CQW8CDTCaxlu2LaCCyw=';
        $actual = Signature::calculateSignature($this->consumerId,$this->privateKey,$requestUrl,$requestMethod,$timestamp);
        $this->assertEquals($expected, $actual);

        $timestamp = '1462482229078';
        $requestUrl = 'https://marketplace.stg.walmartapis.com/v2/orders?createdStartDate=2015-01-03T05%3A00%3A00Z&offset=0&limit=5';
        $expected = 'IIpHJY7wFNV61GA/bx/4A/lzOj7uhB/JodndEQl8wpAVzcfCfD5ovrYclQG3cR3Al9KSLCT3leU5Ug0ikqyp+bI757E3D3zhzzCOyDMpG6mnhcKW/WjTBZIe5KLd2D/oN4c9Eu6mTudd/w6/VKUDB9qxHIGHMoKCWRt2udDZn48=';
        $actual = Signature::calculateSignature($this->consumerId,$this->privateKey,$requestUrl,$requestMethod,$timestamp);
        $this->assertEquals($expected, $actual);

        $timestamp = '1462476258197';
        $requestUrl = 'https://marketplace.stg.walmartapis.com/v2/items?offset=0&limit=5';
        $expected = 'GmuOrPQ67wuVje8FYtLqq5Li2/BehKsITW/8CNMNuwI/j0jm0Y6Hbj4zyp963/UYPAUWJUweaMoyw6gHnOnxXV3A/u9oeh19Z4jfTD19w0YKCCSp5dX8RdiktIAYjpITdz8Tnif3McPqtddWLdjz9MjtIZUnGoTCGNWFYlJuc6Y=';
        $actual = Signature::calculateSignature($this->consumerId,$this->privateKey,$requestUrl,$requestMethod,$timestamp);
        $this->assertEquals($expected, $actual);

        // test without providing timestamp to make sure it works without it
        $actual = Signature::calculateSignature($this->consumerId,$this->privateKey,$requestUrl,$requestMethod);
        $this->assertEquals(172, strlen($actual));
    }

    public function testCalculateSignatureInvalidPrivateKey()
    {
        $consumerId = $privateKey = $requestUrl = $requestMethod = 'test';

        $this->setExpectedException('\Exception', '', 1446780146);
        Signature::calculateSignature($consumerId,$privateKey,$requestUrl,$requestMethod);

    }

    public function testObjectInterface()
    {
        $requestMethod = 'GET';
        $requestUrl = 'https://marketplace.stg.walmartapis.com/v2/feeds?offset=0&limit=1';
        $signatureObject = new Signature($this->consumerId, $this->privateKey, $requestUrl, $requestMethod);

        $this->assertEquals($this->consumerId, $signatureObject->consumerId);
        $this->assertEquals($this->privateKey, $signatureObject->privateKey);
        $this->assertEquals($requestUrl, $signatureObject->requestUrl);
        $this->assertEquals($requestMethod, $signatureObject->requestMethod);

        $timestamp = '1462475614410';
        $expected = 'IIeNSuFsBGpEQE7OWcprahLC8mk54ljlMFrKdRP2zo2Kil7t1knhb4+WmNq6sg1zZSOo9IjKwtu1eIgqM5Isf8UvcEQYV44ighfDBOLkDmqvc/BJRm6erZ5A/n5gbhIssnv8CtuQvQUdLTw0wAG0sW48CQW8CDTCaxlu2LaCCyw=';

        $signatureString = $signatureObject->getSignature($timestamp);
        $this->assertEquals($expected, $signatureString);

        // test without providing timestamp to make sure it works without it
        $actual = $signatureObject->getSignature();
        $this->assertEquals(172, strlen($actual));
    }

    public function testGetMilliseconds()
    {
        $expected = round(microtime(true) * 1000);
        $actual = Signature::getMilliseconds();
        $this->assertEquals($expected, $actual, '', 10); // allow for a 10ms discrepency
    }

}