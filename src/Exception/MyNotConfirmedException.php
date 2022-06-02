<?php 

namespace App\Exception;

use Symfony\Component\Security\Core\Exception\AccountStatusException;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class MyNotConfirmedException extends AccountStatusException
{
    public function getMessageKey(): string
    {
        throw new AuthenticationException("Votre compte n'est pas encore validé. Veuillez confirmer votre e-mail. Bien Cordialement.");
    }
}