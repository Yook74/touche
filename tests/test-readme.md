# Installing Codeception and Executing Tests

## Install [Codeception](https://codeception.com/docs/)
You will need to have Codeception installed to run the tests. 
To determine if you have Codeception installed already, go to the root of the repository and run `./vendor/bin/codecept -v`.
If you get a version number and usage information, can skip this section.
If not, follow the instructions to install Composer and Codeception.

### [Composer](https://getcomposer.org/) 
Composer is the package/dependency manager for PHP. 
We will be using it to install Codeception, our testing framework. 
First we may need to install composer though.
On Ubuntu, that's `$ sudo apt-get install composer`

### [Codeception](https://codeception.com/docs/)
Now that you have composer, go to the root of the repository and type `$ composer install`.
This will reference the `composer.lock` and `composer.json` files in the root of the repository and install codeception.

### Drivers
You may also need to install other apt packages which serve as drivers. 
I needed to install `php-mysql`.

## Configure Acceptance Tests

### Background
All Codeception tests fall under one of three categories: acceptance, unit, and functional. 
The tests that have been created at the time I wrote this document are all acceptance tests.
Acceptance tests consist of a series of interactions with the website and a series of expectations for the state of the website.
For example, a test will enter a user's credentials and click the "Log In" button and expect to be taken to a homepage.
If the expectations are not met, the test fails.

### Acceptance configuration
The acceptance tests have a configuration file which needs to be tweaked.
Open up `tests/acceptance.suite.yml` and fill out the `url` and `password` lines.
The `url` line should be set to the contest creation page for your installation of `touche`.
If you are using the `touche` server, you can replace "USERNAME-HERE" with your username.
The `password` line should be the root MySQL password.
If you saw that the `dsn` line has the word "placeholder" in it, that line is correct; do not change this line

## Execute Acceptance Tests 
### [PhantomJS](http://phantomjs.org/)
PhantomJS is a headless browser, which means it has no GUI. 
Codeception is configured to use this browser to interact with the `touche` software, but you need to install it and run it so Codeception can use it.
You can install PhantomJS with apt or by following the directions on their website.

Before you run any tests, you need to execute this command: `$ phantomjs --webdriver=4444`.
You can either do this in a separate terminal or use the `&` operator to make it run in the background.

### Run tests
The command `$ ./vendor/bin/codecept run tests/acceptance/create_contest/` runs the contest creation tests.
These tests create a contest and make sure that it has been set up properly.
All of the other acceptance tests depend on the creation tests, so you have to run the creation tests before running any other tests.
This is kind of a sin, but creating the contest takes a long time, and I didn't want to incur that time penalty for every test suite.
Other than that the tests are independent. 
You can run them with the same syntax as the earlier command; just specify a different folder.
At the end of your test session, run `$ ./vendor/bin/codecept run tests/acceptance/delete_contest/`.
