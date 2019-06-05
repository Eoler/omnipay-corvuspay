<?php namespace Omnipay\CorvusPay\Message;

use Symfony\Component\HttpFoundation\ParameterBag;

use Omnipay\Common\Exception\InvalidResponseException;

class CompletePurchaseRequest extends PurchaseRequest
{
    /**
     * Validate request signature and return data
     * @param \Symfony\Component\HttpFoundation\ParameterBag $requestData
     * @return array
     * @throws \Omnipay\Common\Exception\InvalidResponseException
     */
    protected function validateRequest(ParameterBag $requestData)
    {
        $validata = array(
            'order_number'  => $requestData->get('order_number'),
            'language'      => $requestData->get('language'),
            'approval_code' => $requestData->get('approval_code'),
        );
        if (! hash_equals($requestData->get('signature'), $this->calcHash($validata))) {
            throw new InvalidResponseException("Invalid callback signature.");
        }
        return $requestData->all();
    }

    /**
     * Prepare and get data
     * @return mixed|void
     */
    public function getData()
    {
        return $this->validateRequest($this->httpRequest->request);
    }
    /**
     * Send data and return response
     * @param mixed $data
     * @return CompletePurchaseResponse
     */
    public function sendData($data)
    {
        return $this->response = new CompletePurchaseResponse($this, $data);
    }
}