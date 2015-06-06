# Gittwork

A small collection of methods to enhance Teamwork Projects, used internally at [Murrion Software](http://murrion.com), aimed at software developers using [Teamwork](https://www.teamwork.com/refer/murrion) + [Bitbucket](https://bitbucket.org/). Built using [Lumen](http://lumen.laravel.com/), a PHP micro framework from the maker of Laravel.

## Installation

1. Clone the repository to a web accessible location, such as https://domain.com/
2. Add your Teamwork API key to /app/Http/Controllers/TeamworkController.php

## Current Features

1. Automatically update new Tasks on Teamwork to add the Task ID to its description with additional information on how to send Commit data to the Task.

## Planned Features

1. Receive Commit details from Bitbucket and update the relevant Task on Teamwork with commit details.
2. Tag the Task as ready for testing in Teamwork based on the Commit.
3. Create a Time sheet entry automatically upon Commit
4. Receive details of a Fatal error in a PHP based web application and automatically create a new Task on Teamwork
5. Receive details of a Fatal error in mySQL and automatically create a new Task on Teamwork

### Automatically add a Task ID and other information to the description of a new Task

Create a TASK.CREATED webhook in your Teamwork account and point it to: http(s)://yourdomain.com/receive_teamwork_task.

This will update the task description to include:

```Include "[12345678]" or "[Finish(ed) 12345678]" to update this task when making a commit. Record time spent on the task by using: "[12345678:30]"```