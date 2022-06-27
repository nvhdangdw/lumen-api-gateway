token_github: 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
expired: 90 days/22-06-22

# Clone
Https
```
https://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@github.com/khanhtonptn/MBA__docker-lumen__API-Gateway.git
```
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
