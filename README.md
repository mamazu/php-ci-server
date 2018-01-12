# php-ci-server
A ci server based on php

## Usage
In order to use the bot there are two steps required.

1. Set up the webhook for the repository:
Go to the repository's settings and under Webhooks. Set the payload url to the ngrok URL, the content type to `application/x-www-form-urlencoded` and the secret key to something secret.

2. Set up the configuration for the CI.
Create a `config.json` file with the follwing contents:
```json
{
  "whitelisted_repos" : [
    // Put your repos that you want to allow here with the full repo name
    "mamazu/php-ci-server"
  ],
  "secret_key": "SECRET_KEY"
}
```
The secret key has to be the same you have entered in GitHub.