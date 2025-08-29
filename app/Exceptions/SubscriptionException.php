<?php

// =================================================================
// app/Exceptions/SubscriptionException.php

namespace App\Exceptions;

use Exception;

class SubscriptionException extends Exception
{
    public static function alreadyExists(): self
    {
        return new self('Une souscription existe déjà pour cette FIMECO.');
    }

    public static function fimecoInactive(): self
    {
        return new self('Cette FIMECO n\'est plus active pour les souscriptions.');
    }

    public static function amountTooLow(float $minimum): self
    {
        return new self("Le montant minimum de souscription est de {$minimum}.");
    }
}

