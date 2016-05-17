<?php
namespace Walmart\Auth;

use phpseclib\Crypt\RSA;

class Signature
{
    /**
     * @var string Consumer ID provided by Developer Portal
     */
    public $consumerId;

    /**
     * @var string Base64 Encoded Private Key provided by Developer Portal
     */
    public $privateKey;

    /**
     * @var string URL of API request being made
     */
    public $requestUrl;

    /**
     * @var string HTTP request method for API call (GET/POST/PUT/DELETE/OPTIONS/PATCH)
     */
    public $requestMethod;

    /**
     * You may optionally instantiate as an object. This is useful for repeated calls to getSignature();
     * @param string $consumerId
     * @param string $privateKey
     * @param string $requestUrl
     * @param string $requestMethod
     */
    public function __construct($consumerId, $privateKey, $requestUrl, $requestMethod)
    {
        $this->consumerId = $consumerId;
        $this->privateKey = $privateKey;
        $this->requestUrl = $requestUrl;
        $this->requestMethod = $requestMethod;
    }

    /**
     * Get signature with optional timestamp. If using Signature class as object, you can repeatedly call this
     * method to get a new signature without having to provide $consumerId, $privateKey, $requestUrl, $requestMethod
     * every time.
     * @param string|null $timestamp
     * @return string
     * @throws \Exception
     */
    public function getSignature($timestamp=null)
    {
        if(is_null($timestamp) || !is_numeric($timestamp)){
            $timestamp = self::getMilliseconds();
        }
        return self::calculateSignature(
            $this->consumerId,
            $this->privateKey,
            $this->requestUrl,
            $this->requestMethod,
            $timestamp
        );
    }

    /**
     * Static method for quick calls to calculate a signature.
     * @link https://developer.walmartapis.com/#authentication
     * @param string $consumerId
     * @param string $privateKey
     * @param string $requestUrl
     * @param string $requestMethod
     * @param string|null $timestamp
     * @return string
     * @throws \Exception
     */
    public static function calculateSignature($consumerId, $privateKey, $requestUrl, $requestMethod, $timestamp=null)
    {
        if(is_null($timestamp) || !is_numeric($timestamp)){
            $timestamp = self::getMilliseconds();
        }

        /**
         * Append values into string for signing
         */
        $message = $consumerId."\n".$requestUrl."\n".strtoupper($requestMethod)."\n".$timestamp."\n";

        /**
         * Get RSA object for signing
         */
        $rsa = new RSA();
        $decodedPrivateKey = base64_decode($privateKey);
        $rsa->setPrivateKeyFormat(RSA::PRIVATE_FORMAT_PKCS8);
        $rsa->setPublicKeyFormat(RSA::PRIVATE_FORMAT_PKCS8);

        /**
         * Load private key
         */
        if($rsa->loadKey($decodedPrivateKey,RSA::PRIVATE_FORMAT_PKCS8)){
            /**
             * Make sure we use SHA256 for signing
             */
            $rsa->setHash('sha256');
            $rsa->setSignatureMode(RSA::SIGNATURE_PKCS1);
            $signed = $rsa->sign($message);

            /**
             * Return Base64 Encode generated signature
             */
            return base64_encode($signed);

        } else {
            throw new \Exception("Unable to load private key", 1446780146);
        }
    }

    /**
     * Get current timestamp in milliseconds
     * @return float
     */
    public static function getMilliseconds()
    {
        return round(microtime(true) * 1000);
    }
}