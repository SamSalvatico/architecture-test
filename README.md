# TourRadar

* [Questions](#questions)
* [Startup](#startup)
* [Implementation](#implementation)
* [Production TODO](#production-todo)

## Questions

**What problems do you identify in the current setup? Please enumerate them with a brief description of why you believe they are problems, and what risks they carry.**

1. Writing on *TourFiles* storage as JSON file for all importers lacks of possibility to next query data, so we always need to process all the data in the new JSON file;
2. Queue polling could cause a latency between writing / reading;
3. Single TourProcessor instance is not able to process JSON files in parallel;
4. It is not specified, but, basing on the way we are logging data, we could encounter problems about readibility if we have so much importers and, consequently, many log files.
5. This one does not depend only on TourRadar choice, but the *pull* pattern to get data from operators is not the best one, there are an high possibility to get responses that don't contain new data since the last request.

**What new architecture would you suggest that optimizes for
performance, scalability, and reliability?**

To start, I would suggest to insert a message broker like RabbitMq in the architecture to improve ways services communicate between each other.
After that, we could choose to use JSON or a format like Protobuf as payload for our messages.

If there is possibility to choose the way the data are imported we can opt for a *push* pattern, so the operators could invoke a REST endpoint, possibly balanced thanks to a load balancer like HAProxy, or we can subscribe to a PubSub topic, sending updated data to optimize imports. Then the messages will be written on a queue and asynchronously managed. We need to pay attention to the order of the imported data.

I would suggest to change the way importers write processed data. We could choose between two ways:
- if processed data from operator are *small* (we must make tests to decide what *small* means) we could send each imported tour as a new message to our message broker. On the other side, we can have one or more processes that listen for new messages and spawn new processes to import data in our db. Pros: high scalability. Cons: high throughput, if we want to historicize data got from operators, we need to add a storage (db/filesystem);
- we can use a non-relational database like MongoDb as an intermediate storage to make our data queryable. In this way, if data from operator are not incremental, we can avoid to reprocess already imported data. After that, we can send a message to a queue.  On the other side, we can have one or more processes that listen for new messages and spawn new processes that read tours from the non-relational db, set them as imported and persist them. Pro: we can historicize data and query for specific documents. Cons: we must implement both database data management and message broker communication.

Both solutions could be implemented with high-availability standards.

Choosing previous solutions we can have more than one TourProcessor instance, and, paying attention to race conditions in website db, we can persist data in parallel.

If possible, we could also use a logging system that make us able to aggregate data and make importers improve the logging-related latency. One solution could be to make processes write on stdin/stderr channels and use a tool like FluentD that, if we are in a containerized environment, reads from channels of our containers, then send data to Logstash instance(s) to make us able to log to an Elasticsearch cluster. Then we can aggregate and query our data using Kibana.  

**How would you ensure your chosen architecture is working as expected?**


We have to, first of all, to decide metrics to be able to understand which is happening: how many files are we importing? How many messages the message broker could manage without be overwhelmed?

Unit testing, behavioural testing, integration testing. Tests, tests, tests.

We can use tools like Prometheus to store events about our architecture, use its alert manager to send notifications about critical issues and make dashboards using Grafana to understand what's happening and what's happened.

Then, I would suggest to make some stress test like adding many importers with tons of data to check if our system is correctly scaling.

**For the new architecture you designed in answer to the question above, if
you had to start from scratch, what team do you think you would need to
pull off an MVP in 3 weeks? What would you leave outside this MVP and
how would you prioritize the backlog?**

Suggested team: 
- one developer that implements libraries for make services able to communicate using message broker;
- one or more devops to make architecture ready for minimum needs;
- one developer that makes mocks tour importer operators;
- one or more developers that implements tour processor;
- one team lead that coordinates everybody.

All developers must have a senority that make them able to work with minimum guidance from other developers. This is because in 3 weeks the time we have to understand what we need to do is very poor.

Leave outside:
- monitoring architecture like Prometheus, Grafana, ...;
- low-latency log architecture;
- high availability fine-grained configurations;
- scalability tests;
- fine-grained testing.

Backlog (1 is higher):
1. decide, broadly, TourRadar tour's formats;
2. implement tour processors and importers;
3. broadly implement the architecture adding Helm charts for RabbitMq, MongoDB, ..
4. make services able to communicate between each other;
5. tests for a complete workflow.


** For audit reasons, once the MVP is live, we would like to keep a snapshot of what data was pulled from Operators' APIs. How would you implement that? **

It depends on which method of storage we use
- file system, we can choose cloud storages like S3 with replication in different regions to avoid loss of data;
- non-relational database like MongoDb, we can use dedicated tools for clusters like the following one: https://docs.opsmanager.mongodb.com/current/reference/api/snapshots/.

## Startup

### Project

The project is based on [Laravel 8](https://laravel.com/docs/8.x) and uses PHP8.

### Prerequisites

- [Docker](https://docker.com) installed to run it using Sail.

### Start with Makefile

`cd` into the **architecture-test** root folder.

Running the following command you will start the application thanks to docker compose using the `docker-compose.yml` file you can find in the root folder. 
```bash
$ make install
```
> N.B. The previos command works in a Linux environment, in WSL, or in a Mac Os environment (see the hint in the Makefile if your case is the last one).

Now you're ready to party!

To check if it is working you can invoke `POST http://localhost:8080/api/tour_operators/import` with this body
```json
{
    "operator_id": "first",
    "tours_data": [
        {
            "first_operator_id": "def"
        }
    ]
}
```
If it is working it must send a body like this back to you
```json
{
    "event_id": "ac707ef6cef7222eaae1f7cbc0638c51"
}
```

The application is ready on port 8080 of localhost.

### Tests
The tests run using PHPUnit, PHPcs and PHPstan.

#### With Makefile

The next command will run PHPstan, PHPcs and PHPUnit
```bash
$ make test
```
To simply run phpunit

```bash
$ ./vendor/bin/sail test
```

#### Suites

All the test suites can be found at:
- *tests/Feature* for the feature tests;
- *tests/Unit* for unit tests.


## Implementation

I tried to implement the 'first part' of the flow, from the API invokation from the operator to the transformation to a *TourRadar Tour* object.
In order: 
1. an operator invokes the `POST api/tour_operators/import` endpoint with its operator_id and new tours' content;
2. we generate an `event_id` to trace the request from the operator;
3. understand which operator make the requests and provide a first level of input validation;
4. we enqueue the input to process it asynchronously (currently, for testing purpose, the queue is set as *sync* in .env);
5. we transform the operator tours to our own internal object;
6. we store and send our objects to make other processes able to download related data and persist them definitely. 


### 1 - API
- Added the *app/Http/Controllers/TourOperatorsController.php* controller that implements the *importTours* method. The body is validated by *app/Http/Requests/ProcessTourRequest.php* request, the operator must send its id and a valid array of tours;
- The dependency injection, as declared in *bindCustomRepositories@app/Providers/AppServiceProvider.php*, helps us to implement a *TourOperatorsContract* that is requested from the *TourOperatorsController*'s constructor. The default implementation is *app/Repositories/TourOperators/TourOperatorsDefaultRepository.php*
- Added the `api/tour_operators/import` route management in *routes/api.php*.

### 2 - Event Id
- the *TourOperatorsDefaultRepository* requests a *app/Repositories/ImportManager/ImportManagerContract.php* in constructor. The default one is *app/Repositories/ImportManager/ImportManagerStubRepository.php*. This interface make us able to generate *event ids* in different ways, based on our implementation. The *event id* will be useful to retrieve data about this request;
- in *importTours@TourOperatorsDefaultRepository* we first generate an event id.

### 3 - Operator choice and input validation
- the *TourOperatorsDefaultRepository* requests a *app/Repositories/OperatorToursProcessor/OperatorChooser/OperatorChooserContract.php* in constructor. The default one is *app/Repositories/OperatorToursProcessor/OperatorChooser/SimpleOperatorChooser.php*. This interface make us able to understand which operator sent the request and get the related processor;
- through *app/Repositories/OperatorToursProcessor/OperatourToursProcessorFactory.php* we get the correct processor and make it validate the input data based on single operator references. If the content is not valid we return an error code to the operator and we free the *event id* previously generated.

### 4 - Queue data
- if sent data are valid we enqueue them using an *app/Jobs/ImportTours.php* job and send the event id back to the operator. For development purposes we are currently using a *sync* queue and a single queue pattern. Ideally we could use an asynchronous driver like Redis and dispatch on multiple queues (e.g. one for each operator).

### 5 - Parse tours
- Thank to the *OperatourToursProcessorFactory* we can get the correct operator processor for the input data and transform them to an array of *RadarTour* objects.

### 6 - Store and share data
- Using the *app/Repositories/RadarToursManager/RadarToursManagerContract.php* we can manage our RadarTours. It needs a *app/Repositories/RadarToursManager/StoreRadarTours/StoreRadarToursContract.php* and a *app/Repositories/RadarToursManager/SendRadarTours/SendRadarToursContract.php*. The first will help us to store the Tours that will be then processed and the second one will be helpful to share the updates with other services.

## Production TODO

The most important things to do before we can move the flow in a production environment are:
- manage REST APIs authentication;
- write APIs docs
- choose a queue driver that make us able to asynchronously process multiple events;
- add missing tests;
- improve logging;
- so much more.