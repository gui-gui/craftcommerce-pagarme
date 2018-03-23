<?php

namespace Craft;

class PagarmePlugin extends BasePlugin
{

    private $commerceInstalled = false;

    public function init()
    {
       
        $commerce = craft()->db->createCommand()
            ->select('id')
            ->from('plugins')
            ->where("class = 'Commerce'")
            ->queryScalar();
        
        if($commerce){
            $this->commerceInstalled = true;
        }
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
        return "1.0.0";
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

    public function getDocumentationUrl()
    {
    return 'https://github.com/gui-gui/craftcommerce-pagarme';
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
            require_once __DIR__.'/PagarmePaymentFormModel.php';
            return [
                '\Commerce\Gateways\Omnipay\Pagarme_GatewayAdapter' 
            ];
        }
        return [];
    }
}
