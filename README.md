# Laravel Model UUIDs
## v4.0.1

[![Build Status](https://travis-ci.org/michaeldyrynda/laravel-model-uuid.svg?branch=master)](https://travis-ci.org/michaeldyrynda/laravel-model-uuid)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/michaeldyrynda/laravel-model-uuid/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/michaeldyrynda/laravel-model-uuid/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/michaeldyrynda/laravel-model-uuid/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/michaeldyrynda/laravel-model-uuid/?branch=master)
[![Latest Stable Version](https://poser.pugx.org/dyrynda/laravel-model-uuid/v/stable)](https://packagist.org/packages/dyrynda/laravel-model-uuid)
[![Total Downloads](https://poser.pugx.org/dyrynda/laravel-model-uuid/downloads)](https://packagist.org/packages/dyrynda/laravel-model-uuid)
[![License](https://poser.pugx.org/dyrynda/laravel-model-uuid/license)](https://packagist.org/packages/dyrynda/laravel-model-uuid)
[![Dependency Status](https://www.versioneye.com/php/dyrynda:laravel-model-uuid/dev-master/badge?style=flat-square)](https://www.versioneye.com/php/dyrynda:laravel-model-uuid/dev-master)

## Introduction

I find myself using UUID across multiple projects of late, and packaged up this functionality rather than copying and pasting it from project to project.

**Note**: this package explicitly does not disable auto-incrementing on your Eloquent models. In terms of database indexing, it is generally more efficient to use auto-incrementing integers for your internal querying. Indexing your `uuid` column will make lookups against that column fast, without impacting queries between related models.

For more information, check out [this post](https://www.percona.com/blog/2014/12/19/store-uuid-optimized-way/) on storing and working with UUID in an optimised manner.

Take a look at [laravel-efficient-uuid](https://github.com/michaeldyrynda/laravel-efficient-uuid) if you want to make it easy to generate migrations that efficiently store UUID in your database.

This package supports time-ordered UUIDs in Laravel 5.6 as of version 4.0.0.

## Code Samples

In order to use this package, you simply need to import and use the trait within your Eloquent models.

```php
<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Dyrynda\Database\Support\GeneratesUuid;

class Post extends Model
{
    use GeneratesUuid;
}
```

It is assumed that you already have a field named `uuid` in your database, which is used to store the generated value. If you wish to use a custom column name, for example if you want your primary `id` column to be a `UUID`, you can define a `uuidColumn` method in your model.

```php
class Post extends Model
{
    public function uuidColumn()
    {
        return 'custom_column';
    }
}
```

By default, this package will use UUID version 4 values, however, you are welcome to use `uuid1`, `uuid3`, `uuid4`, or `uuid5` by specifying the protected property `$uuidVersion` in your model. Should you wish to take advantage of ordered UUID (version 4) values that were introduced in Laravel 5.6, you should specify `ordered` as the `$uuidVersion` in your model.

```php
<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Dyrynda\Database\Support\GeneratesUuid;

class Post extends Model
{
    use GeneratesUuid;

    protected $uuidVersion = 'uuid5';
}
```

This trait also provides a query scope which will allow you to easily find your records based on their UUID, and respects any custom field name you choose.

```php
$post = Post::whereUuid($uuid)->first();
```

If you use the suggested [laravel-efficient-uuid](https://github.com/michaeldyrynda/laravel-efficient-uuid) package, you will need to add a cast to your model in order to correctly set and retrieve your UUID values. This will ensure your UUIDs are written to your (MySQL) database as binary and presented as strings.

```php
<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Dyrynda\Database\Support\GeneratesUuid;

class Post extends Model
{
    use GeneratesUuid;

    protected $casts = ['uuid' => 'uuid'];
}
```

## Route model binding

Should you wish to leverage implicit route model binding on your `uuid` field, you'll need to override the `getRouteKeyName` method in your Eloquent model.

```php
public function getRouteKeyName()
{
    return 'uuid';
}
```

*If you are using the [laravel-efficient-uuid](https://github.com/michaeldyrynda/laravel-efficient-uuid) package, implicit route model binding won't work out of the box.*

Laravel will execute the query using the string representation of the `uuid` field when querying against the binary data stored in the database. In this instance, you will need to explicitly bind the parameter using the included scope in your `RouteServiceProvider`:

```php
// app/Providers/RouteServiceProvider.php

public function boot()
{
    Route::bind('post', function ($post) {
        return \App\Post::whereUuid($post)->first();
    });
}
```

## Installation

This package is installed via [Composer](https://getcomposer.org/). To install, run the following command.

```bash
composer require "dyrynda/laravel-model-uuid:~4.0"
```
## Support

If you are having general issues with this package, feel free to contact me on [Twitter](https://twitter.com/michaeldyrynda).

If you believe you have found an issue, please report it using the [GitHub issue tracker](https://github.com/michaeldyrynda/laravel-model-uuid/issues), or better yet, fork the repository and submit a pull request.

If you're using this package, I'd love to hear your thoughts. Thanks!
