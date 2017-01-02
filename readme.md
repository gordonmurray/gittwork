# Gittwork - close the gap between Tasks and Code.

[![Build Status](https://travis-ci.org/gordonmurray/gittwork.svg?branch=master)](https://travis-ci.org/gordonmurray/gittwork) 

Connect Teamwork Projects, Github or Bitbucket. Use their Webhooks to improve your Teamwork Projects + Git workflow. Aimed at software developers using both [Teamwork Projects](https://www.teamwork.com) + [Github](https://github.com) or [Bitbucket](https://bitbucket.org).  

## Quick start

1. Clone the repository
2. Copy the file called .env.example to .env and set your own TEAMWORK_URL and TEAMWORK_APIKEY
3. Give the /logs folder write permissions
4. Point one or more webhooks in Teamwork Projects to webhooks.php

## Current Features

1. Automatically add a Task ID and other information to the description of a new Task in Teamwork Projects
2. Expose the Project Number of a new Project in Teamwork Projects
3. Add Github Commit messages as Task Comments in Teamwork Projects

### Automatically add a Task ID and other information to the description of a new Task

Create a TASK.CREATED webhook in your Teamwork Projects account and point it to: https://your_deployment_location/gittwork/public/webhooks.php

This will update the task description to include:

```Include "[12345678]" or "[Finish(ed) 12345678]" to update this Task when making a commit. Record time spent on the task by using: "[12345678:30]"```

### Expose the Project Number of a new Project

Create a PROJECT.CREATED webhook in your Teamwork Projects account and point it to: https://your_deployment_location/gittwork/public/webhooks.php

This will update the a new Project title from "New Project" to "[1] New Project"

### Add Github Commit messages as Task Comments in Teamwork Projects

Create a Webhook in your Github repository and point it to : https://your_deployment_location/gittwork/public/github.php

This will receive Github commit data and add the commit message as a Comment to your Task, based on the Task ID used in the commit message.