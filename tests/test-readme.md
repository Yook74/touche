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

### [ChromeDriver](https://sites.google.com/a/chromium.org/chromedriver/getting-started)
ChromeDriver is a layer between Codeception and Google Chrome. 
It allows Codeception to perform actions in Chrome as if Codeception were a person.
Codeception is configured to use this ChromeDriver (and by extension Chrome) to interact with the `touche` software, 
but you need to install it and run it so Codeception can use it.

First, check to make sure that ChromeDriver isn't already installed with `which chromedriver`.
If it's not you can install ChromeDriver with apt or by following the directions on their website.
Chrome or Chromium must also be installed before you can use ChromeDriver.

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
If you saw that the `dsn` line has the word "placeholder" in it, that line is correct; do not change this line.

## Execute Acceptance Tests 
### ChromeDriver
After installing ChromeDriver but before you run any tests, you need to execute this command: `$ chromedriver --url-base=/wd/hub`.
You can either do this in a separate terminal or use the `&` operator to make it run in the background.

### Weird Things About Chrome
Sometimes there will be a lot of Chrome processes running that slow everything down. 
We're not sure why this happens! 
It could be that Chrome instances aren't killed if tests are killed before completion, but we haven't tested this.

If you run into this problem, you can use the `killall` command.

### Run tests
The command `$ ./vendor/bin/codecept run tests/acceptance/00-create_contest/` runs the contest creation tests.
These tests create a contest and make sure that it has been set up properly.
All of the other acceptance tests depend on the creation tests, so you have to run the creation tests before running any other tests.
This is kind of a sin, but creating the contest takes a long time, and I didn't want to incur that time penalty for every test suite.
Other than that the tests are independent. 
You can run them with the same syntax as the earlier command; just specify a different folder.
At the end of your test session, run `$ ./vendor/bin/codecept run tests/acceptance/02-delete_contest/`.

The numbers in front of the test folders allow you to run `$ ./vendor/bin/codecept run tests/acceptance/` 
and have the tests execute in a correct order.

After a set of tests runs, the results of the test can be reviewed.
By pointing your browser to `src/tests/_output/records.html`, you can view the results of the most recently run set of tests. 
 