<?php


namespace App\Tests\Controller;


use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SmokeTest extends WebTestCase
{
    /**
     * @dataProvider provideUrls
     * @param $pageName
     * @param $url
     * @param string $method
     * @param int $expectedStatusCode
     * @param bool $withLogin
     */
    public function testPageIsSuccessful($pageName,
                                         $url,
                                         $method = "GET",
                                         $expectedStatusCode = 200,
                                         $loggedAs = null)
    {
        $client = self::createClient();
        if ($loggedAs) {
            $userRepository = static::$container->get(UserRepository::class);
            $testUser = $userRepository->findOneBy(['username' => $loggedAs]);
            $client->loginUser($testUser);
        }

        $client->request($method, $url);
        $response = $client->getResponse();

        self::assertSame(
            $response->getStatusCode(),
            $expectedStatusCode
        );
    }

    public function provideUrls()
    {
        yield['homepage', '/' ];
        yield['homepage', '/', 'GET', 200, 'Admin'];
        yield['homepage', '/', 'GET', 200, 'User1'];
        yield['login', '/login'];
        yield['login', '/login', 'GET', 200, 'Admin'];
        yield['login', '/login', 'GET', 200, 'User1'];
        yield['logout', '/logout', 'GET', 302];
        yield['logout', '/logout', 'GET', 302, 'Admin'];
        yield['logout', '/logout', 'GET', 302, 'User1'];
        yield['task_list', '/tasks', 'GET', 302];
        yield['task_list', '/tasks', 'GET', 200, 'Admin'];
        yield['task_list', '/tasks', 'GET', 200, 'User1'];
        yield['task_list', '/tasks-done', 'GET', 302];
        yield['task_list', '/tasks-done', 'GET', 200, 'Admin'];
        yield['task_list', '/tasks-done', 'GET', 200, 'User1'];
        yield['task_create', '/tasks/create','GET',302];
        yield['task_create', '/tasks/create', 'GET', 200, 'Admin'];
        yield['task_create', '/tasks/create', 'GET', 200, 'User1'];
        // Task 1 create by Admin
        yield['task_edit', '/tasks/1/edit', 'GET', 302]; //redirect to "/login"
        yield['task_edit', '/tasks/1/edit', 'GET', 200, 'Admin'];
        yield['task_edit', '/tasks/1/edit', 'GET', 403, 'User1'];
        yield['task_toggle', '/tasks/1/toggle', 'GET', 302]; //redirect to "/login"
        yield['task_toggle', '/tasks/1/toggle', 'GET', 302, 'Admin']; //succesful and redirect to '/tasks'
        yield['task_toggle', '/tasks/1/toggle', 'GET', 403, 'User1'];
        yield['task_delete', '/tasks/1/delete', 'GET', 302]; //redirect to "/login"
        yield['task_delete', '/tasks/1/delete', 'GET', 302, 'Admin']; //succesful and redirect to '/tasks'
        yield['task_delete', '/tasks/1/delete', 'GET', 403, 'User1'];
        // Task 6 create by User1
        yield['task_edit', '/tasks/6/edit', 'GET', 302]; //redirect to "/login"
        yield['task_edit', '/tasks/6/edit', 'GET', 403, 'Admin'];
        yield['task_edit', '/tasks/6/edit', 'GET', 200, 'User1'];
        yield['task_toggle', '/tasks/6/toggle', 'GET', 302]; //redirect to "/login"
        yield['task_toggle', '/tasks/6/toggle', 'GET', 403, 'Admin'];
        yield['task_toggle', '/tasks/6/toggle', 'GET', 302, 'User1']; //succesful and redirect to '/tasks'
        yield['task_delete', '/tasks/6/delete', 'GET', 302]; //redirect to "/login"
        yield['task_delete', '/tasks/6/delete', 'GET', 403, 'Admin'];
        yield['task_delete', '/tasks/6/delete', 'GET', 302, 'User1']; //succesful and redirect to '/tasks'
        // Task 4 is anonyme
        yield['task_edit', '/tasks/4/edit', 'GET', 302]; //redirect to "/login"
        yield['task_edit', '/tasks/4/edit', 'GET', 200, 'Admin'];
        yield['task_edit', '/tasks/4/edit', 'GET', 403, 'User1'];
        yield['task_toggle', '/tasks/4/toggle', 'GET', 302]; //redirect to "/login"
        yield['task_toggle', '/tasks/4/toggle', 'GET', 302, 'Admin']; //succesful and redirect to '/tasks'
        yield['task_toggle', '/tasks/4/toggle', 'GET', 403, 'User1'];
        yield['task_delete', '/tasks/4/delete', 'GET', 302]; //redirect to "/login"
        yield['task_delete', '/tasks/4/delete', 'GET', 302, 'Admin']; //succesful and redirect to '/tasks'
        yield['task_delete', '/tasks/4/delete', 'GET', 403, 'User1'];

        yield['user_list', '/users', 'GET', 302]; //redirect to "/login"
        yield['user_list', '/users', 'GET', 200, 'Admin'];
        yield['user_list', '/users', 'GET', 403, 'User1'];
        yield['user_create', '/users/create', 'GET', 302]; //redirect to "/login"
        yield['user_create', '/users/create', 'GET', 200, 'Admin'];
        yield['user_create', '/users/create', 'GET', 403, 'User1'];
        // User id 1 = Admin
        yield['user_edit', '/users/1/edit', 'GET', 302]; //redirect to "/login"
        yield['user_edit', '/users/1/edit', 'GET', 200, 'Admin'];
        yield['user_edit', '/users/1/edit', 'GET', 403, 'User1'];
        // User id 2 = User1
        yield['user_edit', '/users/1/edit', 'GET', 302]; //redirect to "/login"
        yield['user_edit', '/users/1/edit', 'GET', 200, 'Admin'];
        yield['user_edit', '/users/1/edit', 'GET', 403, 'User1'];


    }

}