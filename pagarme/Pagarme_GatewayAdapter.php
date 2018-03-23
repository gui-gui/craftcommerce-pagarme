<?php
namespace Commerce\Gateways\Omnipay;

use Omnipay\Pagarme\ItemBag as PagarmeItemBag;
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

    public function createItemBag()
    {
        return new PagarmeItemBag();
    }

    public function getPaymentFormModel()
	{
		return new PagarmePaymentFormModel();
	}

    public function populateRequest(OmnipayRequest $request, BaseModel $paymentForm)
    {
        if ($paymentForm->token)
        {
            $request->setToken($paymentForm->token);
        }

        if ($paymentForm->paymentMethod)
        {
            $request->setPaymentMethod($paymentForm->paymentMethod);
        }
        
        if ($paymentForm->postbackUrl)
        {
            $request->setPostbackUrl($paymentForm->postbackUrl);
        }
        
        if ($paymentForm->holderDocumentNumber)
        {
            $request->setHolderDocumentNumber($paymentForm->holderDocumentNumber);
        }
        
        if ($paymentForm->softDescriptor)
        {
            $request->setSoftDescriptor($paymentForm->softDescriptor);
        }
        
        if ($paymentForm->installments)
        {
            $request->setInstallments($paymentForm->installments);
        }

        if ($paymentForm->boletoExpirationDate)
        {
            $request->setBoletoExpirationDate($paymentForm->boletoExpirationDate);
        }

        if ($paymentForm->shippingFee)
        {
            $request->setShippingFee($paymentForm->shippingFee);
        }

    }

	public function defineAttributes()
	{
		// In addition to the standard gateway config, here is some custom config that is useful.
		$attr = parent::defineAttributes();
		$attr['encryptionKey'] = [AttributeType::String];
		$attr['encryptionKey']['label'] = $this->generateAttributeLabel('encryptionKey');

		return $attr;
	}
    
}
