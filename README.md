# TourRadar

## Questions

**What problems do you identify in the current setup? Please enumerate them with a brief description of why you believe they are problems, and what risks they carry.**

1. Writing on *TourFiles* storage as JSON file for all importers lacks of possibility to next query data, so we always need to process all the data in the new JSON file;
2. Queue polling could cause a latency between writing / reading;
3. Single TourProcessor instance is not able to process JSON files in parallel;
4. It is not specified, but, basing on the way we are logging data, we could encounter problems about readibility if we have so much importers and, consequently, many log files.

**What new architecture would you suggest that optimizes for
performance, scalability, and reliability?**

To start, I would suggest to insert a message broker like RabbitMq in the architecture to improve ways services communicate between each other.
After that, we could choose to use JSON or a format like Protobuf as payload for our messages.

I would suggest to change the way importers write processed data. We could choose between two ways:
- if processed data from operator are *small* (we must make tests to decide what *small* means) we could send each imported tour as a new message to our message broker. On the other side, we can have one or more processes that listen for new messages and spawn new processes to import data in our db. Pros: high scalability. Cons: high throughput, if we want to historicize data got from operators, we need to add a storage (db/filesystem);
- we can use a non-relational database like MongoDb as an intermediate storage to make our data queryable. In this way, if data from operator are not incremental, we can avoid to reprocess already imported data. After that, we can send a message to a queue.  On the other side, we can have one or more processes that listen for new messages and spawn new processes that read tours from the non-relational db, set them as imported and persist them. Pro: we can historicize data and query for specific documents. Cons: we must implement both database data management and message broker communication.

Both solutions could be implemented with high-availability standards.

Choosing previous solutions we can have more than one TourProcessor instance, and, paying attention to race conditions in website db, we can persist data in parallel.

If possible, we could also use a logging system that make us able to aggregate data and make importers improve the logging-related latency. One solution could be to make processes write on stdin/stderr channels and use a tool like FluentD that, if we are in a containerized environment, reads from channels of our containers, then send data to Logstash instance(s) to make us able to log to an Elasticsearch cluster. Then we can aggregate and query our data using Kibana.  