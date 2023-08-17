# customer-api
API to manage customers in database.

## To install on Linux:
```
$ git clone https://github.com/booksey/customer-api.git customer-api
$ cd customer-api
$ composer install
$ cat .env.dist > .env
```

## Setup .env file in project root
APP_ENV=environment  (default "dev", used for debugging)<br/>
AWS_ACCESS_KEY_ID and YOUR_AWS_SECRET_ACCESS_KEY are not necessary, I used AWS Dynamo DB Service for testing.

## Add Database Service to project
I used AWS DynamoDb service and created App\Services\DatabaseService to manage database statements.
Add AWS database environment variables to .env and you can use it right away.
If you want to change it, all you have to do is to replace DatabaseService.php with your own
service handler, that implements the API's DatabaseServiceInterface.

## PHP code analysis tools
I used these tools for PHP code analysis + testing: phpcs, phpmd, phpstan, phpunit. You can check my composer scripts for more info.<br/>
Check all:
```
$ composer check-all
```

## Endpoints:

### Get customer(s) from database
endpoint: /api/customer/get<br/>
method: POST<br/>
request body parameters: customerId (if not provided or null, API returns all Customer)<br/>
responseJson: (['data' => null|Customer|CustomerCollection], statusCode)

### Insert customer(s) to database
endpoint: /api/customer/create<br/>
method: POST<br/>
request body parameters: ['customers' => [...Customer]]
responseJson: (['success' => true|false], statusCode)

### Update customer(s) in database
endpoint: /api/customer/update<br/>
method: POST<br/>
request body parameters: ['customers' => [...Customer]]
responseJson: (['success' => true|false], statusCode)

### Delete customer(s) from database
endpoint: /api/customer/delete<br/>
method: POST<br/>
request body parameters: ['customers' => [...Customer]]
responseJson: (['success' => true|false], statusCode)
