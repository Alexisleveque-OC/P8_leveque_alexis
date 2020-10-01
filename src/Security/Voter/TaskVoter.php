<?php

namespace App\Security\Voter;

use App\Entity\Task;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class TaskVoter extends Voter
{

    const TASK_DELETE = "TASK_DELETE";
    const TASK_EDIT = "TASK_EDIT";

    /**
     * @var Security
     */
    private Security $security;

    public function __construct(Security  $security)
    {
        $this->security = $security;
    }

    protected function supports($attribute, $subject)
    {
        return in_array($attribute, [self::TASK_DELETE, self::TASK_EDIT])
            && $subject instanceof Task;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();
        if (!$user instanceof UserInterface) {
            return false;
        }

        switch ($attribute) {
            case (self::TASK_DELETE || self::TASK_EDIT):
                return   $subject->getUser() === $user ||
                    ($subject->getUser() == null  &&  $this->security->isGranted("ROLE_ADMIN")) ;
                break;
        }

        return false;
    }
}
