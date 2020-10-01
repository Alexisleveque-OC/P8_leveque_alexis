<?php

namespace App\Security\Voter;

use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class UserVoter extends Voter
{
    const USER_LIST = "USER_LIST";
    const USER_CREATE = "USER_CREATE";
    const USER_EDIT = "USER_EDIT";

    /**
     * @var Security
     */
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports($attribute, $subject)
    {
        return in_array($attribute, [self::USER_LIST, self::USER_CREATE]) ||
            (in_array($attribute, [self::USER_EDIT]) && $subject instanceof User);
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();
        if (!$user instanceof UserInterface) {
            return false;
        }

        switch ($attribute) {
            case (self::USER_LIST ||
                self::USER_CREATE ||
                self::USER_EDIT
            ):
                return $this->security->isGranted("ROLE_ADMIN");
                break;
        }

        return false;
    }
}
