<?php namespace Omnipay\CorvusPay\Message;

use Omnipay\Common\Message\AbstractResponse;

/**
 * Class CompletePurchaseResponse
 * @package Omnipay\CorvusPay\Message
 */
class CompletePurchaseResponse extends AbstractResponse
{
    /**
     * Indicates whether transaction was successful
     * @return bool
     */
    public function isSuccessful()
    {
        return ! empty($this->data['approval_code']);
    }
    /**
     * {@inheritdoc}
     */
    public function getTransactionReference()
    {
        return $this->data['approval_code'];
    }
    /**
     * {@inheritdoc}
     */
    public function getTransactionId()
    {
        return $this->data['order_number'];
    }
    /**
     * Get reference code generated by gateway
     * @return mixed|null|string
     */
    public function getCode()
    {
        return $this->getTransactionReference();
    }
}