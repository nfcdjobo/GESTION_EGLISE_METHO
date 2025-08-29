<?php
// app/Exceptions/ConcurrentUpdateException.php

namespace App\Exceptions;

use Exception;

class ConcurrentUpdateException extends Exception
{
    protected $message = 'La ressource a été modifiée par un autre utilisateur. Veuillez actualiser et réessayer.';
}
