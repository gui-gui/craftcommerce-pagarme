<?php

namespace Commerce\Gateways\Omnipay;

use Omnipay\Common\Message\AbstractRequest as OmnipayRequest;
use Craft\AttributeType;
use Craft\BaseModel;

class PagarmeBoleto_GatewayAdapter extends Pagarme_GatewayAdapter
{
    public function handle()
    {
        return "Pagarme_Boleto";
    }


    public function __construct($attributes = null)
	{
		parent::__construct($attributes);
		$this->init();
    }
    
    public function init() 
    {
        $defaults = $this->getDefaultParameters();

        //fill selects
        $this->_selects = array_filter($defaults, 'is_array');
        foreach ($this->_selects as $param => &$values)
        {
            $values = array_combine($values, $values);
        }

        //fill booleans
        foreach ($defaults as $key => $value)
        {
            if (is_bool($value))
            {
                $this->_booleans[] = $key;
            }
        }
    }

    public function getDefaultParameters()
    {
        $statuses = array();
        foreach (\Craft\craft()->commerce_orderStatuses->getAllOrderStatuses() as $orderStatus) {
            $statuses[$orderStatus->handle] = $orderStatus->name;
        }
        
        return array(
            'apiKey'   => '',
            'encryptionKey' => '',
            'onSuccessStatus' => array_keys($statuses),
            'onRefundStatus' => array_keys($statuses),
        );
    }
    
	public function defineAttributes()
	{
        $attr = parent::defineAttributes();
        $attr['onSuccessStatus']['label'] = $this->generateAttributeLabel('onSuccessStatus');
        $attr['onRefundStatus']['label'] = $this->generateAttributeLabel('onRefundStatus');

        return $attr;
	}
    
}
