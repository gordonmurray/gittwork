# Gittwork

A small collection of methods to enhance Teamwork Projects. Built using Lumen, a PHP micro framework from the maker of Laravel.

## Installation

Clone the repository to a web accessible location, such as https://domain.com/

## Current Features

* Automatically update new Tasks on Teamwork to add the Task ID to its description with additional information on how to send Commit data to the Task.

## Planned Features

* Receive Commit details from Bitbucket and update the relevant Task on Teamwork with commit details.
* Tag the Task as ready for testing in Teamwork based on the Commit.
* Create a Time sheet entry automatically upon Commit
* Receive details of a Fatal error in a PHP based web application and automatically create a new Task on Teamwork
* Receive details of a Fatal error in mySQL and automatically create a new Task on Teamwork

### Automatically add a Task ID and other information to the description of a new Task

Create a TASK.CREATED webhook in your Teamwork account and point it to: http(s)://yourdomain.com/receive_teamwork_task.

This will update the task description to include:

```Include "[12345678]" or "[Finish(ed) 12345678]" to update this task when making a commit. Record time spent on the task by using: "[12345678:30]"```