# User + Settings

## Requirements

- Have php 7.4 running on your machine locally. 
- Have docker up and running
- Have the symfony cli installed as well.

## Setup Instructions

1\. Install dependencies
```
composer install

```

2\. Turn on Docker

4\. Run docker and wait about 10 seconds for everything to boot up

```
docker-compose up -d
```

3\. Run this sh file
```
sh run-dev.sh
```

## Pages 

Go to [Docs Page](https://127.0.0.1:8000/api/docs) to see the api.  
The home page does not exist.


## Run Tests

You will need sqlite installed on your machine to run the tests.  

```
sh run-test.sh
```

## Notes 

- All responses are wrapped in a response envelope with meta data to make it easier for the clients to parse.
- Traits and doctrine lifecyles events are used to add datetimes to entities.
- I used a collection for the user settings.  In a real rest api I would have done a separate endpoint for settings.
- Tests use a sqlite database 
- I would also would have added a global exception handler for recording errors




