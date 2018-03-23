<?php 

namespace Commerce\Gateways\Omnipay;

use Commerce\Gateways\PaymentFormModels\PagarmePaymentFormModel;
use Omnipay\Common\Message\AbstractRequest as OmnipayRequest;
use Craft\AttributeType;
use Craft\BaseModel;

class Pagarme_GatewayAdapter extends \Commerce\Gateways\CreditCardGatewayAdapter 
{
    public function handle() 
    {
        return "Pagarme";
    }
    
    public function getPaymentFormModel() 
    {
        return new PagarmePaymentFormModel();
    }
    
    public function populateRequest(OmnipayRequest $request, BaseModel $paymentForm) 
    {
        if ($paymentForm->paymentMethod) 
        {
            $request->setPaymentMethod($paymentForm->paymentMethod);
        }

        if ($paymentForm->holderDocumentNumber) 
        {
            $request->setHolderDocumentNumber($paymentForm->holderDocumentNumber);
        }

        if ($paymentForm->installments) 
        {
            $request->setInstallments($paymentForm->installments);
        }

        if ($paymentForm->token) 
        {
            $request->setToken($paymentForm->token);
        }
    }

    public function defineAttributes() 
    {
        $attr = array();
        $attr['apiKey'] = [AttributeType::String];
        $attr['apiKey']['label'] = $this->generateAttributeLabel('apiKey');
        $attr['publicKey'] = [AttributeType::String];
        $attr['publicKey']['label'] = $this->generateAttributeLabel('publicKey');
        $attr['testMode'] = [AttributeType::Bool];
        $attr['testMode']['label'] = $this->generateAttributeLabel('testMode');

        return $attr;
    }
}