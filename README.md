# Code challenge

## Introduction

This was a code challenge for a job interview. The challenge was to build a simple translation API that would translate
a text from a source language to a target language. The API should be able to translate to different languages and
should be able to use different translation services.

## Assumptions

To simplify the endpoint I decided that the user will need to provide one source text, the source language and 
the target language. The endpoint will return the translation of the source text in the target language if it's 
already stored in the database. If not, the translation will be stored in "queued" status and the translation
will be queued to be translated by an external service.

I also limited the available source and target languages to a reduced list of languages as we don't want to depend
on the external service list-language endpoints to know which languages are available as they are different in each
service. Obviously in a real situation where we want to translate to a language that is not available in some of
the services I would build some kind of translation service balancer, but will talk about it in the "possible improvements"
section.

The external services are defined in the `config/services.yml` file. The services are defined as a list of services,
and they are tried in order until one of them returns a translation. If none of them return a translation, the application
throws an exception.

The Lecto service has a ridiculous limit of requests and it's not possible to register a new account, so I couldn't
work with it much, but it is implemented. I also implemented a DumbTranslator service that just returns the source text.

The queue system is implemented with RabbitMQ and used within the messenger component. The queue is defined in the
`config/packages/messenger.yaml`.

All actions and errors are logged using the Monolog component on stdout and in the `var/log` folder.

I kept the translation table as simple as possible, but in a real situation I would add more things that I will talk about
in the "possible improvements" section.

Logger shows a deprecation warning for which they have opened an issue on Github 13 days ago, but it's not a problem for now.

## Usage

### Requirements

- Docker
- Docker Compose
- Ports 80, 8080, 5672, 15672 and 3306 available
- https://chrome.google.com/webstore/detail/cors-unblock/lfhmikememgdcahcdlaciloancbhjino?hl=en installed on chrome to avoid CORS issues

### Run the application

To run the application you need to run the following command:

```make start```

This will build the docker images and run the application. 
- The API application will be available at http://localhost:8080/api
- The frontend application will be available at http://localhost:80
- The RabbitMQ management interface will be available at http://localhost:15672

### Stop the application

To stop the application you need to run the following command:

```make stop```

### Run the tests

To run the tests you need to run the following command:

```make tests-php```

### Run the translation command

To run the translation command you need to run the following command:

```make translate PARAMS="'Text to translate' 'Source language' 'Target language'"```

Example:

```make translate PARAMS="'Hello world' 'EN' 'ES'"```

If the translation is already stored in the database, it will be returned. If not, it will be queued to be translated, 
just wait a bit and run the command again.

### Other useful commands

This commands only work in Ubuntu based systems.

- Open the frontend in the browser: ```make open-ui```
- Open the API documentation in the browser: ```make open-api```
- Open the RabbitMQ management interface in the browser: ```make open-rabbitmq-manager```

## Possible improvements

### Database

Add a unique index on the `source_text`, `source_language` and `target_language` columns of the `translations` 
table would be the first thing in this simple design but this could be improved by building a slug service that 
would generate a unique slug for each translation and store it in the database. This would allow us to have an index
based only on the slug column.

I would also add `created` and `updated/translated` timestamps to the table, a column to store the external translator service
that provided the translation and a column to store the number of times the translation has been requested. This hits 
column would make it possible to build a cache system that would store the most requested translations in memory by using
redis, elasticache or elastic search.

Also, ideally the database should be a mongodb database as it's a document database and it's better for this kind of
data, but I didn't want to add more complexity to the challenge.

### External services

I would build an external translation service balancer that would take in account different services depending the source
and target language, also based in usage, pricing, etc. This balancer would be a service that would be called by the
`ExternalTranslator` service and would return the translation from the best service in each case.

It would also be a good idea to store in database the available external services with a status and enabled flag. This 
would allow to have a `HealthChecker` service that would check the status of the external services and update the database
so the balancer could take this information into account.

Finally, with the enabled flag, if for whatever reason we need to disable a translation provider we could do it without
having to change the code. Of course adding new providers needs coding and if we want to disable one permanently it 
should be developed, but for operative swiftness it would be a good idea to have this flag.

### Logging

In a real environment I would integrate with a logging service like Datadog and create alerts, sending messages to Slack
in case of errors is also an option or use a notification service like SNS to send emails or SMS or whatever alert policy
is decided by the management.

### Frontend

I made a very simple frontend as I think it's not the main point of this challenge. But for the sake of a good user experience
I would add a loading spinner while the translation is being requested and I would add a "copy to clipboard" button to the result
so the user can copy the translation to the clipboard.

Also, as the new translations are queued it would be interesting to have a Mercure container so we can communicate to
the frontend when the translation is ready and update the translation status in the frontend.
