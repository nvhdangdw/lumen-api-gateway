# Docker
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
chmod 775 -R {ApiGateway,OrdersService,ProductsApi}/storage/logs
```
