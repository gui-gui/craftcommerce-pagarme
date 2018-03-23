<?php
namespace Craft;

abstract class Pagarme_TransactionStatusesEnums extends BaseEnum
{
	// Constants
	// =========================================================================

	const PROCESSING      = 'processing';
	const AUTHORIZED      = 'authorized';
	const PAID            = 'paid';
	const REFUNDED        = 'refunded';
	const WAITING_PAYMENT = 'waiting_payment';
	const PENDING_REFUND  = 'pending_refund';
	const REFUSED         = 'refused';
}
