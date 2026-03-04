# Readme

Contents:

- Description
- Development goals
- Incoming task
- Install
- Frontend form
- Backend structure
- Extension

## Description

Framework-independent API example based on Symfony.

## Development goals

Development goals – to demonstrate how applications can be built without being tied to a specific framework.

## Incoming task

[pdf](./docs/incoming-task.pdf)

## Install

1. Docker install:

    ```bash
    docker compose build
    docker compose up -d
    ```

2. Composer install:

   ```bash
   composer install
   ```

3. Check - [localhost:8000](http://localhost:8000)

## Frontend form

Visit `localhost:8000`and use a form to check the api.

## Backend structure

```sheme
Request               User          Response
                   |        /\
Post Form          |        |       Json object (status & data)
multipart/form-data|        |
                   \/       |
                     Symfony
                   \/       /\             
                Http Controller
                   |        /\     
Calculate command  |        |       View Dto
                   |        |
                   \/       |
                Carrier Service
                   |        /\
Find via VO Slug   |        |       Carrier
                   \/       |
                Carrier Repository
                   |
Use Symfony        |         
config as adata    |
container          |
                   \/   
                  Symfony config
```

## Extension

### Adding a new carrier

1. Implement a new calculate class in the [dir](./src/Application/CarrierService/ShippingCostCalculators/)
2. Add a new row to the `app.carrier_data` parameter in the [config file](./config/packages/parameters.yaml):
    - name
    - slug
    - classname (from #1)

### New Persistence

If you would like to use sql, file storage, or another persistence method, you only need to modify or replace the *repository* to extend the application.

The Carrier service depends on the repository interface.

1. Create a new repository
2. Register it as an alias in the [services.yaml](./config/services.yaml)
