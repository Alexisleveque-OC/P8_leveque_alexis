# P8_leveque_alexis

[![Codacy Badge](https://api.codacy.com/project/badge/Grade/b77d75a1a02a499db91df63f61115449)](https://app.codacy.com/gh/Alexisleveque-OC/P8_leveque_alexis?utm_source=github.com&utm_medium=referral&utm_content=Alexisleveque-OC/P8_leveque_alexis&utm_campaign=Badge_Grade)

This is README.md file of my repository fot Project 8 of the application developer PHP/Symfony.

## Story of Project
- Create all issues.
- Make version for Symfony 3 functional and analyse it.
- Upgrade this one for Symfony 5.
- Development of applications.
- Analyse and improvement.
- Write README.
- Presentation of project to a mentor.

## Context
See the project context just here >>> [P8 context](https://openclassrooms.com/fr/paths/59/projects/44/assignment)

## How to install ?

### Step 1 :
- Create a directory in your localserver (Example for Wamp : C:/wamp64/www). And clone project with this link 
[Projet 8](https://github.com/Alexisleveque-OC/P8_leveque_alexis.git) .

### Step 2  :

#### if you don't have composer
- You need to install composer in your workplace. For this, let's go here [composer download](https://getcomposer.org/download/). 
- Download and install it on your computer.
#### if you have composer
- Enter this command in your console for install dependencies
````
composer install
````

### Step 3 : 
- Copy file ".env" to ".env.local" (in directory App) whit your information.

### Step 4 :
- For database and Fixtures, in your terminal enter this command 
````
composer prepare
````
That's all, your Database is create and fixtures are load ! ;) 

### Step 5 :
Run App in your server if you have it or enter this command "symfony server:start"

#### Note 

- If you want to do some tests,copy ".env.local" and paste it with the name ".env.local.test". Enter in console 
````
php bin/phpunit
````
the app will be test and fixtures back in original condition.
