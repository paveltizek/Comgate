<?php

namespace LZaplata\Comgate;


use Nette\SmartObject;

class Payment
{
    use SmartObject;
    /** @var Service */
    public $service;

    /** @var \AgmoPaymentsSimpleDatabase */
    public $paymentsDatabase;

    /** @var \AgmoPaymentsSimpleProtocol */
    public $paymentsProtocol;

    /** @var string */
    public $refId;

    /** @var float */
    public $price;

    /**
     * Payment constructor.
     * @param Service $service
     */
    private $vatPL;

    private $category;

    private $label;

    private $payerId;

    private $method;

    private $account;

    private $email;

    private $phone;

    private $productName;

    private $language;

    private $reccurring;

    private $reccurringId;

    private $eetReport;

    private $eetData;

    private $country;

    private $filename;

    public function __construct(Service $service ,$dir)
    {
        $this->filename = $dir;
        if (!file_exists($this->filename)) {
            mkdir($this->filename);
        }

        $this->service = $service;
        $this->paymentsDatabase = new \AgmoPaymentsSimpleDatabase(
            $dir,
            $this->service->getMerchant(),
            $this->service->getSecret()
        );
        $this->paymentsProtocol = new \AgmoPaymentsSimpleProtocol(
            $this->service->getUrl() . "/create",
            $this->service->getMerchant(),
            $this->service->getSandbox(),
            $this->service->getSecret()
        );

        $this->vatPL = "STANDARD";
        $this->category = "PHYSICAL";
        $this->label = "payment";
        $this->payerId = null;
        $this->method = "ALL";
        $this->account = "";
        $this->email = "";
        $this->phone = "";
        $this->productName = "";
        $this->language = "";
        $this->reccurring = false;
        $this->reccurringId = null;
        $this->eetReport = false;
        $this->eetData = null;
        $this->country = "CZ";
    }

    /**
     * @param $price
     * @throws \Exception
     */
    public function createPayment($price)
    {
        $this->refId = $this->paymentsDatabase->createNextRefId();
        $this->price = $price;


        $this->paymentsProtocol->createTransaction(
            $this->country,                                               // country
            $price,                                             // price
            $this->service->getCurrency(),                      // currency
            $this->label,                                          // description
            $this->refId,                                       // refId
            $this->payerId,                                               // payerId
            $this->vatPL,                                         // vatPL
            $this->category,                                         // category
            $this->method,                                              // method
            $this->account,                                                 // account
            $this->email,                                                 // email
            $this->phone,                                                 // phone
            $this->productName,                                                 // productName
            $this->language,                                                 // language
            $this->service->getPreauth(),                       // preauth
            $this->reccurring,                                              // reccuring
            $this->reccurringId,                                               // reccuringId
            $this->eetReport,                                              // eetReport
            $this->eetData                                                // eetData
        );
    }

    /**
     * @return Response
     * @throws \Exception
     */
    public function send()
    {
        $transId = $this->paymentsProtocol->getTransactionId();

        $this->paymentsDatabase->saveTransaction(
            $transId,                                           // transId
            $this->refId,                                       // refId
            $this->price,                                       // price
            $this->service->getCurrency(),                      // currency
            "PENDING"                                           // status
        );

        return new Response($this->paymentsProtocol, $this->service);
    }

    /**
     * @return string
     */
    public function getFilename(): string {
        return $this->filename;
    }

    /**
     * @param string $filename
     * @return Payment
     */
    public function setFilename(string $filename): Payment {
        $this->filename = $filename;
        return $this;
    }




    /**
     * @return int
     */
    public function getPayId()
    {
        return $this->paymentsProtocol->getTransactionId();
    }

    /**
     * @return Service
     */
    public function getService() {
        return $this->service;
    }

    /**
     * @param Service $service
     * @return Payment
     */
    public function setService($service) {
        $this->service = $service;
        return $this;
    }

    /**
     * @return \AgmoPaymentsSimpleDatabase
     */
    public function getPaymentsDatabase() {
        return $this->paymentsDatabase;
    }

    /**
     * @param \AgmoPaymentsSimpleDatabase $paymentsDatabase
     * @return Payment
     */
    public function setPaymentsDatabase($paymentsDatabase) {
        $this->paymentsDatabase = $paymentsDatabase;
        return $this;
    }

