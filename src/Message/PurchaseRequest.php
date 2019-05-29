<?php namespace Omnipay\CorvusPay\Message;

use Omnipay\Common\Message\AbstractRequest;

class PurchaseRequest extends AbstractRequest
{
   /**
     * @var string
     */
    protected $prodEndpoint = 'https://wallet.corvuspay.com/checkout/';
    /**
     * @var string
     */
    protected $testEndpoint = 'https://test-wallet.corvuspay.com/checkout/';

    /**
     * @return string
     */
    public function getEndpoint()
    {
        return $this->getTestMode() ? $this->testEndpoint : $this->prodEndpoint;
    }

    /**
     * @param string $value
     * @return PurchaseRequest provides a fluent interface
     */
    public function setLanguage($value)
    {
        return $this->setParameter('language', $value);
    }
    /**
     * @return mixed
     */
    public function getLanguage()
    {
        return $this->getParameter('language');
    }
    /**
     * @param string $value
     * @return PurchaseRequest provides a fluent interface
     */
    public function setStoreId($value)
    {
        return $this->setParameter('storeId', $value);
    }
    /**
     * @return mixed
     */
    public function getStoreId()
    {
        return $this->getParameter('storeId');
    }
    /**
     * @param string $value
     * @return PurchaseRequest provides a fluent interface
     */
    public function setApiKey($value)
    {
        return $this->setParameter('apiKey', $value);
    }
    /**
     * @return mixed
     */
    public function getApiKey()
    {
        return $this->getParameter('apiKey');
    }
    /**
     * @param string $value
     * @return PurchaseRequest provides a fluent interface
     */
    public function setEmail($value)
    {
        return $this->setParameter('email', $value);
    }
    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->getParameter('email');
    }
    /**
     * @param string $value
     * @return PurchaseRequest provides a fluent interface
     */
    public function setCardholderName($value)
    {
        return $this->setParameter('cardholderName', $value);
    }
    /**
     * @return mixed
     */
    public function getCardholderName()
    {
        return $this->getParameter('cardholderName');
    }
    /**
     * @param string $value
     * @return PurchaseRequest provides a fluent interface
     */
    public function setCardholderSurname($value)
    {
        return $this->setParameter('cardholderSurname', $value);
    }
    /**
     * @return mixed
     */
    public function getCardholderSurname()
    {
        return $this->getParameter('cardholderSurname');
    }

    /**
     * Calculate HMAC-SHA256 hash of the transaction data.
     * @param $reqdata
     * @return string
     */
    public function calcHash($data)
    {
        ksort($data);
        $dataconcat = '';
        foreach ($data as $key => $value) {
            $dataconcat .= $key . $value;
        }
        $data['signature'] = hash_hmac('sha256', $dataconcat, $this->getApiKey());
        return hash_hmac('sha256', $dataconcat, $this->getApiKey());
    }

    /**
     * Prepare all required data for sending
     * @return array
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    public function getData()
    {
        $this->validate('language', 'storeId', 'apiKey', 'amount', 'currency', 'description');
        $data = array('version' => '1.3');
        $data['store_id']           = $this->getStoreId();
        $data['order_number']       = $this->getTransactionId();
        $data['language']           = $this->getLanguage();
        $data['currency']           = $this->getCurrency();
        $data['amount']             = $this->getAmount();
        $data['cart']               = $this->getDescription();
        $data['require_complete']   = 'false'; // sale
        if (!empty($this->getEmail())) {
            $data['cardholder_email'] = $this->getEmail();
        }
        if (!empty($this->getCardholderName())) {
            $data['cardholder_name'] = $this->getCardholderName();
        }
        if (!empty($this->getCardholderSurname())) {
            $data['cardholder_surname'] = $this->getCardholderSurname();
        }
        // calculate signature from mandatory request data
        $data['signature'] = $this->calcHash($data);
        return $data;
    }
    /**
     * Send data and return response instance
     * @param mixed $data
     * @return PurchaseResponse
     */
    public function sendData($data)
    {
        return $this->response = new PurchaseResponse($this, $data);
    }
}