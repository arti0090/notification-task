# TASK DESCRIPTION

Technical Test: Simple Notification API
Overview
Your task is to create a simple API using PHP and the Symfony framework. This API will manage and send notifications. The goal is not to build a production-ready system. We're interested in seeing your thought process and code structure.

Requirements
The goal of this test is to implement an Email Notification API. A notification has, at a minimum, a recipient email, a subject, and a body.

Project Setup:
Initialize a new Symfony project.
Use a database of your choice (simplicity is clearly OK here).
Use Doctrine for database interactions.

API Endpoints:
Create an endpoint to create a new notification.
Create an endpoint to list all notifications in the database.
Create an endpoint that simulates the sending of a notification. This endpoint should accept an ID and mark the corresponding notification as "sent."

Core Logic:
A notification should have a status (e.g., 'pending', 'sent').
When a new notification is created, its status should be 'pending'.
For the purpose of this test, "sending" a notification does not have to actually send the email, but YOU have to think of what consequences on your application it would mean if you were actually sending it.

Submission and Evaluation
We're not just looking for a working solution; we want to understand how you got there.

Code:
Provide a link to a Git repository containing your solution.

Documentation/Thought Process:
In the README.md also document every choice you've made regarding architecture, decisions, workaround if needed, or anything you find relevant.


Good luck! We look forward to seeing your work.

## STEPS AND THOUGHT PROCESS

1. So the first thing is to set up project, I am using a PHP and SQL database from my machine, but I will leave the docker files
provided from Symfony. I will not focus on them (if I will have time I will check them at the end).

Setup with commands:
```bash
$ symfony new notification-app && cd notification-app
$ composer require symfony/orm-pack symfony/validator symfony/serializer symfony/uid symfony/maker-bundle --dev symfony/test-pack

# running symfony
$ symfony serve
```

Now thinking about the solution, I see there are few points right now: 
- statuses could use the symfony workflow component (state machine).
The states will be declared in one place, it will take care of "guarding" state, and will have events that could be used for later.
As drawback, it might be harder to debug and might be a bit overkill in a small number of states (but maybe in future there might be more states).

- between notification change, I could use event system so in case of create, update etc. I will send event (code would be easier to extend).

- notifications could be sent in async and sync mode. For the async I would use the symfony messenger, 
choice of sending could be done with env variable.

- tests would use phpunit (unit tests, API contract test) and ecs with rector (static analysis) 

```bash
$ composer require symfony/messenger
$ composer require rector/rector --dev
$ composer require symplify/easy-coding-standard --dev
```

2. Creating endpoints, entities and classes

- I almost forgot about adding an Api platform, as we are creating API for those notifications and also some libs for testing

```bash
$ symfony composer require api
$ composer require --dev foundry orm-fixtures
```

- creating functional tests for Notification API—for now high-level tests to make them 'red' and work on them later
  (add some missing vendors for tests)

```bash
$ composer require --dev symfony/browser-kit symfony/http-client
$ composer require --dev justinrainbow/json-schema
$ composer require --dev dama/doctrine-test-bundle
```

- creating entity 'Notification' → I made a recipient email as a string, but this could be an array/a collection of strings cause
  in many email systems you can send them to many ppl (just making it simple :))


3. creating the endpoint for sending notifications

As a way of "sending notification" I used logger as it does not need to be a real send. Btw those could be used in real application,
for easier debugging (so we can say they have double purpose).

At first I thought to make Sender classes to use just NotificationSenderInterface, but later as they have some similarities in
implementation, I used the abstract class for them, from which they extend and for new Sender, there is only a need of implementing
how the "sending" per se should happen (like in sync it directly logs, and in async it sends a message on bus)


## Running project

- change env variables (like env secret if needed, mysql database of your choice, etc.)
- run commands

```bash
$ composer install
$ bin/console doctrine:database:create
$ bin/console doctrine:migrations:migrate
```

- start project

```bash
$ symfony serve
```

Notifications should be logged to a log file (`var/log/notification.log`)

## TIME LOG

- setup, considerations, documenting — 1h
- adding api platform, adding tests, working on custom 'POST' endpoint, fixing and adjusting phpunit - 3h

