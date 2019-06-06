<?php namespace Omnipay\CorvusPay;

use Omnipay\CorvusPay\Message\PurchaseRequest;
use Omnipay\CorvusPay\Message\CompletePurchaseRequest;
use Omnipay\Common\AbstractGateway;

/**
 * CorvusPay Gateway
 * @link https://cps.corvus.hr/public/corvuspay/
 */
class Gateway extends AbstractGateway
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'CorvusPay';
    }
    /**
     * @return array
     */
    public function getDefaultParameters()
    {
        return [
            'testMode' => false,
            'language' => 'hr',
            'storeId'  => '',
            'apiKey'   => '',
            'flexInstallments' => '',
        ];
    }
    /**
     * @param string $value
     * @return Gateway provides a fluent interface
     */
    public function setLanguage($value)
    {
        return $this->setParameter('language', $value);
    }
    /**
     * @return $this
     */
    public function getLanguage()
    {
        return $this->getParameter('language');
    }

    /**
     * @param string $value
     * @return Gateway provides a fluent interface
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
     * @return Gateway provides a fluent interface
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
     * @return Gateway provides a fluent interface
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
     * @return Gateway provides a fluent interface
     */
    public function initFlexInstallments($min, $max, $oneTime = true)
    {
        return $this->setFlexInstallments(sprintf('%s%02d%02d', $oneTime ? 'Y' : 'N', $min, $max));
    }

    /**
     * Create a purchase request
     * @param array $parameters
     * @return \Omnipay\CorvusPay\Message\PurchaseRequest
     */
    public function purchase(array $parameters = array())
    {
        return $this->createRequest(PurchaseRequest::class, $parameters);
    }
    /**
     * Complete a purchase from checkout callback
     * @param array $parameters
     * @return \Omnipay\CorvusPay\Message\CompletePurchaseRequest
     */
    public function completePurchase(array $parameters = array())
    {
        return $this->createRequest(CompletePurchaseRequest::class, $parameters);
    }
}
