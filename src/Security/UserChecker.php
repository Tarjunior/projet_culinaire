<?php 

namespace App\Security;

use App\Entity\User;
use App\Exception\MyNotConfirmedException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserCheckerInterface;

class UserChecker implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $user)
    {
        if(!$user instanceof User)
        {
            return;
        }

        if($user->getIsConfirmed() === false)
        {
            $exception = new MyNotConfirmedException();
            return $exception->getMessageKey();
        }
    }

    public function checkPostAuth(UserInterface $user)
    {
        // TODO: Implement checkPostAuth() method.
    }
}