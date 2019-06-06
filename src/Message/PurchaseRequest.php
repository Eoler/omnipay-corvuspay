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
    public function setFlexInstallments($value)
    {
        return $this->setParameter('flexInstallments', $value);
    }
    /**
     * @return mixed
     */
    public function getFlexInstallments()
    {
        return $this->getParameter('flexInstallments');
    }
    /**
     * @param string $min  Minimum monthly installments (2+)
     * @param string $max  Maximum monthly installments (-99)
     * @param boolean $oneTime  One-time payments are allowed
     * @return PurchaseRequest provides a fluent interface
     */
    public function initFlexInstallments($min, $max, $oneTime = true)
    {
        return $this->setFlexInstallments(sprintf('%s%02d%02d', $oneTime ? 'Y' : 'N', $min, $max));
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
     * @param string $value
     * @return PurchaseRequest provides a fluent interface
     */
    public function setCardholderAddress($value)
    {
        return $this->setParameter('cardholderAddress', $value);
    }
    /**
     * @return mixed
     */
    public function getCardholderAddress()
    {
        return $this->getParameter('cardholderAddress');
    }
    /**
     * @param string $value
     * @return PurchaseRequest provides a fluent interface
     */
    public function setCardholderCity($value)
    {
        return $this->setParameter('cardholderCity', $value);
    }
    /**
     * @return mixed
     */
    public function getCardholderCity()
    {
        return $this->getParameter('cardholderCity');
    }
    /**
     * @param string $value
     * @return PurchaseRequest provides a fluent interface
     */
    public function setCardholderZipCode($value)
    {
        return $this->setParameter('cardholderZipCode', $value);
    }
    /**
     * @return mixed
     */
    public function getCardholderZipCode()
    {
        return $this->getParameter('cardholderZipCode');
    }
    /**
     * @param string $value
     * @return PurchaseRequest provides a fluent interface
     */
    public function setCardholderCountry($value)
    {
        return $this->setParameter('cardholderCountry', $value);
    }
    /**
     * @return mixed
     */
    public function getCardholderCountry()
    {
        return $this->getParameter('cardholderCountry');
    }
    /**
     * @param string $value
     * @return PurchaseRequest provides a fluent interface
     */
    public function setCardholderPhone($value)
    {
        return $this->setParameter('cardholderPhone', $value);
    }
    /**
     * @return mixed
     */
    public function getCardholderPhone()
    {
        return $this->getParameter('cardholderPhone');
    }
    /**
     * @param string $value
     * @return PurchaseRequest provides a fluent interface
     */
    public function setCardholderEmail($value)
    {
        return $this->setParameter('cardholderEmail', $value);
    }
    /**
     * @return mixed
     */
    public function getCardholderEmail()
    {
        return $this->getParameter('cardholderEmail');
    }

    /**
     * Calculate HMAC-SHA256 hash of the transaction data.
     * @param array $data
     * @return string
     */
    public function calcHash($data)
    {
        ksort($data);
        $dataconcat = '';
        foreach ($data as $key => $value) {
            $dataconcat .= $key . $value;
        }
        return hash_hmac('sha256', $dataconcat, $this->getApiKey());
    }

    /**
     * Prepare all required data for sending
     * @return array
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    public function getData()
    {
        $this->validate('apiKey', 'storeId', 'transactionId', 'language', 'currency', 'amount', 'description');
        $pars = $this->parameters->all();
        $data = array('version' => '1.3');
        $data['store_id']         = $pars['storeId'];
        $data['order_number']     = str_limit($pars['transactionId'], 30);
        $data['language']         = $pars['language'];
        $data['currency']         = $pars['currency'];
        $data['amount']           = $pars['amount'];
        $data['cart']             = str_limit($pars['description'], 255);
        $data['require_complete'] = 'false'; // sale
        if (!empty($pars['flexInstallments'])) { // flexible number of monthly installments
            $data['payment_all'] = $pars['flexInstallments'];
        }
        if (!empty($pars['cardholderName']))    $data['cardholder_name']     = str_limit($pars['cardholderName'], 40);
        if (!empty($pars['cardholderSurname'])) $data['cardholder_surname']  = str_limit($pars['cardholderSurname'], 40);
        if (!empty($pars['cardholderAddress'])) $data['cardholder_address']  = str_limit($pars['cardholderAddress'], 40);
        if (!empty($pars['cardholderCity']))    $data['cardholder_city']     = str_limit($pars['cardholderCity'], 20);
        if (!empty($pars['cardholderZipCode'])) $data['cardholder_zip_code'] = str_limit($pars['cardholderZipCode'], 9);
        if (!empty($pars['cardholderCountry'])) $data['cardholder_country']  = str_limit($pars['cardholderCountry'], 30);
        if (!empty($pars['cardholderPhone']))   $data['cardholder_phone']    = str_limit($pars['cardholderPhone'], 30);
        if (!empty($pars['cardholderEmail']))   $data['cardholder_email']    = str_limit($pars['cardholderEmail'], 40);
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