# Saloodo Technical Challenge for Backend Software Engineer

## Backend Developer Test

Build a set of REST interfaces (no visual interfaces are needed) that allow us to do the following:
* Manage a list of products that have prices.
* Enable the administrator to set concrete prices (such as 10EUR) and discounts to prices either by a concrete amount (-1 EUR) or by percentage (-10%).
* Enable the administrator to group products together to form bundles that have independent prices.
* Enable customers to get the list of products and respective prices.
* Enable customers to place an order for one or more products, and provide customers with the list of products and the total price.

## Solution

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

### Design Decicions and Implementation Details

#### Prices and Discounts formats

Final prices are calculated and stored, if a discount exists you should always calculate the final price, plus, we should have much less writes(discounts set) than reads.

Several input formats for price and discount are accepted by the API but are exposed as a number with two decimal places, discounts also have a type(PERCENTUAL, CONCRETE), the intent is to give the client flexibility to apply which format it finds more suitable for the task at hand.

#### Price

The price is implemented as a [Doctrine Embeddable](https://www.doctrine-project.org/projects/doctrine-orm/en/2.6/tutorials/embeddables.html).

#### Discount

The discounts are represented by two fields: discountAmount and discountType.

I considered three other approaches for the discount design:
* using a polymorphic Discount where we could have a ConcreteDiscount and a PercentualDiscount
* storing both values in different columns
* having a boolean column 'percentual'

In the end, I choose this design because I consider it the most clean, simple(compared to polymorphic), extensible and it's more compliant with my previous design decision of giving flexibility to the client to deal with data formatting.

The polymorphic option looks like the best approach, but I thought it was getting too much complex, maybe in a future refactoring.

#### PATCH vs PUT

I decided to implement only PATCH for changes and consider part of the resource as a representation of the changes.

## Documentation

You can access a live API documentation along with a SwaggerUI interface at: 
http://www.moisesrosa.com/

### Usage

#### API Example

I set up a running API at http://www.moisesrosa.com/

You can test the API using:
`curl -H "X-AUTH-TOKEN: ADMIN_TOKEN" http://www.moisesrosa.com/customers`

#### Tests

Tests are using _PHPUnit_ and _hautelook/AliceBundle_

##### Functional tests

The functional tests over the API endpoints are located at `tests/Controller`

#### __CAUTION__
__I had to apply this patch in order to make the trait works with WebTestCase
https://github.com/hautelook/AliceBundle/pull/441
The composer repository version still doesn't have this patch, so I had to apply it directly to the code.__

### Prices and Discounts format

The following formats are accepted as price and discount amount:

* Integer. Ex: 10, 999, 500
* Decimal. Ex: 5.5, 4.56
* With Currency. Ex: 10EUR, 5.5EUR - _only EUR is supported by now_
* **For discounts only**: with a negative sign. Ex: -10EUR, -10, -5.5

### Security

This project uses Symfony Security for basic token authentication.

The requests should send along a `X-AUTH-TOKEN`.

As this is a challenge project, it comes with two tokens and it's not possibile to create new tokens for now.

All endpoints needs a token. The API has endpoints that are exclusive for ADMINs and CUSTOMERs roles, the ADMIN token has both roles and should be able to access all endpoints.

You should use one of this two tokens:

#### ADMINs

For ADMINs restricted endpoints.

`X-AUTH-TOKEN: ADMIN_TOKEN`

#### CUSTOMERs

For CUSTOMERs restricted endpoints.

`X-AUTH-TOKEN: CUSTOMER_TOKEN`

### Customer

#### `GET /customers`
*__admin__ only*

List all customers.

#### `GET /customers/{id}`
*__admin__ only*

Retrieve one customer by ID.

#### `DELETE /customers/{id}`
*__admin__ only*

Deletes one customer by ID.

#### `POST /customers`
*__admin__ only*

Create one customer.

#### Body data format
```json
{
  'name': 'Customer'
}
```
#### `PATCH /customers/{id}`
*__admin__ only*

Updates one customer by ID.

#### Body data format
```json
{
  'name': 'Customer New Name'
}
```

### Product
### Bundle
### Order
