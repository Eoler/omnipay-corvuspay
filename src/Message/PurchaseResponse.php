<?php namespace Omnipay\CorvusPay\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RedirectResponseInterface;

class PurchaseResponse extends AbstractResponse implements RedirectResponseInterface
{
    /**
     * Set successful to false, as transaction is not completed yet
     * @return bool
     */
    public function isSuccessful()
    {
        return false;
    }
    /**
     * {@inheritdoc}
     */
    public function isRedirect()
    {
        return true;
    }
    /**
     * {@inheritdoc}
     */
    public function getRedirectUrl()
    {
        return $this->getRequest()->getEndpoint();
    }
    /**
     * {@inheritdoc}
     */
    public function getRedirectMethod()
    {
        return 'POST';
    }
    /**
     * {@inheritdoc}
     */
    public function getRedirectData()
    {
        return $this->data;
    }
}