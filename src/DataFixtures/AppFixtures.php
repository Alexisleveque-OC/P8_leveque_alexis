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

        $admin = new User();
        $admin->setUsername('Admin');
        $admin->setEmail('admin@gmail.com');
        $admin->setPassword('admin');
        $hash = $this->encoder->encodePassword($admin, $admin->getPassword());
        $admin->setPassword($hash);
        $admin->setRoles(['ROLE_ADMIN']);

        $manager->persist($admin);

        for ($k=1; $k<=5 ; $k++){
            $task = new Task();
            $task->setContent($faker->sentence);
            $task->setTitle($faker->word);
            $task->setCreatedAt(New \DateTime());

            if ($k <= 3){
                $task->setUser($admin);
            }
            if ($k >= 2 && $k <=4){
                $task->toggle(!$task->isDone());
            }

            $manager->persist($task);
        }



        for ($i = 1; $i <= 5; $i++){
            $user = new User();
            $user->setUsername(sprintf('User%d', $i));
            $user->setEmail(sprintf('user%d@gmail.com', $i));
            $user->setPassword('coucou');
            $hash = $this->encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hash);
            $user->setRoles(['ROLE_USER']);

            $manager->persist($user);

            for ($j=1; $j<=5 ; $j++){
                $task = new Task();
                $task->setContent($faker->sentence);
                $task->setTitle($faker->word);
                $task->setCreatedAt(New \DateTime());

                if ($j <= 3){
                    $task->setUser($user);
                }
                if ($j >= 2 && $j <=4){
                    $task->toggle(!$task->isDone());
                }

                $manager->persist($task);
            }
        }

        $manager->flush();
    }
}
