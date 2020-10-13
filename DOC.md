List of Paths
=============

|Controller|Route's name|Path|Description|Security|
|----------|------------|----|-----------|--------|
|DefaultController|homepage|/|Display the Homepage|Anonymous|
|SecurityController|login|/login|Display the form Login|Anonymous|
|SecurityController|logout|/logout|Disconnect user connected|Authenticated|
|TaskController|task_list|/tasks|Display the list of all tasks "to do" link to user and anonymous if admin|Authenticated|
|TaskController|task_done_list|/tasks/dones|Display the list of all tasks "done" link to user and anonymous if admin|Authenticated|
|TaskController|task_create|/tasks/create|Used to create a new Task|Authenticated|
|TaskController|task_edit|/tasks/{id}/edit|Used to edit an existing task|Authenticated and Owner of task or admin if task is anonymous|
|TaskController|task_toggle|/tasks/{id}/toggle|Mark the task done|Authenticated and Owner of the task or admin if task is anonymous|
|TaskController|task_delete|/tasks/{id}/delete|Delete the task|Authenticated and Owner of task or admin if task is anonymous|
|UserController|user_list|/users|Display the list of users|Authenticated and Admin|
|UserController|user_create|/users/create|Used to create a new User|Authenticated and Admin|
|UserController|user_edit|/users/{id}/edit|Used to edit an existing User|Authenticated and Admin|


Controllers
===========

Controllers are the first files called when a user click on your application.
If you want to add a new features, first you have to create a controller and give him a route to catch it.
Use the maker to create that controller : 
```
php bin/console make:controller
```

The new Controller will be created in __src/Controller__. This is where all controllers belong.

Symfony will create a route for him but feel free to change it to your own.
Routes look like this :
```php
/**
 * @Route("/", name="homepage")
 */
public function indexAction(){}
```

First parameter for Route is the url that will be displayed.
Second is the name that will be used in your templates or code.

Entities
========

Entities are objects that represent database tables.
They are hydrated automatically when Doctrine do some request in database.

If you want to create a new entity, you can use the maker :
```
php bin/console make:entity
```

Control or create some fixtures in __src/DataFixtures/appFixtures.php__ for work in good conditions.

When your new Entity is created, you must update your database.
For this enter in console :

```
composer prepare
```

This command was a maccro created for this project. In fact, it's do this : 
```
php bin/console d:d:drop --if-exists -f 
&& php bin/console d:d:create 
&& php bin/console d:s:update -f
&& php bin/console d:fixtures:load -n
```
- first command: Delete Database on your machine if exists
- second one: Create new Database with old(s) and new(s) Entity
- third one: Update schema, create all tables and Column for each Entity
- fourth one: load fixtures

