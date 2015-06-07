# Gittwork

A small collection of methods to enhance Teamwork Projects, used internally at [Murrion Software](http://murrion.com), aimed at software developers using [Teamwork](https://www.teamwork.com/refer/murrion) + [Bitbucket](https://bitbucket.org/). Built using [Lumen](http://lumen.laravel.com/), a PHP micro framework from the maker of Laravel.

```
Please note: This project is still in active development and will change a lot
```

## Installation

1. Clone the repository to a web accessible location, such as https://domain.com/
2. Copy the file called .env.example to .env and set your own TEAMWORK_API_KEY and TEAMWORK_CUSTOM_URL

## Current Features

1. Automatically update new Tasks on Teamwork to add the Task ID to its description with additional information on how to send Commit data to the Task.
2. Receive Commit details from Bitbucket and update the relevant Task(s) on Teamwork with details from the Commit.

## Planned Features

1. Tag the Task as ready for testing in Teamwork based on the Commit.
2. Create a Time sheet entry automatically upon Commit
3. Receive details of a Fatal error in a PHP based web application and automatically create a new Task on Teamwork
4. Receive details of an error in mySQL and automatically create a new Task on Teamwork

### Automatically add a Task ID and other information to the description of a new Task

Create a TASK.CREATED webhook in your Teamwork account and point it to: http(s)://yourdomain.com/receive_teamwork_task

This will update the task description to include:

```Include "[12345678]" or "[Finish(ed) 12345678]" to update this task when making a commit. Record time spent on the task by using: "[12345678:30]"```

### Automatically add a comment to a Task mentioned in a Bitbucket commit message

Create a POST commit web hook on Bitbucket and point it to: http(s)://yourdomain.com/receive_bitbucket_commit

If a Teamwork Task ID is mentioned in the commit message then the relevant Task will be updated with a new Comment which shows the commit message, any files changed as well as a link to the changeset on Bitbucket, for example:


> added some more things to somefile.text
> 
> files changed
> modified somefile.text
> 
> branch
> master
> 
> view the commit on bitbucket
> https://bitbucket.org/xxxxx/project-x/commits/xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
>
