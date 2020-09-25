<?php

namespace App\DataFixtures;

use App\Entity\Task;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private UserPasswordEncoderInterface $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        for ($i = 1; $i <= 5; $i++){
            $user = new User();
            $user->setUsername(sprintf('User%d', $i));
            $user->setEmail(sprintf('User%d@gmail.com', $i));
            $user->setPassword('coucou');
            $hash = $this->encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hash);

            $manager->persist($user);

            for ($j=1; $j<=5 ; $j++){
                $task = new Task();
                $task->setContent($faker->sentence);
                $task->setTitle($faker->word);
                $task->setCreatedAt(New \DateTime());

                $manager->persist($task);
            }
        }

        $manager->flush();
    }
}
