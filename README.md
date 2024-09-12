
## Installation
  ```bash
  git clone https://github.com/Daniel-ius/e-shop.git
  cd e-shop
  ```
  Docker
  ```bash
  docker-compose up -d
  ```
  ```bash 
  docker-compose exec app composer install
  ```
  ```bash
  docker-compose exec app php bin/console doctrine:migrations:migrate --no-interaction
  docker-compose exec app php bin/console doctrine:fixtures:load --no-interaction 
  ```
  Symfony cli
  ```bash 
  composer install
  ```
  ```bash
  php bin/console doctrine:migrations:migrate --no-interaction
  php bin/console doctrine:fixtures:load --no-interaction 
  ```

## Category Controller Routes
    List Categories
        Route: /api/v1/categories
        Method: GET
        Description: Fetches and returns a list of all categories.

    Show Category
        Route: /api/v1/categories/{id}
        Method: GET
        Description: Fetches and returns a specific category by its ID.

    Create Category
        Route: /api/v1/categories/create
        Method: GET, POST
        Description: Creates a new category with the provided data.

    Edit Category
        Route: /api/v1/categories/{id}/edit
        Method: PUT
        Description: Edits an existing category with the provided data.

    Delete Category
        Route: /api/v1/categories/{id}
        Method: DELETE
        Description: Deletes a category by its ID.
## Product Controller Routes
    List Products
        Route: /api/v1/products
        Method: GET
        Description: Fetches and returns a list of all products.

    Show Product
        Route: /api/v1/products/{id}
        Method: GET
        Description: Fetches and returns a specific product by its ID.

    Create/Edit Product
        Route: /api/v1/products/{id}/edit, /api/v1/products/create
        Method: GET, POST, PUT
        Description: Creates a new product or edits an existing product with the provided data.

    Delete Product
        Route: /api/v1/products/{id}
        Method: DELETE
        Description: Deletes a product by its ID.

## Cart Controller Routes
    Add to Cart
        Route: /api/v1/cart/add
        Method: POST
        Description: Adds a product to the user's cart.

    Get Cart
        Route: /api/v1/cart/get
        Method: GET
        Description: Fetches and returns the current user's cart.

    Get Order History
        Route: /api/v1/cart/orderHistory
        Method: GET
        Description: Fetches and returns the order history for the current user.

    Checkout
        Route: /api/v1/cart/checkout
        Method: POST
        Description: Checks out the current user's cart.

## User Related Routes
    User Registration
        Route: /api/v1/registration
        Method: POST
        Description: Registers a new user with the provided data.

    Get User Data
        Route: /api/v1/user/data
        Method: GET
        Description: Fetches and returns the current user's data.

    Edit User
        Route: /api/v1/user/edit
        Method: PATCH
        Description: Edits the current user's data.

    Change Password
        Route: /api/v1/user/change/password
        Method: PUT
        Description: Changes the current user's password.


## Usage/Examples
### Example GET Request to List Categories

```bash
curl -X GET https://yourdomain.com/api/v1/categories
```
### Example POST Request to Create a Category

```bash
curl -X POST https://yourdomain.com/api/v1/categories/create \
  -H "Content-Type: application/json" \
  -d '{"name": "New Category"}'
```
### Example PUT Request to Edit a Category

```bash
curl -X PUT https://yourdomain.com/api/v1/categories/1/edit \
  -H "Content-Type: application/json" \
  -d '{"name": "Updated Category"}'
```
### Example DELETE Request to Delete a Category

```bash
curl -X DELETE https://yourdomain.com/api/v1/categories/1
```
### Example GET Request to List Products

```bash
curl -X GET https://yourdomain.com/api/v1/products
```
### Example POST Request to Create a Product

```bash
curl -X POST https://yourdomain.com/api/v1/products/create \
  -H "Content-Type: application/json" \
  -d '{"name": "New Product", "category": "1", "price": 100}'
```
### Example PUT Request to Edit a Product

```bash
curl -X PUT https://yourdomain.com/api/v1/products/1/edit \
  -H "Content-Type: application/json" \
  -d '{"name": "Updated Product", "category": "1", "price": 150}'
 ```
### Example DELETE Request to Delete a Product

```bash
curl -X DELETE https://yourdomain.com/api/v1/products/1
```
### Example POST Request to Add to Cart

```bash
curl -X POST https://yourdomain.com/api/v1/cart/add \
  -H "Content-Type: application/json" \
  -d '{"productId": "1", "quantity": 1}'
```
### Example GET Request to Get Cart

```bash
curl -X GET https://yourdomain.com/api/v1/cart/get
```
### Example GET Request to Get Order History

```bash
curl -X GET https://yourdomain.com/api/v1/cart/orderHistory
```
### Example POST Request to check out

```bash
curl -X POST https://yourdomain.com/api/v1/cart/checkout
```
### Example POST Request to Register a New User


```bash
curl -X POST https://yourdomain.com/api/v1/registration \
  -H "Content-Type: application/json" \
  -d '{"username": "new-user", "password": "password", "email": "user@example.com"}'
```
### Example PATCH Request to Edit User

```bash
curl -X PATCH https://yourdomain.com/api/v1/user/edit \
  -H "Content-Type: application/json" \
  -d '{"firstName": "Updated First Name", "lastName": "Updated Last Name"}'
```
### Example PUT Request to Change Password

```bash
curl -X PUT https://yourdomain.com/api/v1/user/change/password \
  -H "Content-Type: application/json" \
  -d '{"password": "new-password"}'
```
## Authors

- [@Daniel-ius](https://www.github.com/Daniel-ius)