    /**
     * @return \AgmoPaymentsSimpleProtocol
     */
    public function getPaymentsProtocol() {
        return $this->paymentsProtocol;
    }

    /**
     * @param \AgmoPaymentsSimpleProtocol $paymentsProtocol
     * @return Payment
     */
    public function setPaymentsProtocol($paymentsProtocol) {
        $this->paymentsProtocol = $paymentsProtocol;
        return $this;
    }

    /**
     * @return string
     */
    public function getRefId() {
        return $this->refId;
    }

    /**
     * @param string $refId
     * @return Payment
     */
    public function setRefId($refId) {
        $this->refId = $refId;
        return $this;
    }

    /**
     * @return float
     */
    public function getPrice() {
        return $this->price;
    }

    /**
     * @param float $price
     * @return Payment
     */
    public function setPrice($price) {
        $this->price = $price;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getVatPL() {
        return $this->vatPL;
    }

    /**
     * @param mixed $vatPL
     * @return Payment
     */
    public function setVatPL($vatPL) {
        $this->vatPL = $vatPL;
        return $this;
    }

    /**
     * @return string
     */
    public function getCategory() {
        return $this->category;
    }

    /**
     * @param string $category
     * @return Payment
     */
    public function setCategory($category) {
        $this->category = $category;
        return $this;
    }

    /**
     * @return string
     */
    public function getLabel() {
        return $this->label;
    }

    /**
     * @param string $label
     * @return Payment
     */
    public function setLabel($label) {
        $this->label = $label;
        return $this;
    }

    /**
     * @return null
     */
    public function getPayerId() {
        return $this->payerId;
    }

    /**
     * @param null $payerId
     * @return Payment
     */
    public function setPayerId($payerId) {
        $this->payerId = $payerId;
        return $this;
    }

    /**
     * @return string
     */
    public function getMethod() {
        return $this->method;
    }

    /**
     * @param string $method
     * @return Payment
     */
    public function setMethod($method) {
        $this->method = $method;
        return $this;
    }

    /**
     * @return string
     */
    public function getAccount() {
        return $this->account;
    }

    /**
     * @param string $account
     * @return Payment
     */
    public function setAccount($account) {
        $this->account = $account;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmail() {
        return $this->email;
    }

    /**
     * @param string $email
     * @return Payment
     */
    public function setEmail($email) {
        $this->email = $email;
        return $this;
    }

    /**
     * @return string
     */
    public function getPhone() {
        return $this->phone;
    }

    /**
     * @param string $phone
     * @return Payment
     */
    public function setPhone($phone) {
        $this->phone = $phone;
        return $this;
    }

    /**
     * @return string
     */
    public function getProductName() {
        return $this->productName;
    }

    /**
     * @param string $productName
     * @return Payment
     */
    public function setProductName($productName) {
        $this->productName = $productName;
        return $this;
    }

    /**
     * @return string
     */
    public function getLanguage() {
        return $this->language;
    }

    /**
     * @param string $language
     * @return Payment
     */
    public function setLanguage($language) {
        $this->language = $language;
        return $this;
    }

    /**
     * @return bool
     */
    public function isReccurring() {
        return $this->reccurring;
    }

    /**
     * @param bool $reccurring
     * @return Payment
     */
    public function setReccurring($reccurring) {
        $this->reccurring = $reccurring;
        return $this;
    }

    /**
     * @return null
     */
    public function getReccurringId() {
        return $this->reccurringId;
    }

    /**
     * @param null $reccurringId
     * @return Payment
     */
    public function setReccurringId($reccurringId) {
        $this->reccurringId = $reccurringId;
        return $this;
    }

    /**
     * @return bool
     */
    public function isEetReport() {
        return $this->eetReport;
    }

    /**
     * @param bool $eetReport
     * @return Payment
     */
    public function setEetReport($eetReport) {
        $this->eetReport = $eetReport;
        return $this;
    }

    /**
     * @return null
     */
    public function getEetData() {
        return $this->eetData;
    }

    /**
     * @param null $eetData
     * @return Payment
     */
    public function setEetData($eetData) {
        $this->eetData = $eetData;
        return $this;
    }

    /**
     * @return string
     */
    public function getCountry() {
        return $this->country;
    }

    /**
     * @param string $country
     * @return Payment
     */
    public function setCountry($country) {
        $this->country = $country;
        return $this;
    }


}