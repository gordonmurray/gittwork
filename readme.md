## Gittwork

A small collection of methods to enhance Teamwork Projects, built using Lumen.

### Automatically add Task IDs to the description of new Tasks

Create a TASK.CREATED webhook in your Teamwork account and point it to: http(s)://yourdomain.com/receive_teamwork_task.

This will update the task description to include:

```Include "[12345678]" or "[Finish(ed) 12345678]" to update this task when making a commit. Record time spent on the task by using: "[12345678:30]"```