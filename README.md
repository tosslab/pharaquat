# Pharaquat: PHP API Wrapper for JANDI

## Requirements
- php: >= 7.1
- ext-curl: *
- ext-json: *
- ext-zlib: *

## Checking Version
```bash
$ php /path/to/project/dist/pharaquat -v
```

## Using Library
```php
require_once('/path/to/project/dist/pharaquat');
```

## Creating Client
```php
$client = new Jandi\Client();
$client->setEnv('production');
```

### Jandi\Client::setEnv()
```php
Jandi\Client::setEnv(string $env): bool
```
| Param | Type | Required | Available Values | Default |
| --- |:---:|:---:| --- | --- |
| env | string | O | 'development', 'production' | 'development' |

## Getting Auth
```php
$auth = $client->getAuthAsMember('onion.jeong@tosslab.com', 'password');
if ($auth->isValid()) {
    echo 'auth succeeded', PHP_EOL;
} else {
    echo 'auth failed', PHP_EOL;
    exit();
}
```

### Jandi\Client::getAuthAsMember()
```php
Jandi\Client::getAuthAsMember(string $username, string $password): Jandi\Auth\AuthInterface
```
| Param | Type | Required | Example |
| --- |:---:|:---:| --- | 
| username | string | O | 'onion.jeong@tosslab.com' |
| password | string | O | 'password' |

### Jandi\Auth\AuthInterface::isValid()
```php
Jandi\Auth\AuthInterface::isValid(): bool
```

## Creating Request

### Attachment
```php
$request = new Jandi\Request\Attachment([
    'auth' => $auth,
    'team' => 2816,
    'room' => 3927,
    'file' => [
        'file' => '/path/to/image.png',
    ],
]);
```

#### Request
| Key | Type | Required | Example |
| --- |:---:|:---:| --- | 
| auth | `Jandi\AuthInterface` | O | | 
| team | int | O | 2816 |
| room | int | O | 3927 |
| file | array | O | [] |
| file.file | string | O | '/path/to/image.png' |

#### Response
| Key | Type | Required | Example |
| --- |:----:|:--------:| ---:|  
| id | int | O | 248 |

### Post
```php
$request = new Jandi\Request\Post([
    'auth' => $auth,
    'team' => 2816,
    'room' => 3927,
    'body' => [
        'title' => 'NEW TITLE',
        'content' => 'NEW CONTENT',
    ],
]);
```

#### Request
| Key | Type | Required | Example |
| --- |:---:|:---:| --- | 
| auth | `Jandi\AuthInterface` | O | | 
| team | int | O | 2816 |
| room | int | O | 3927 |
| body | array | O | [] |
| body.title | string | O | 'NEW TITLE' |
| body.content | string | O | 'NEW CONTENT' |
| body.fileIds | array | | [248] |

#### Response
| Key | Type | Required | Example |
| --- | --- | --- | --- |  
| id | int | O | 5735 |

## Sending Request
```php
$response = $client->send($request);
```

### Jandi\Client::send()
```php
Jandi\Client::send(Jandi\Request\RequestInterface $request): Jandi\Response
```
| Param | Type | Required | Example |
| --- |:---:|:---:| --- | 
| request | `Jandi\Request\RequestInterface` | O | new Jandi\Request\Post() |

## Getting Response

### Checking Status Code
```php
if ($response->getStatusCode() === 200) {
    echo 'request succeeded', PHP_EOL;
} else {
    echo 'request failed', PHP_EOL;
    exit();
}
```

#### Jandi\Response::getStatusCode()
```php
Jandi\Response::getStatusCode(): int
```

### Getting Data
```php
$responseData = $response->getData();
$id = $responseData->id;
```

#### Jandi\Response::getData()
```php
Jandi\Response::getData(): ?\stdClass
```

## Handling Exceptions
```php
try {
    // do something
} catch (Jandi\Exceoption $e) {
    echo $e, PHP_EOL;
} catch (\Exception $e) {
    echo $e, PHP_EOL;
} catch (\Error $e) {
    echo $e, PHP_EOL;
}
```

## Todo
- [ ] Docker Support
- [ ] Jandi\Auth\Bot
- [ ] Jandi\Request\Message
- [ ] Jandi\Request\File
