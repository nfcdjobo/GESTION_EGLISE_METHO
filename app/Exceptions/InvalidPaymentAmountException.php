<?php

// =================================================================
// app/Exceptions/InvalidPaymentAmountException.php

namespace App\Exceptions;

use Exception;

class InvalidPaymentAmountException extends Exception
{
    protected $message = 'Montant de paiement invalide.';
}
