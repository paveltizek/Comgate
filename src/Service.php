<?php

namespace LZaplata\Comgate;

use Nette\SmartObject;

class Service
{
    use SmartObject;
    /** @var string */
    public $merchant;

    /** @var string */
    public $secret;

    /** @var bool */
    public $sandbox;

    /** @var string */
    public $url;

    /** @var string */
    public $currency;

    /** @var bool */
    public $preauth;
    private $logDir;

    /**
     * Service constructor.
     * @param int $merchant
     * @param string $secret
     * @param bool $sandbox
     * @param string $currency
     * @param bool $preauth
     * @param $logDir
     */
    public function __construct($merchant, $secret, $sandbox, $currency, $preauth, $logDir) {
        $this->setMerchant($merchant);
        $this->setSecret($secret);
        $this->setSandbox($sandbox);
        $this->setCurrency($currency);
        $this->setPreauth($preauth);
        $this->logDir = $logDir;
    }

    /**
     * @param string $merchant
     * @return self
     */
    public function setMerchant($merchant) {
        $this->merchant = (string)$merchant;

        return $this;
    }

    /**
     * @return string
     */
    public function getMerchant() {
        return $this->merchant;
    }

    /**
     * @param string $secret
     * @return self
     */
    public function setSecret($secret) {
        $this->secret = (string)$secret;

        return $this;
    }

    /**
     * @return string
     */
    public function getSecret() {
        return $this->secret;
    }

    /**
     * @param int $sandbox
     * @return self
     */
    public function setSandbox($sandbox) {
        $this->sandbox = $sandbox;

        if ($sandbox) {
            $this->url = "https://payments.comgate.cz/v1.0";
        } else {
            $this->url = "https://payments.comgate.cz/v1.0";
        }

        return $this;
    }

    /**
     * @return bool
     */
    public function getSandbox() {
        return $this->sandbox;
    }

    /**
     * @return string
     */
    public function getUrl() {
        return $this->url;
    }

    /**
     * @param string $currency
     * @return self
     */
    public function setCurrency($currency) {
        $this->currency = $currency;

        return $this;
    }

    /**
     * @return string
     */
    public function getCurrency() {
        return $this->currency;
    }

    /**
     * @param $preauth
     * @return self
     */
    public function setPreauth($preauth) {
        $this->preauth = $preauth;

        return $this;
    }

    /**
     * @return bool
     */
    public function getPreauth() {
        return $this->preauth;
    }

    /**
     * @param float $price
     * @return Payment
     * @throws \Exception
     */
    public function createPayment($price, $refId = null, $dir,
                                  $vatPL = "STANDARD",
                                  $category = "PHYSICAL",
                                  $label = "payment",
                                  $payerId = null,
                                  $method = "ALL",
                                  $account = "",
                                  $email = "",
                                  $phone = "",
                                  $productName = "",
                                  $language = "",
                                  $reccurring = false,
                                  $reccurringId = null,
                                  $eetReport = false,
                                  $eetData = null,
                                  $country = "CZ"
    ) {
        $payment = new Payment($this, $dir);
        $payment->setVatPL($vatPL)
            ->setRefId($refId)
            ->setCategory($category)
            ->setLabel($label)
            ->setPayerId($payerId)
            ->setMethod($method)
            ->setAccount($account)
            ->setEmail($email)
            ->setPhone($phone)
            ->setProductName($productName)
            ->setLanguage($language)
            ->setReccurring($reccurring)
            ->setReccurringId($reccurringId)
            ->setEetReport($eetReport)
            ->setEetData($eetData)
            ->setCountry($country);
        $payment->createPayment($price, $refId);

        return $payment;
    }

    /**
     * @param float $price
     * @return Refund
     * @throws \Exception
     */
    public function createRefund($transId, $price, $refId = null, $dir) {
        $refund = new Refund($this, $dir);

        $refund->createRefund($transId, $price, $refId);

        return $refund;
    }

    /**
     * @return Response
     */
    public function getReturnResponse() {
        return new Response(null, $this);
    }

    /**
     * @return mixed
     */
    public function getLogDir() {
        return $this->logDir;
    }


}