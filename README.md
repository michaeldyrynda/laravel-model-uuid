# Laravel Model UUIDs

[![Build Status](https://github.com/michaeldyrynda/laravel-model-uuid/workflows/run-tests/badge.svg)](https://github.com/michaeldyrynda/laravel-model-uuid/actions?query=workflow%3Arun-tests)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/michaeldyrynda/laravel-model-uuid/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/michaeldyrynda/laravel-model-uuid/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/michaeldyrynda/laravel-model-uuid/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/michaeldyrynda/laravel-model-uuid/?branch=master)
[![Latest Stable Version](https://poser.pugx.org/dyrynda/laravel-model-uuid/v/stable)](https://packagist.org/packages/dyrynda/laravel-model-uuid)
[![Total Downloads](https://poser.pugx.org/dyrynda/laravel-model-uuid/downloads)](https://packagist.org/packages/dyrynda/laravel-model-uuid)
[![License](https://poser.pugx.org/dyrynda/laravel-model-uuid/license)](https://packagist.org/packages/dyrynda/laravel-model-uuid)
[![Dependency Status](https://www.versioneye.com/php/dyrynda:laravel-model-uuid/dev-master/badge?style=flat-square)](https://www.versioneye.com/php/dyrynda:laravel-model-uuid/dev-master)
[![Buy us a tree](https://img.shields.io/badge/Treeware-%F0%9F%8C%B3-lightgreen)](https://plant.treeware.earth/michaeldyrynda/laravel-model-uuid)

## Introduction

I find myself using UUID across multiple projects of late, and packaged up this functionality rather than copying and pasting it from project to project.

**Note**: this package explicitly does not disable auto-incrementing on your Eloquent models. In terms of database indexing, it is generally more efficient to use auto-incrementing integers for your internal querying. Indexing your `uuid` column will make lookups against that column fast, without impacting queries between related models.

For more information, check out [this post](https://www.percona.com/blog/2014/12/19/store-uuid-optimized-way/) on storing and working with UUID in an optimised manner.

Take a look at [laravel-efficient-uuid](https://github.com/michaeldyrynda/laravel-efficient-uuid) if you want to make it easy to generate migrations that efficiently store UUID in your database.

If you require compatibility with `ramsey/uuid` >= 4.1, please use version >= 6.2.0 of this package.

As of version 7.1.0 this package supports only UUID versions 1 (`uuid1`), 4 (`uuid4`), 6 (`uuid6` - ordered) and `ordered` ([Laravel's ordered UUID v4](https://laravel.com/docs/master/helpers#method-str-ordered-uuid)) and 7 (`uuid7`). 

This package supports Laravel 10 and PHP 8.1 as minimum.

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
    public function uuidColumn(): string
    {
        return 'custom_column';
    }
}
```

You can have multiple UUID columns in each table by specifying an array in the `uuidColumns` method. When querying using the `whereUuid` scope, the default column - specified by `uuidColumn` will be used.

```php
class Post extends Model
{
    public function uuidColumns(): array
    {
        return ['uuid', 'custom_column'];
    }
}
```

The package will use `uuid` as the column name to store the generated UUID value by default. If you prefer a different name, you may change the `model-uuid.column_name` config variable.

You may also override the `uuidColumn` method on a per-model basis.

By default, this package will use UUID version 4 values, however, you are welcome to use `uuid1`, `uuid4`, `uuid6`, or `uuid7` by specifying the protected property `$uuidVersion` in your model. Should you wish to take advantage of [ordered UUID (version 4) values that were introduced in Laravel 5.6](https://laravel.com/docs/master/helpers#method-str-ordered-uuid), you should specify `ordered` as the `$uuidVersion` in your model.

```php
<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Dyrynda\Database\Support\GeneratesUuid;

class Post extends Model
{
    use GeneratesUuid;

    public function uuidVersion(): string
    {
        return 'uuid5';
    }
}
```

Whilst not recommended, if you _do_ choose to use a UUID as your primary model key (`id`), be sure to configure your model for this setup correctly. Not updating these properties will lead to Laravel attempting to convert your `id` column to an integer, which will be cast to `0`. When used in combination with [`laravel-efficient-uuid`](https://github.com/michaeldyrynda/laravel-efficient-uuid), this casting will result in a `Ramsey\Uuid\Exception\InvalidUuidStringException` being thrown.

```php
<?php

namespace App;

use Dyrynda\Database\Support\GeneratesUuid;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use GeneratesUuid;

    public $incrementing = false;

    protected $keyType = 'string';
}
```

This trait also provides a query scope which will allow you to easily find your records based on their UUID, and respects any custom field name you choose.

```php
// Find a specific post with the default (uuid) column name
$post = Post::whereUuid($uuid)->first();

// Find multiple posts with the default (uuid) column name
$post = Post::whereUuid([$first, $second])->get();

// Find a specific post with a custom column name
$post = Post::whereUuid($uuid, 'custom_column')->first();

// Find multiple posts with a custom column name
$post = Post::whereUuid([$first, $second], 'custom_column')->get();
```

If you use the suggested [laravel-efficient-uuid](https://github.com/michaeldyrynda/laravel-efficient-uuid) package, you will need to add a cast to your model in order to correctly set and retrieve your UUID values. This will ensure your UUIDs are written to your (MySQL) database as binary and presented as strings.

```php
<?php

namespace App;

use Dyrynda\Database\Casts\EfficientUuid;
use Dyrynda\Database\Support\GeneratesUuid;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use GeneratesUuid;

    protected $casts = [
        'uuid' => EfficientUuid::class,
    ];
}
```

## Route model binding

From 6.5.0, should you wish to leverage implicit route model binding on your `uuid` field, you may use the `BindsOnUuid` trait, which will use the configured `uuidColumn` by default.

```php
<?php

namespace App;

use Dyrynda\Database\Support\BindsOnUuid;
use Dyrynda\Database\Support\GeneratesUuid;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use BindsOnUuid, GeneratesUuid;
}
```

Should you require additional control over the binding, or are using < 6.5.0 of this package, you may override the `getRouteKeyName` method directly.

```php
public function getRouteKeyName(): string
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
composer require dyrynda/laravel-model-uuid
```

If you wish to override default configuration, you may publish the configuration file to your application.

```bash
php artisan vendor:publish --tag=model-uuid-config
```

## Support

If you are having general issues with this package, feel free to contact me on [Twitter](https://twitter.com/michaeldyrynda).

If you believe you have found an issue, please report it using the [GitHub issue tracker](https://github.com/michaeldyrynda/laravel-model-uuid/issues), or better yet, fork the repository and submit a pull request.

If you're using this package, I'd love to hear your thoughts. Thanks!

## Treeware

You're free to use this package, but if it makes it to your production environment you are required to buy the world a tree.

It’s now common knowledge that one of the best tools to tackle the climate crisis and keep our temperatures from rising above 1.5C is to plant trees. If you support this package and contribute to the Treeware forest you’ll be creating employment for local families and restoring wildlife habitats.

You can buy trees [here](https://plant.treeware.earth/michaeldyrynda/laravel-model-uuid)

Read more about Treeware at [treeware.earth](https://treeware.earth)
