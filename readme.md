# Gittwork

A collection of methods to improve a Teamwork Projects + Github workflow. Aimed at software developers using both [Teamwork](https://www.teamwork.com) + [Github](https://github.com/). Designed to close the gap between Tasks and Code. 

## Installation

1. Clone the repository
2. Copy the file called .env.example to .env and set your own TEAMWORK_URL and TEAMWORK_APIKEY

## Current Features

1. Automatically update new Tasks on Teamwork to add the Task ID to its description with additional information on how to send Commit data to the Task.


### Automatically add a Task ID and other information to the description of a new Task

Create a TASK.CREATED webhook in your Teamwork Projects account and point it to: https://your_deployment_location/gittwork/public/webhooks.php

This will update the task description to include:

```Include "[12345678]" or "[Finish(ed) 12345678]" to update this task when making a commit. Record time spent on the task by using: "[12345678:30]"```
