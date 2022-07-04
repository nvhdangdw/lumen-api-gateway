# Docker
## Services
* nginx
* api-gateway `php-fpm:7.2.*`
* opencart `php-fpm:7.1.*`
* mysql
* redis
* rabbitmq
* adminer `web GUI`
* redis-commander `web GUI`
* mailhog `servcie and web GUI`
## Run
```
docker compose up
```
## Rebuild and run
```
docker compose up --build
```
## Fix permission in `**/storage/*`
```
docker compose exec app bash
rm -rf {ApiGateway,OrdersService,ProductsApi,QRBenefitService}/storage/logs/*
chmod 775 -R {ApiGateway,OrdersService,ProductsApi,QRBenefitService}/storage/logs
```
# Document
## Workflow
### 1. Structure
![](https://cdn.shortpixel.ai/spai/w_800+q_lossy+ret_img+to_webp/https://ftxinfotech.com/wp-content/uploads/2020/03/microservice.png)

`Microservices are used when an application`
* Is developing from the scratch
* Is a monolith application
* Is rebuilding (or refactoring)
* Is integrated with new functionality
* Scaling is a problem
* Productivity is low
* Is difficult to maintain

### 2. Authentication
![](https://images.ctfassets.net/23aumh6u8s0i/5rxhsrATv7IED2mfbfVdHw/5a361594f6c17e231577be5853308e9a/auth0-flow)

## Database
### 1. <a href="https://www.theserverside.com/answer/Synchronous-vs-asynchronous-microservices-communication-patterns#:~:text=There%20are%20three%20ways%20to,hybrid%2C%20which%20supports%20both">Synchronous vs. asynchronous microservices communication patterns</a>
![](https://cdn.ttgtmedia.com/rms/onlineimages/serverside-synchronous_inter_service_communication-f.png)
![](https://cdn.ttgtmedia.com/rms/onlineimages/serverside-asynchronous_inter_service_communication-f.png)
