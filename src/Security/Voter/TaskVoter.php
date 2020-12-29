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
    const TASK_LIST = "TASK_LIST";
    const TASK_CREATE = "TASK_CREATE";

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
        return in_array($attribute, [self::TASK_LIST, self::TASK_CREATE]) ||
            (in_array($attribute, [self::TASK_DELETE, self::TASK_EDIT])
                && $subject instanceof Task);
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();
        if (!$user instanceof UserInterface) {
            return false;
        }


        switch ($attribute) {
            case self::TASK_CREATE:
            case self::TASK_LIST :
                    return $token->getUser();
                break;
            case self::TASK_EDIT:
            case self::TASK_DELETE:
                return $this->isTaskOwner($subject, $user) ||
                    (!$subject->isNotAnonymous() && $this->security->isGranted("ROLE_ADMIN"));
                break;
        }
        return false;
    }

    private function isTaskOwner(Task $task, UserInterface $user){
        return $task->getUser() === $user;
    }
}
