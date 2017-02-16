<?php

namespace tests;


use Omnipay\FirstAtlanticCommerce\Gateway;
use Omnipay\FirstAtlanticCommerce\Message\UpdateCardResponse;
use Omnipay\Tests\GatewayTestCase;

class UpdateCardTest extends GatewayTestCase
{

    /** @var  Gateway */
    protected $gateway;
    /** @var  array */
    private $updateOptions;

    public function setUp()
    {
        $this->gateway = new Gateway($this->getHttpClient(), $this->getHttpRequest());

        $this->gateway->setMerchantId('123');
        $this->gateway->setMerchantPassword('abc123');

        $this->updateOptions = [
            'customerReference'=>'John Doe',
            'cardReference'=>'411111_000011111',
            'card'=>$this->getValidCard()
        ];
    }

    public function testSuccessfulCardUpdate()
    {
        $this->setMockHttpResponse('UpdateCardSuccess.txt');

        /** @var UpdateCardResponse $response */
        $response = $this->gateway->updateCard($this->updateOptions)->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertEmpty($response->getMessage());
        $this->assertEquals('411111_000011111', $response->getCardReference());
    }

    public function testFailedCardUpdate()
    {
        $this->setMockHttpResponse('UpdateCardFailure.txt');

        /** @var UpdateCardResponse $response */
        $response = $this->gateway->updateCard($this->updateOptions)->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertEquals('No Data', $response->getMessage());
        $this->assertEmpty($response->getCardReference());
    }
}