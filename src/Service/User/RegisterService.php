<?php


namespace App\Service\User;


use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegisterService
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $manager;
    /**
     * @var UserPasswordEncoderInterface
     */
    private UserPasswordEncoderInterface $encoder;

    public function __construct(EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder)
    {
        $this->manager = $manager;
        $this->encoder = $encoder;
    }

    public function registerUser(User $user)
    {
        $password = $this->encoder->encodePassword($user, $user->getPassword());
        $user->setPassword($password);

        $this->manager->persist($user);
        $this->manager->flush();
    }
}