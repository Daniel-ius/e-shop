# Routes
## CategoryController

### The CategoryController provides the following routes:

    List Categories
        Route: /categories
        Method: GET
        Description: Fetches and returns a list of all categories.

    Show Category
        Route: /categories/{id}
        Method: GET
        Description: Fetches and returns a specific category by its ID.

    Create Category
        Route: /categories/create
        Method: GET, POST
        Description: Creates a new category with the provided data.

    Edit Category
        Route: /categories/{id}/edit
        Method: PUT
        Description: Edits an existing category with the provided data.

    Delete Category
        Route: /categories/{id}
        Method: DELETE
        Description: Deletes a category by its ID.

## ProductController

### The ProductController provides the following routes:

    List Products
        Route: /products
        Method: GET
        Description: Fetches and returns a list of all products.

    Show Product
        Route: /products/{id}
        Method: GET
        Description: Fetches and returns a specific product by its ID.

    Create/Edit Product
        Route: /products/{id}/edit, /products/create
        Method: GET, POST, PUT
        Description: Creates a new product or edits an existing product with the provided data.

    Delete Product
        Route: /products/{id}
        Method: DELETE
        Description: Deletes a product by its ID.

## CartController

### The CartController provides the following routes:

    Add to Cart
        Route: /cart/add
        Method: POST
        Description: Adds a product to the user's cart.

    Get Cart
        Route: /cart/get
        Method: GET
        Description: Fetches and returns the current user's cart.

    Get Order History
        Route: /cart/orderHistory
        Method: GET
        Description: Fetches and returns the order history for the current user.

    Checkout
        Route: /cart/checkout
        Method: POST
        Description: Checks out the current user's cart.

## RegistrationController

### The RegistrationController provides the following route:

    User Registration
        Route: /registration
        Method: POST
        Description: Registers a new user with the provided data.

## UserController

### The UserController provides the following routes:

    Get User Data
        Route: /user/data
        Method: GET
        Description: Fetches and returns the current user's data.

    Edit User
        Route: /user/edit
        Method: PATCH
        Description: Edits the current user's data.

    Change Password
        Route: /user/change/password
        Method: PUT
        Description: Changes the current user's password.

# Usage
## Categories
### List Categories
#### To list all categories, make a GET request to /categories. The response will be a JSON object containing the list of categories.

### Show Category
#### To show a specific category, make a GET request to /categories/{id}, replacing {id} with the category's ID. The response will be a JSON object containing the category data.

### Create Category
#### To create a new category, make a POST request to /categories/create with the category data in the request body. The request body should be in JSON format:


`{
  "name": "Category Name"
}`

### Edit Category
#### To edit an existing category, make a PUT request to /categories/{id}/edit with the updated category data in the request body:

`{
  "name": "Updated Category Name"
}`

## Delete Category

#### To delete a category, make a DELETE request to /categories/{id}, replacing {id} with the category's ID. The response will confirm the deletion.
## Products
### List Products

#### To list all products, make a GET request to /products. The response will be a JSON object containing the list of products.
### Show Product

#### To show a specific product, make a GET request to /products/{id}, replacing {id} with the product's ID. The response will be a JSON object containing the product data.
### Create/Edit Product

#### To create a new product or edit an existing product, make a POST or PUT request to /products/create or /products/{id}/edit with the product data in the request body. The request body should be in JSON format:

`{
  "name": "Product Name",
  "category": "Category ID",
  "price": 100
}
`
## Delete Product

#### To delete a product, make a DELETE request to /products/{id}, replacing {id} with the product's ID. The response will confirm the deletion. 

## Cart
### Add to Cart

#### To add a product to the cart, make a POST request to /cart/add with the product data in the request body. The request body should be in JSON format:

`{
"productId": "Product ID",
"quantity": 1
}`

### Get Cart
#### To get the current user's cart, make a GET request to /cart/get. The response will be a JSON object containing the cart data.
### Get Order History
#### To get the current user's order history, make a GET request to /cart/orderHistory. The response will be a JSON object containing the order history data.
### Checkout
#### To check out the current user's cart, make a POST request to /cart/checkout. The response will confirm the checkout process.
## User Registration
#### To register a new user, make a POST request to /registration with the user data in the request body. The request body should be in JSON format:


```json
{
"username": "admin",
"roles": [ "ROLE_USER", "ROLE_ADMIN" ],
"password": "admin",
"firstName": "John",
"lastName": "Doe",
"email": "john.doe@example.com",
"phoneNumber": "123-456-7890",
"street": "123 Main St",
"city": "Any-town",
"zipCode": "12345"
}
```

## User Management
### Get User Data

#### To get the current user's data, make a GET request to /user/data. The response will be a JSON object containing the user's data.
### Edit User

#### To edit the current user's data, make a PATCH request to /user/edit with the updated user data in the request body. The request body should be in JSON format:

```json
{
  "firstName": "Updated First Name"
}
```

## Change Password

#### To change the current user's password, make a PUT request to /user/change/password with the new password in the request body. The request body should be in JSON format:

```json
{
  "password": "New Password"
}
```

## Error Handling
#### The controllers handle various errors such as JSON parsing errors and validation errors. Errors are returned as JSON responses with a success flag set to false and an errors key containing the error messages.

# Example Requests
## Example GET Request to List Categories

```bash
curl -X GET https://yourdomain.com/categories
```
## Example POST Request to Create a Category

```bash
curl -X POST https://yourdomain.com/categories/create \
  -H "Content-Type: application/json" \
  -d '{"name": "New Category"}'
```
## Example PUT Request to Edit a Category

```bash
curl -X PUT https://yourdomain.com/categories/1/edit \
  -H "Content-Type: application/json" \
  -d '{"name": "Updated Category"}'
```
## Example DELETE Request to Delete a Category

```bash
curl -X DELETE https://yourdomain.com/categories/1
```
## Example GET Request to List Products

```bash
curl -X GET https://yourdomain.com/products
```
## Example POST Request to Create a Product

```bash
curl -X POST https://yourdomain.com/products/create \
  -H "Content-Type: application/json" \
  -d '{"name": "New Product", "category": "1", "price": 100}'
```
## Example PUT Request to Edit a Product

```bash
curl -X PUT https://yourdomain.com/products/1/edit \
  -H "Content-Type: application/json" \
  -d '{"name": "Updated Product", "category": "1", "price": 150}'
 ```
## Example DELETE Request to Delete a Product

```bash
curl -X DELETE https://yourdomain.com/products/1
```
## Example POST Request to Add to Cart

```bash
curl -X POST https://yourdomain.com/cart/add \
  -H "Content-Type: application/json" \
  -d '{"productId": "1", "quantity": 1}'
```
## Example GET Request to Get Cart

```bash
curl -X GET https://yourdomain.com/cart/get
```
## Example GET Request to Get Order History

```bash
curl -X GET https://yourdomain.com/cart/orderHistory
```
## Example POST Request to check out

```bash
curl -X POST https://yourdomain.com/cart/checkout
```
## Example POST Request to Register a New User


```bash
curl -X POST https://yourdomain.com/registration \
  -H "Content-Type: application/json" \
  -d '{"username": "new-user", "password": "password", "email": "user@example.com"}'
```
## Example PATCH Request to Edit User

```bash
curl -X PATCH https://yourdomain.com/user/edit \
  -H "Content-Type: application/json" \
  -d '{"firstName": "Updated First Name", "lastName": "Updated Last Name"}'
```
## Example PUT Request to Change Password

```bash
curl -X PUT https://yourdomain.com/user/change/password \
  -H "Content-Type: application/json" \
  -d '{"password": "new-password"}'
```