(You can enter the command one after one if you won't load fixtures)

Repositories
============

Repositories are the tool used in symfony to request your database.
Every repository is linked to an Entity.
Ex : with the __Article__ Entity you'll have the __ArticleRepository__.

## good practices
You can create your own request using the QueryBuilder in the repository file. 
See [TaskRepository](https://github.com/Alexisleveque-OC/P8_leveque_alexis/blob/master/src/Repository/TaskRepository.php) for follow example

- in a first time : Create function getBaseQueryBuilder this one select Table which you want to work and joined table if it's necessary.
````php
protected function getBaseQueryBuilder()
    {
            // here "t" is Task and "u" is User
        return $this->createQueryBuilder("t")
            ->select('t, u')
            ->leftJoin('t.user', 'u');
    }
````
- in a second time : Create static function with one clause
````php
static function addDoneClause(QueryBuilder $qb, bool $isDone)
    {
        return $qb->andWhere('t.isDone = :isDone')
            ->setParameter('isDone', $isDone);
    }
````
- for finish : create your function with a name as precise as possible (ex: 'FindUserWhoHaveOneTask'). For construct your function,
 get your baseQueryBuilder -> add somme clause -> return result
````php
public function findAllTasksDoneByUser(User $user, $withAnonymous = false)
           {
               $qb = $this->getBaseQueryBuilder();
               
               self::addDoneClause($qb, true);
               self::addUserClause($qb, $user);
               
               return $qb->getQuery()
                   ->getResult();
           }
````

## bad practices
With repositories, you can use magical function that are already created lile __findAll()__. 

You can have more informations in the Documentation Here :
[Doctrine Documentation](https://symfony.com/doc/current/doctrine.html)

Forms
=====

In a symfony environnement, forms are managed differently than in a traditional php/html application.

You can create form related (or not) with One Entity. For this enter
``
php bin/console make:form
``
And follow instruction.

You can personalize your forms in __src/forms__ directory. (You can personalize in your template too).

Forms are created directly in your controller and passed to your template by those lines of code :
```php
$form = $this->createForm(YourEntityNameForm::class, $yourEntity);

return $this->render('yourTemplate.html.twig', [
    'form' => $form->createView()
]);
```
First line create a form according to what is described in the class.
Second line pass the form to your template.

Now you have to display your template, so in it you'll have :
```
{{form_start(form)}}
{{form_end(form)}}
```

That will display __all the elements__ that can be displayed.

If you want more details on how you can manage your forms go to Symfony Documentation :
[Forms Documentation](https://symfony.com/doc/current/forms.html)

Authorization
=============

The Authorization for access to some Routes must be passed with a voter. 
For example, for access to the "task_list", the user must be connected. 

See [TaskController](https://github.com/Alexisleveque-OC/P8_leveque_alexis/blob/master/src/Controller/TaskController.php) for follow.
````php
/**
     * @Route("/tasks", name="task_list")
     * @param TaskRepository $taskRepository
     * @param Security $security
     * @return Response
     * @IsGranted ("TASK_LIST")
     */
    public function listTask(TaskRepository $taskRepository, Security  $security)
    {...}
````
The annotation @IsGranted("TASK_LIST"), call the TaskVoter (in __src/Security/Voter/TaskVoter.php__).
This one check if the attribute is configure with the "protected function supports" and check if user can continue with the "protected function voteOnAttribute".

For configure new access control check [documentation](https://symfony.com/doc/current/security/voters.html).
Be careful if you want denied access to an entity in particular, you must inject a subject in your annotation and configure it in the Voter.

Authentication and Users
========================

In this application, Authentication is managed with a login form and the Security Component of Symfony.

Users are stored in the database in a table __user__.

When a user want to login, he will send his email and password [login form](https://github.com/Alexisleveque-OC/P8_leveque_alexis/blob/master/templates/security/login.html.twig)
and his informations will be passed to the Guard ([LoginFormAuthenticator](https://github.com/Alexisleveque-OC/P8_leveque_alexis/blob/master/src/Security/LoginFormAuthenticator.php)).
This one check the credentials (informations send with login form) and return true if all is ok.
It's possible to configure error message or differents credentials if you want in __src/Security/LoginFormAuthenticator.php__.

The configuration is in the file __config/packages/security.yaml__ :

```yaml
security:
    encoders:
        App\Entity\User:
            algorithm: bcrypt
    # https://symfony.com/doc/current/security.html#where-do-users-come-from-user-providers
    providers:
        users_in_memory:
            entity:
                class: App\Entity\User
                property: email
    firewalls:
        dev:
            pattern: ^/(_(profiler|wdt)|css|images|js)/
            security: false
        main:
            anonymous: true
            lazy: true
            provider: users_in_memory
            guard:
                authenticators:
                    - App\Security\LoginFormAuthenticator
            logout:
                path: logout
                target: homepage

```

__Encoders__ : that lines tell to Symfony to use the Bcrypt algorithm to hash passwords.

__Providers__ : Those lines tell to Symfony where to look for the user informations. Here it will be with the Class User and with the email attribute.

__FireWalls__ : Here you will create some rules to tell how symfony will react in different environnements.
The line provider, indicatee to symfony which provider he must use.
The guard -> authenticators is the file that check the credentials.
Line logout define the route for logout and redirect user.
Here, check the lines under the main firewall, specifically in form_login.

Now with this configuration, the Security Component can take the form informations, check if those informations are correct in database and if a user is existing.
If all that is done, User informations are stored in Sessions and the User is authenticated.