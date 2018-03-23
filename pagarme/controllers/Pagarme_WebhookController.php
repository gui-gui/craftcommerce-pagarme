<?php
namespace Craft;

use Craft\Pagarme_TransactionStatusesEnums as Statuses;

class Pagarme_WebhookController extends BaseController
{
  protected $allowAnonymous = true;

  // This function handles postbacks from Pagarme. 
  // for now it's used for Boleto transactions.
  public function actionPostback()
  {
    $this->requirePostRequest();
    
    $request = craft()->request;
    $hash = $request->getParam('commerceTransactionHash');
    $status = $request->getPost('current_status');
    $transaction = craft()->commerce_transactions->getTransactionByHash($hash);

    if (!$transaction)
    {
        throw new HttpException(400, "Could not complete postback for missing transaction.");
    }

    $customError = "";

    // TODO: allow for refund postback.
    if ($status == Statuses::PAID)
    {
      craft()->pagarme_boleto->completePaymentPostback($transaction, $customError);
    }
    else
    {
      $customError = "Can only handle postback with 'current_status' = 'paid', got '{$status}' instead.";
    }
    
    if($customError) 
    {
      PagarmePlugin::log(
        "[INFO] Order: {$transaction->order->id}. Postback for transaction {$hash} did not succeed. Error: {$customError}", 
        LogLevel::Info);
    }
    
  }
}