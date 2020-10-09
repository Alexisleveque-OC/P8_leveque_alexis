<?php


namespace App\Tests\Service;


use App\Entity\User;
use App\Service\User\RegisterService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegisterServiceTest extends TestCase
{
    /**
     * @var RegisterService
     */
    private RegisterService $RegisterService;

    public function setUp()
    {
        $manager = $this->createMock(EntityManagerInterface::class);
        $encoder = $this->createMock(UserPasswordEncoderInterface::class);
        $this->RegisterService = new RegisterService($manager, $encoder);
    }

    public function userProvider()
    {
        $user = new User();
        $user->setUsername('toto');
        $user->setPassword('coucou');
        $user->setEmail("toto@toto.com");

        return [[$user]];
    }

    /**
     * @dataProvider userProvider
     * @param User $user
     */
    public function testRegisterUser(User $user)
    {
        $test = $this->RegisterService->registerUser($user);

        $this->assertEquals($test->getUsername(),'toto');
        $this->assertEquals($test->getRoles()[0],'ROLE_USER');
        $this->assertInstanceOf(User::class, $test);
    }
}