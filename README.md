# Saloodo Technical Challenge for Backend Software Engineer

Build a set of REST interfaces (no visual interfaces are needed) that allow us to do the following:
* Manage a list of products that have prices.
* Enable the administrator to set concrete prices (such as 10EUR) and discounts to prices either by a concrete amount (-1 EUR) or by percentage (-10%).
* Enable the administrator to group products together to form bundles that have independent prices.
* Enable customers to get the list of products and respective prices.
* Enable customers to place an order for one or more products, and provide customers with the list of products and the total price.

# Table of contents

* [Solution](#solution)
* [Documentation](#documentation)

## Solution

* [Technology](#technology)
* [Endpoints](#endpoints)
* [Design Decisions and Implementation Details](#design-decisions-and-implementation-details)
* [Possible improvements](#possible-improvements)

### Technology

I have written the challenge using Symfony 4 with the following bundles:

* SensioFrameworkExtraBundle
* FOSRestBundle
* JMSSerializerBundle
* DoctrineBundle
* DoctrineMigrationsBundle
* hautelook/AliceBundle
* NelmioAliceBundle
* NelmioApiDocBundle

### Endpoints 

Endpoints that satisfies the requirements. Full documentation [here](#documentation).

#### Manage a list of products that have prices.

`GET|POST|PATCH|DELETE /products|/products/{id}`

#### Enable the administrator to set concrete prices (such as 10EUR) and discounts to prices either by a concrete amount (-1 EUR) or by percentage (-10%).

`PATCH /products/{id}/price`

#### Enable the administrator to group products together to form bundles that have independent prices.

`GET|POST|PATCH|DELETE /bundles|/bundles/{id}|/bundles/{id}/price|/bundles/{id}/products`

#### Enable customers to get the list of products and respective prices.

`GET /products|/products/{id}`

#### Enable customers to place an order for one or more products, and provide customers with the list of products and the total price.

`GET|POST /orders/{id}|/orders`

### Design Decisions and Implementation Details

#### Prices and Discounts formats

Final prices are calculated and stored, having a discount means that the final price should be calculated anyway, plus, we should have much less writes(discounts set) than reads.

Several input formats for price and discount are accepted by the API but are exposed as a number with two decimal places, discounts also have a type(PERCENTUAL, CONCRETE), the intent is to give the client flexibility to apply which format it finds more suitable for the task at hand.

#### Price

The price is implemented as a [Doctrine Embeddable](https://www.doctrine-project.org/projects/doctrine-orm/en/2.6/tutorials/embeddables.html).

#### Discount

The discounts are represented by two fields: discountAmount and discountType.

I considered three other approaches for the discount design:
* using a polymorphic Discount where we could have a ConcreteDiscount and a PercentualDiscount
* storing both values in different columns
* having a boolean column 'percentual'

In the end, I choosed this design because I consider it the most clean, simple(compared to polymorphic), extensible and it's more compliant with my previous design decision of giving flexibility to the client to deal with data formatting.

The polymorphic option looks like the best approach, but I thought it was getting too much complex, maybe in a future refactoring.

#### PATCH vs PUT

I decided to implement only PATCH for changes and consider part of the resource as a representation of the changes.

### Possible improvements

* Inventory management
* Admin features to orders
* More customer features
* Better error messaging
* Refactoring `App/Serializer`
* Improve test coverage

## Documentation

You can access a SwaggerUI interface to play with the API at: 
http://www.moisesrosa.com/

* [Usage](#usage)
* [Prices and Discounts format](#prices-and-discounts-format)
* [Security](#security)
* [API](#api)

### Usage

#### API Example

I set up a running API at http://www.moisesrosa.com/

You can also test the API using:
`curl -H "X-AUTH-TOKEN: ADMIN_TOKEN" http://www.moisesrosa.com/customers`

#### Tests

Tests are using _PHPUnit_ and _hautelook/AliceBundle_

##### Functional tests

The functional tests over the API endpoints are located at `tests/Controller`

#### __CAUTION__
__I had to apply this [patch](https://github.com/hautelook/AliceBundle/pull/441) in order to make the trait works with WebTestCase, the composer repository version still doesn't have this patch, so I had to apply it directly to the code.__

### Prices and Discounts format

The following formats are accepted as price and discount amount:

* Integer. Ex: 10, 999, 500
* Decimal. Ex: 5.5, 4.56
* With Currency. Ex: 10EUR, 5.5EUR - _only EUR is supported by now_
* **For discounts only**: with a negative sign. Ex: -10EUR, -10, -5.5

### Security

This project uses Symfony Security for basic token authentication.

The requests should contains a X-AUTH-TOKEN header.

As this is a challenge project, it comes with two tokens and it's not possibile to create new tokens for now.

All endpoints needs a token. The API has endpoints that are exclusive for ADMINs and CUSTOMERs roles, the ADMIN token has both roles and should be able to access all endpoints.

You should use one of this two tokens:

#### ADMINs

For ADMINs restricted endpoints.

`X-AUTH-TOKEN: ADMIN_TOKEN`

#### CUSTOMERs

For CUSTOMERs restricted endpoints.

`X-AUTH-TOKEN: CUSTOMER_TOKEN`

### API

#### _Customer_

##### `GET /customers`
*ADMIN role only*

List all customers.

##### `GET /customers/{id}`
*ADMIN role only*

Retrieve one customer by ID.

##### `DELETE /customers/{id}`
*ADMIN role only*

Deletes one customer by ID.

##### `POST /customers`
*ADMIN role only*

Create one customer.

Body data format
```json
{
  "name": "Customer"
}
```

##### `PATCH /customers/{id}`
*ADMIN role only*

Updates one customer by ID.

Body data format
```json
{
  "name": "Customer New Name"
}
```

#### _Product_

##### `GET /products`
*CUSTOMER role*

List all products.

##### `GET /products/{id}`
*CUSTOMER role*

Retrieve one product by ID.

##### `DELETE /products/{id}`
*ADMIN role only*

Deletes one product by ID.

##### `POST /products`
*ADMIN role only*

Create one product.

Body data format
```json
{
  "name": "Product",
  "price": "5.50",
  "discount": "10%"
}
```

##### `PATCH /products/{id}`
*ADMIN role only*

Updates one product by ID.

Body data format
```json
{
  "price": "5EUR"
}
```

##### `PATCH /products/{id}/price`
*ADMIN role only*

Updates product's price or discount by ID.

Body data format
```json
{
  "amount": "5",
  "discount": "10%"
}
```

#### _Bundle_

##### `GET /bundles`
*CUSTOMER role*

List all bundles.

##### `GET /bundles/{id}`
*CUSTOMER role*

Retrieve one bundle by ID.

##### `DELETE /bundles/{id}`
*ADMIN role only*

Deletes one bundle by ID.

##### `POST /bundles`
*ADMIN role only*

Create one bundle.

The products list should contains only integers representing valid product's IDs.

Body data format
```json
{
  "name": "Bundle",
  "price": "20",
  "discount": "5%",
  "products": [
    1, 2
  ]
}
```

##### `PATCH /bundles/{id}`
*ADMIN role only*

Updates one bundle by ID.

Body data format
```json
{
  "name": "New Bundle Name"
}
```

##### `PATCH /bundles/{id}/price`
*ADMIN role only*

Updates bundle's price or discount by ID.

Body data format
```json
{
  "amount": "5",
  "discount": "10%"
}
```

##### `POST /bundles/{id}/products`
*ADMIN role only*

Add products to bundle by ID.

The products list should contains only integers representing valid product's IDs.

Body data format
```json
{
  "products": [1, 2]
}
```

##### `DELETE /bundles/{id}/products`
*ADMIN role only*

Deletes products from bundle by ID.

The products list should contains only integers representing valid product's IDs.

Body data format
```json
{
  "products": [1, 2]
}
```
#### _Order_

##### `GET /orders/{id}`
*CUSTOMER role*

Retrieve one order by ID.

##### `POST /orders`
*CUSTOMER role*

Create one order.

The customer field should by an integer representing a valid customer ID.

The items list should contains only integers representing valid product's or bundle's IDs.

Body data format
```json
{
  "customer": 1,
  "items": [
    4, 5
  ]
}
```
