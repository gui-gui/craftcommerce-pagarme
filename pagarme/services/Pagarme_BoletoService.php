<?php
namespace Craft;

use Omnipay\Pagarme\Message\FetchTransactionRequest;
use Omnipay\Pagarme\Message\FetchTransactionResponse;
use Craft\Pagarme_TransactionStatusesEnums as Statuses;

class Pagarme_BoletoService extends BaseApplicationComponent
{ 

    public function completePaymentPostback(
        Commerce_TransactionModel $transaction,
        &$customError = null
    )
    {   
        if ($transaction->paymentMethod->class != 'Pagarme_Boleto')
        {
            return;
        }
    
        $successStatusHandle = $transaction->paymentMethod->getGatewayAdapter()->getAttributes()['onSuccessStatus'];
        $order = $transaction->order;
        $currentStatusHandle = $order->orderStatus->handle;
        
        if($currentStatusHandle == $successStatusHandle) {
            $customError = 'Status do pedido igual ao status final desejado com o postback.';
            return;
        }
        
        if (!$transaction->canCapture()) {
            $customError = 'Não é possível capturar essa transação.';
            return;
        }
        
        $newTransaction = craft()->commerce_payments->captureTransaction($transaction);

        if ($newTransaction->status == Commerce_TransactionRecord::STATUS_SUCCESS) {
            craft()->commerce_orders->updateOrderPaidTotal($order);

            $successStatus = craft()->commerce_orderStatuses->getOrderStatusByHandle($successStatusHandle);
            $successStatusId = $successStatus->id;
            
            // now update order status history
            $order->orderStatusId = $successStatusId;
            $order->message = "Atualizado após notificação da API Pagarme";
            craft()->commerce_orders->saveOrder($order);
            PagarmePlugin::log("Info: Order {$order->hash} updated after Postback to '{$successStatus->name}' status.", LogLevel::Info);
        }
    }

}
