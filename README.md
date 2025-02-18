## News Aggregator 

## Docker Setup instruction

* docker-compose up -d --build for build the image and run it in detached mode 
* ensure the images are running by docker-compose ps
1 app (laravel app)
2 db (mysql)
3 phpmyadmin (gui for db)
4 redis (cache driver)
5 nginx (for serving files)

* every artisan command should prefix with docker-compose exec app for reflecting the command inside the container


* open new terminal (type)
- mv .env.example .env
- change DB_HOST=db (docker service name)
- change CACHE_STORE=redis (docker service name)
- docker-compose exec app composer install 
- docker-compose exec app php artisan migrate
- docker-compose exec app php artisan migrate:status (to list migratation status)
- docker-compose exec app php artisan db:seed (seed user - test@example.com,password)
- run docker-compose exec app php artisan app:fetch-news (directly running the command to store it onto db for first time only )
- open new terminal (type)
- run docker-compose exec app php artisan queue:work (for running pending queues)

- for triggering cron for local  -> run docker-compose exec app php artisan schedule:work
this will trigger cron every hour and fetch news and store onto local db

- use postman to login , get news , set preference , get preferred news
with hostname and  prefix api/v1

## open api docs

- visit to  hostname/api/documentation to visit the full gui for the docs
- visit to storage/api-docs.json

## My Implementation

### Docker Implementation
I already have some experience in docker and docker with nodejs but nodejs support server by default for handling file, so server configuration is not a big deal and ! but with PHP we need a file server to serve our file so that ive got some reference on DigitalOcean (https://www.digitalocean.com/community/tutorials/how-to-install-and-set-up-laravel-with-docker-compose-on-ubuntu-22-04) site about how php docker file should be implemented and learnt about how it is implemented after implementing faced issue with no such host! for solve this issue i went through docker github issue page and found out that docker changed their service terms in network and some isp did not resolved the issue yet so that i tried different wifi and boom it worked ! 

I chose redis as cache driver for tags support to removed the caches based on tags ! 

### Laravel 11 
I've used laravel 10 and 7 in my organization and I've no hands on experience in 11, It gave some initial difficulties like adding new middleware and assigning alias is not easy as it used to be ! for example laravel usually returns error in html format but for api we need json and for that we need to have a middleware to either force inject header as application/json or return type as expectjson() and we can alias it and call it in the route service provider for api only but in laravel 11 providers also abstracted away ! I've to implemented the middleware and exception part inside the boostrap app.php
yet it is a nice experience to going through some new that we have experience 


### Api free plan limitations 

After i got this assignment, I initially experiemented news apis to how to implement the result and store it, there i came to know that each and every newsapis returns different response object so that i need to normalize it and also free plan wont extensively supports past data and we cant get more variety of data so that ive implemented the api just from the starting that ! 

### Future enhancement 
For future enhancement i will have to learn more about testing and incorporate test driven development ! now i can only managed to implement test for simple apis ! and also improving newsapis response data with more and past data for the first time to populate the db ! and also find duplicate news with normalized title !