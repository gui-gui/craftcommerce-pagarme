<?php	

namespace Commerce\Gateways\PaymentFormModels;

use Craft\AttributeType;
use Craft\BaseModel;
use Omnipay\Common\Helper as OmnipayHelper;

class PagarmePaymentFormModel extends CreditCardPaymentFormModel
{
	public function populateModelFromPost($post)
	{
		parent::populateModelFromPost($post);


		if (isset($post['token']))
		{
			$this->cardHash = $post['token'];
		}       

		if (isset($post['description']))
		{
			$this->softDescriptor = $post['description'];
		}

		if (isset($post['paymentMethod']))
		{
			$this->paymentMethod = $post['paymentMethod'];
		}

		if(isset($post['installments']))
		{
			$this->installments = $post['installments'] ?: 1;
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

		if(empty($this->token) && $this->paymentMethod != 'boleto')
		{
			return array_merge(parent::rules(), ['paymentMethod, installments, holderDocumentNumber', 'required']);
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
			'paymentMethod' => AttributeType::Enum,
			'installments' => [AttributeType::Number, 'default' => 1],
			'description' => AttributeType::String,
			'holderDocumentNumber' => AttributeType::String,
		];
		
		return array_merge($parent, $custom);
	}
}