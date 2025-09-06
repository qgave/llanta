# Llanta Framework

A lightweight PHP framework for web applications.

## Usage

### Helpers
All files with the `.php` extension inside the `src/helpers` folder will be executed automatically, before the `src/app.php` file is executed.

### Route

```php
route()->get('/', function (UserModel $user) {})->middleware(Middleware::class);
route()->post('/user/{id}', IndexController::class);
route()->put('/', IndexController::class);
route()->patch('/', IndexController::class);
route()->delete('/', IndexController::class);
route()->options('/', IndexController::class);
route()->any('/*', IndexController::class);
```

### Request

```php
request()->query()->id; #parsed query string
request()->getQueryString(); #raw query string
request()->getMethod();
request()->getURI();
request()->getRequestURI();
request()->getFullPath();
request()->getScriptName();
request()->getBasePath();
request()->getPath();
request()->getServerProtocol();
request()->getHttp();
request()->isSecure();
request()->getHttpHost();
request()->getBaseUrl();
request()->getURL();
request()->getPreviousPath();
request()->getContentType();
request()->getUserAgent();
request()->getPayload();
request()->body()->name;
request()->params()->id; #/user/{id}
```
### DB

```php
#configuration
db()->config()->MySQL()->hostname($hostname)->username($username)->password($password)->database($database)->connect();
db()->config()->PostgreSQL()->hostname($hostname)->port($port)->password($password)->database($database)->connect();
db()->config()->SQLite()->path($path)->->connect();
db()->config()->SQLServer()->hostname($hostname)->username($username)->password($password)->database($database)->connect();
db()->config()->OCI()->hostname($hostname)->serviceName($serviceName)->port($port)->username($username)->password($password)->database($database)->connect();

#query
db()->query($query);
```

### Session
`Note` To obtain the value of a temporary session, use the get function.
```php
session()->get($key);
session()->set($key, $value);
session()->delete($key);
session()->clear();
session()->temp($key, $value);
session()->exist($key);
```

### Cookie
```php
cookie()->get($key);
cookie()->set($key, $value, $expire, $path, $domain, $secure, $httponly);
cookie()->delete($key);
```
### Response
```php
response()->statusCode($int);
response()->addHeaders($key, $value); #key: $value
response()->send($response, $statusCode, $headers); #stops execution and sends the response
```
### Redirect
```php
redirect()->url($url);
redirect()->to($path);
redirect()->back();
redirect()->refresh();
```

### Logger
```php
logger()->disabled();
logger()->error($value);
logger()->info($value);
```
### Clock
```php
clock($timeZone)->format($format);
clock($timeZone)->dateTime();
clock($timeZone)->date();
clock($timeZone)->time();
clock($timeZone)->year();
clock($timeZone)->month();
clock($timeZone)->day();
clock($timeZone)->hour();
clock($timeZone)->minute();
clock($timeZone)->second();
clock($timeZone)->unix();
```
### Encryption
```php
encryption()->principalKey($key) #no need to call function
encryption()->secondaryKey($key) #no need to call function
encryption()->encrypt($data)
encryption()->decrypt($data)
```
### Random
```php
random()->number($minimum, $maximum);
random()->alphabet($length);
random()->alphanumeric($length);
random()->pick($data, $length);
random()->unique();
```

### serveFile
```php
serveFile($path, $download);
```
### Hashing
```php
hashing()->create($data, $rounds);
hashing()->compare($data, $hash);
```
### Scope
```php
scope()->store($key, $value);
scope()->collect($key);
```
### Render
```php
render($view, $data);
```
### Debug
Debugging is enabled by default
```php
debug()->enabled();
debug()->disabled();
```

### Kit
The name of the function must be the same as the name of the kit.
```php
kit()->store($name, $class)
    ->props($value1, $value2)/* default parameters which will be received by the class constructor */
    ->instance($instance);/* define the class instance if you already have it */

function example() {
    return kit()->get($props, $store /* whether to use the existing instance or create a new one each time the function is called. */); #The parameters are optional.
}
```

### View
#### src/views/index.php
```php
<?php $this->extends('template'); ?>

<?php $this->section('content'); ?>
    <span>$content</span>
<?php $this->endSection(); ?>

<?php $this->section('title', $title); ?>
```

#### src/views/template.php
```php
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $this->getSection('title'); ?></title>
</head>

<body>
    <?= $this->getSection('content'); ?>
</body>

</html>
```

### Controller

Both the constructor and the functions can receive dependency injection.

#### src/controllers/IndexController.php
```php
namespace Src\Controllers;

use Src\Models\UserModel;

class IndexController {
    protected UserModel $user;
    
    public function __construct(UserModel $user) {
        $this->user = $user;
    }

    public function index() {
        return render('home');
    }

    public function home() {
        $user = scope()->collect('user');
    }
}
```

### Middleware

The `before` function will be executed before the action is executed, and the `after` function will be executed after the response is sent.

`Note`: dependencies can be injected into both functions, but the `before` function must always have the parameter $next (you can change the name of the parameter) at the end.

#### src/middlewares/Auth.php
```php
namespace Src\Middlewares;

use Src\Models\UserModel;

class Auth {

    public function before(UserModel $user, $next) {
        scope()->store('user', $user);
        if ($conditional) $next(); //Execution stops and moves on to the next one.
        response()->send(redirect()->to($path));
    }

    public function after() {

    }
}
```