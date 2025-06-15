# Laravel + MySQL + PhpMyAdmin Docker Setup

## 🔧 Services Overview

- **Laravel App URL:**  
  [http://localhost:8001](http://localhost:8001)

- **PhpMyAdmin URL:**  
  [http://localhost:8083](http://localhost:8083)
  - **Username:** `root`
  - **Password:** `root`

---

## ▶️ Starting the Docker Environment

To start all services (pull latest images):

```bash
./start_docker.sh pull
```

###  migrations, and start the application:
```
./start.sh
```
  

### To fetch and import 100 random users:

```bash
docker-compose exec app php artisan import:customers 100

```


### Available Endpoints

**Endpoint:**  
`GET /customers`

```bash
 http://localhost:8001/customers
```

`GET /customers/{customerId}`

```bash
 http://localhost:8001/customers/{id}
```

### run test

```bash
./run-test.sh all
```
