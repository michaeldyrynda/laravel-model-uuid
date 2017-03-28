# Laravel Model UUIDs
## v1.0.0

## Introduction

I find myself using UUID across multiple projects of late, and packaged up this functionality rather than copying and pasting it from project to project.

## Code Samples

In order to use this package, you simply need to import and use the trait within your Eloquent models.

```php
<?php

namespace App;

use Dyrynda\Database\Support\GeneratesUuid;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use GeneratesUuid;
}
```

It is assumed that you already have a field named `uuid` in your database, which is used to store the generated value. Should you need to use a different field name, specify the protected property `$uuidField` in your model.

```php
<?php

namespace App;

use Dyrynda\Database\Support\GeneratesUuid;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use GeneratesUuid;

    protected $uuidField = 'unique_uuid_field';
}
```

By default, this package will use UUID version 4 values, however, you are welcome to use `uuid1`, `uuid3`, `uuid4`, or `uuid5` by specifying the protected property `$uuidVersion` in your model.

```php
<?php

namespace App;

use Dyrynda\Database\Support\GeneratesUuid;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use GeneratesUuid;

    protected $uuidVersion = 'uuid5';
}
```

## Installation

This package is installed via [Composer](https://getcomposer.org/). To install, run the following command.

```bash
composer require dyrynda/laravel-model-uuid
```
## Support

If you are having general issues with this package, feel free to contact me on [Twitter](https://twitter.com/michaeldyrynda).

If you believe you have found an issue, please report it using the [GitHub issue tracker](https://github.com/michaeldyrynda/laravel-model-uuid/issues), or better yet, fork the repository and submit a pull request.

If you're using this package, I'd love to hear your thoughts. Thanks!
