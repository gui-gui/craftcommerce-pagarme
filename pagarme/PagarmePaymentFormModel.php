<?php	

namespace Commerce\Gateways\PaymentFormModels;

use Craft\AttributeType;

class PagarmePaymentFormModel extends CreditCardPaymentFormModel
{
	public function populateModelFromPost($post)
	{
		parent::populateModelFromPost($post);

		if (isset($post['token']))
		{
			$this->token = $post['token'];
		}

		if (isset($post['cardHash']))
		{
			$this->token = $post['cardHash'];
		}  

		if (isset($post['paymentMethod']))
		{
			$this->paymentMethod = $post['paymentMethod'];
		}
		
		if (isset($post['postbackUrl']))
		{
			$this->postbackUrl = $post['postbackUrl'];
		}
		
		if (isset($post['boletoExpirationDate']))
		{
			$this->boletoExpirationDate = $post['boletoExpirationDate'];
		}	
		
		if (isset($post['holderDocumentNumber']))
		{
			$this->holderDocumentNumber = $post['holderDocumentNumber'];
		}

		if(isset($post['installments']))
		{
			$this->installments = $post['installments'] ?: 1;
		}
		
		if (isset($post['softDescriptor']))
		{
			$this->softDescriptor = $post['softDescriptor'];
		}	

		if (isset($post['shippingFee']))
		{
			$this->shippingFee = $post['shippingFee'];
		}
	}


	/**
	 * @return array
	 */
	public function rules()
	{
		if ($this->token)
		{
			return [
				['paymentMethod, installments', 'required'],
				[
					'installments',
					'numerical',
					'integerOnly' => true,
					'min'         => 1,
					'max'         => 12
				],
				[
					'paymentMethod',
					'in',
					'range' => [
						'boleto',
						'credit_card'
					]
				],
			];	
		}

		if(empty($this->token) && $this->paymentMethod == 'credit_card')
		{
			return array_merge(parent::rules(), [['paymentMethod, installments, holderDocumentNumber', 'required']]);
		}

		return [];
	}	

	/**
	 * @return array
	 */
	protected function defineAttributes()
	{
		$parent = parent::defineAttributes();
		$custom = [
			// parent already has token
			'paymentMethod' => AttributeType::Enum,
			'installments' => [AttributeType::Number, 'default' => 1],
			'holderDocumentNumber' => AttributeType::String,
			'boletoExpirationDate' => AttributeType::String,
			'softDescriptor' => AttributeType::String,
			'postbackUrl' => AttributeType::String,
			'shippingFee' => [AttributeType::Number, 'default' => 0]
		];
		
		return array_merge($parent, $custom);
	}
}