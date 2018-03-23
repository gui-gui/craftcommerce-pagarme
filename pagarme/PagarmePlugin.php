<?php

namespace Craft;

class PagarmePlugin extends BasePlugin
{

    private $commerceInstalled = false;

    public function init()
    {

        $order = craft()->commerce_orders->getOrderById('3396');
        $paymentMethodId = $order->getPaymentMethod()->id;
        $paymentMethod = $order->getPaymentMethod();

        Craft::import('plugins.pagarme.enums.Pagarme_TransactionStatusesEnums');

        $commerce = craft()->db->createCommand()
            ->select('id')
            ->from('plugins')
            ->where("class = 'Commerce'")
            ->queryScalar();
        
        if($commerce){
            $this->commerceInstalled = true;
        }

        craft()->on('commerce_payments.onBeforeGatewayRequestSend', function($event){
            $request = $event->params['request'];
            $transaction = $event->params['transaction'];
            $postback_url = UrlHelper::getActionUrl('pagarme/webhook/postback', ['commerceTransactionId' => $transaction->id, 'commerceTransactionHash' => $transaction->hash]);
            $request->setPostbackUrl($postback_url);
        });
    }

    public function getName()
    {
        return "Pagarme Gateway";
    }

    /**
     * Returns the plugin’s version number.
     *
     * @return string The plugin’s version number.
     */
    public function getVersion()
    {
        return "0.0.1";
    }

    /**
     * Returns the plugin developer’s name.
     *
     * @return string The plugin developer’s name.
     */
    public function getDeveloper()
    {
        return "Gui Rams";
    }

    /**
     * Returns the plugin developer’s URL.
     *
     * @return string The plugin developer’s URL.
     */
    public function getDeveloperUrl()
    {
        return "#";
    }

    public function commerce_registerGatewayAdapters()
    {
        if($this->commerceInstalled) {
            require __DIR__ . '/vendor/autoload.php';
            require_once __DIR__.'/Pagarme_GatewayAdapter.php';
            require_once __DIR__.'/PagarmeBoleto_GatewayAdapter.php';
            require_once __DIR__.'/PagarmePaymentFormModel.php';
            return [
                '\Commerce\Gateways\Omnipay\Pagarme_GatewayAdapter', 
                '\Commerce\Gateways\Omnipay\PagarmeBoleto_GatewayAdapter'
            ];
        }
        return [];
    }

    // public function commerce_modifyItemBag($items, $order)
    // {   
    //     $method = $order->getPaymentMethod->class;

    //     if($method != 'Pagarme' && $method != 'Pagarme_Boleto') {
    //         return;
    //     }
        
    //     foreach ($items as $item) {
    //         $item->setTangible(true);
    //     }
    // }

}
