<?php

namespace LZaplata\Comgate;


use Nette\SmartObject;

class Refund
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

    /** @var string */
    public $transId;

    /** @var float */
    public $price;

    /**
     * Payment constructor.
     * @param Service $service
     */
    private $vatPL;

    private $filename;

    public function __construct(Service $service ,$dir)
    {
        $this->filename = $dir;
//        if (!file_exists($this->filename)) {
//            mkdir($this->filename);
//        }
        if (!file_exists($dir . "/data")) {
            mkdir($dir . "/data");
        }

        $this->service = $service;
        $this->paymentsDatabase = new \AgmoPaymentsSimpleDatabase(
            $dir . "/data",
            $this->service->getMerchant(),
            $this->service->getSecret()
        );
        $this->paymentsProtocol = new \AgmoPaymentsSimpleProtocol(
            $this->service->getUrl() . "/refund",
            $this->service->getMerchant(),
            $this->service->getSandbox(),
            $this->service->getSecret()
        );


    }

    /**
     * @param $price
     * @throws \Exception
     */
    public function createRefund($transId, $price, $refId = null)
    {
        $this->refId = $refId ? $refId : $this->paymentsDatabase->createNextRefId();
        $this->price = $price;


        $this->paymentsProtocol->refundTransaction(
            $transId,                                               // trans id
            $price,                                             // price
            $this->service->getCurrency(),                      // currency

        );
    }

    /**
     * @return Response
     * @throws \Exception
     */
    public function send()
    {
        $transId = $this->paymentsProtocol->getTransactionId();

//        \Tracy\Debugger::barDump($this->refId, '$this->refId');
//        $this->paymentsDatabase->saveTransaction(
//            $transId,                                           // transId
//            $this->refId,                                       // refId
//            $this->price,                                       // price
//            $this->service->getCurrency(),                      // currency
//            "PENDING"                                           // status
//        );

        return new Response($this->paymentsProtocol, $this->service);
    }




}