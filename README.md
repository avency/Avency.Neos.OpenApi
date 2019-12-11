# Avency Neos OpenAPI

This package contains basics to generate an interactive OpenAPI documentation for a RESTful API using doctrine annotations.
 
It contains a controller that renders a swagger ui and which gets its input from a json-file generated based on the OpenAPI annotations in the projects' files.

It uses the `sintbert/swagger-php` package.

## Calling the documentation

### Backend

There is a backend module accessible as administrator, which renders the swagger UI.

### Frontend

To call the documentation in the frontend, you need to be logged in in the backend as administrator.
Then you can call the api:

```
{PROJECT_BASE_URI}/neos/api/documentation.html
```

To access just the generated json which is used to fill the Swagger UI, you can directly call the json file:

```
{PROJECT_BASE_URI}/neos/api/documentation.json
```

## Writing the documentation

### Files to consider for documentation

There is the possibility to specify the folder(s), which need to be considered for generating the documentation. The default is set to
```
FLOW_PATH_ROOT/LocalPackages
```

To change this, just change the Settings:
```
Avency:
  Neos:
    OpenApi:
      scanPath: {NEW_PATH}
```

### Import Statement
All files in which Annotations for the Documentation are added need to use the following statement

```
use OpenApi\Annotations as OA;
```

### Annotations

#### Info
Need to be added in a separate file and contains general information about the api for example:

```
/**
 * @OA\Info(
 *   version="0.1.0",
 *   title="Portal Api",
 *   description="This is a the documentation for the API which is used in the admin panel."
 * )
 */
```

#### Tag
A tag serves as group in which the different api endpoints can be grouped in. 

It should be defined in top of the distinct controllers.

The tag can be referenced in the different operations.

```
/**
 * @OA\Tag(
 *   name="portal user",
 *   description="Everything about frontend portal users"
 * )
 */
```

#### Operations
There are different operations available to describe the different api endpoints, like `@OA\Get`, `@OA\Post`, `@OA\Put` or `@OA\Delete`. 

They should be each defined in the method description.

They contain a `path`, a brief `summary` and a `description`. `@OA\Parameters` can be added, if the url accepts parameters. The `@OA\Response` defines the different response codes.

```
/**
 *  @OA\Get(
 *    path="/api/v1/portaluser",
 *    summary="Find portal user by identifier",
 *    description="Returns user data for the given DB identifier or the specific value for a property",
 *    tags={"portal user"},
 *    @OA\Parameter(
 *      name="identifier",
 *      in="query",
 *      description="Identifier of user object",
 *      required=true,
 *      @OA\Schema(
 *        type="string",
 *        @OA\Items(type="string"),
 *      )
 *    ),
 *    @OA\Response(
 *      response=200,
 *      description="successful operation, returns user data",
 *      @OA\JsonContent(
 *        ref="#/components/schemas/User",
 *        type="object",
 *        @OA\Items(ref="#/components/schemas/User")
 *      ),
 *    ),
 *    @OA\Response(
 *      response="500",
 *      description="error - identifier was not passed as string, any portal user found by identifier",
 *    )
 *  )
 */
```

#### Schema

The schema describes a representation of a model. 

The annotation should be added on the domain model. The `schema`-attribute is taken by the class name, but can be overwritten or set, if a special schema is defined outside of a domain model. 

They contain a `title` and a brief `description`. If necessary, special `@OA\Property` values can be defined, if the schema should represent more than the in the class defined variables, like the identifier.

```
/**
 * @OA\Schema(
 *   schema="User",
 *   title="User model",
 *   description="User model with all user related data",
 *   @OA\Xml(
 *     name="User"
 *   ),
 *   @OA\Property(
 *     property="identifier",
 *     description="db-identifier",
 *     title="identifier",
 *     example="XXXXXXXX-XXXX-XXXX-XXXX-XXXXXXXXXXXX",
 *     type="string"
 *   )
 * )
 */
```

#### Property

The property describes a single property in a model. The annotation should be added close to the property. 

Some information about type does not need to be explicitly defined but is for example taken from the type of the property.

```
/**
 * @OA\Property(
 *     description="Last Name",
 *     title="Last Name",
 *     example="Mustermann"
 * )
 *
 * @var string
 * @ORM\Column(nullable=TRUE)
 */
protected $lastName;
```

It is also possible to refer to other Schema objects. This will be also detected by the property type, if there is an other schema for this type. 
```
/**
 * @OA\Property(
 *     description="Address",
 *     title="Address"
 * )
 *
 * @ORM\OneToOne(cascade={"persist", "remove"})
 * @ORM\Column(nullable=TRUE)
 * @var Address
 */
protected $defaultAddress;
```

References can be also added manually.

```
@OA\Items(ref="#/components/schemas/Address")
```
## More Information

Other parameters or annotations are available. More information can be found on the OpenAPI Specification website:

https://swagger.io/specification/

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
