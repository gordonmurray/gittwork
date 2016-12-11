# Gittwork

[![Build Status](https://travis-ci.org/gordonmurray/gittwork.svg?branch=master)](https://travis-ci.org/gordonmurray/gittwork)

Respond to Teamwork Projects Webhooks, to improve a Teamwork Projects + Github workflow. Aimed at software developers using both [Teamwork](https://www.teamwork.com) + [Github](https://github.com) or [Bitbucket](https://bitbucket.org). Designed to close the gap between Tasks and Code. 

## Quick start

1. Clone the repository
2. Copy the file called .env.example to .env and set your own TEAMWORK_URL and TEAMWORK_APIKEY
3. Point one or more webhooks in Teamwork Projects to webhooks.php

## Current Features

1. Automatically add a Task ID and other information to the description of a new Task

### Automatically add a Task ID and other information to the description of a new Task

Create a TASK.CREATED webhook in your Teamwork Projects account and point it to: https://your_deployment_location/gittwork/public/webhooks.php

This will update the task description to include:

```Include "[12345678]" or "[Finish(ed) 12345678]" to update this Task when making a commit. Record time spent on the task by using: "[12345678:30]"```
