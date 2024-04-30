# Objectives
Generate a small blog application using the hexagonal architecture.

# Structure
The project is divided into 3 main packages:
- `domain`: contains the business logic of the application
- `infrastructure`: contains anything related to the dependencies of the application
- `http`: contains the entrypoint of the application

# Application
The application is a small blog application that allows you to create, read, update and delete posts.
The posts have 2 states:
- `Draft`: the post is not published yet
- `Published`: the post is published

There are 3 level of users:
- `author`: can :
  - create new post in Draft mode
  - update and delete posts only if they are in Draft mode
  - request publication of a post
  - delete a post in any state
- `editor`: can: the same as author plus
  - publish a post
  - un-publish a post
- `admin`: can do anything on the posts + administrate users
- any anonymous user can read a post

# Infrastructure
The infrastructure is based on [Symfony](http://symfony.com)

# How to run
The application is dockerized, so you can run it using the following command:
- Launching the containers : ```docker compose up -d```
- Install all composer dependencies : ```composer install```

# How to test
The application is dockerized, so you can run the tests using the following command:
