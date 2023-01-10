# Dynamic ACL

Dynamic ACL is a package that handles Access Control Level on your Laravel Application. It's fast to run and simple to use. [Install](#installation) and enjoy ;)

## Features

- [Check Permissions](#check_routes): Check routes dynamically on admin permissions.
- [Simple Policy](#simple_policy): Check user_id on your entities (if the admin has access to this).

<h2 id="installation">Installation</h2>

### Prerequisite:

- Make your authentication (session-based) system.
- Define a name for your routes.

```php
composer require iya30n/dynamic-acl
```

### Publish config file

```php
php artisan vendor:publish
```

### Migrate roles table

```php
php artisan migrate
```

> Don't worry about relationships; We handle them for you.

### Run `make:admin` command

This command makes your first admin a super admin with a full-access level.

```php
php artisan make:admin --role
```

## Usage

Just run your application and visit `locahost:8000/admin/roles`.
You'll see a list of your roles. You can create a new one, edit or delete them.

### Configuration

After publishing the vendor, you can change the configuration in the `config/dynamicACL.php` file.

It has the following options:

- **alignment:** Changes UI alignment. It can be eigther RTL or LTR. Also, when you change your lang, CRUD roles will change in (fa, en).
- **controllers_path:** Namespace of your controllers.
- **ignore_list:** List of routes to be ignored on permission check.

<h3 id="check_routes">How to use the ACL?</h3>

Just add **dynamicAcl** middleware to your routes.
> now you'll see list of the routes with **dynamicAcl** middleware on `localhost:8000/admin/roles/create`.
>
> also, this middleware will check your admin access to the current route.

<h3 id="list_of_the_roles">Access to the roles</h3>

You can write your queries with the Role model to get the list of roles and use it on your admin/user CRUD views.

```php
use Iya30n\DynamicAcl\Models\Role;
```

<h3 id="sync_user_roles">Sync user roles</h3>

You can use `sync`, `attach`, and `detach` methods to assign user roles.

```php
$user->roles()->sync([1, 2, 3,...]);
```

<h3 id="get_user_roles">Get user roles</h3>

```php
$user->roles()->get();
```

<h3 id="check_user_access">Check user access manually</h3>

Call the `hasPermission` method on the user and pass the route name:

```php
auth()->user()->hasPermission('admin.articles.create');
```

Check whether the user has access to any routes of an entity:

```php
auth()->user()->hasPermission('admin.articles.*')
```

Also, you can check if the user has access to his entity:

```php
$user->hasPermission('admin.articles.update', $article);

// Or with a custom relation_key (default is 'user_id')
$user->hasPermission('admin.articles.update', $article, 'owner_id');
```

### Check Ownership

Check manually whether the user is the owner of an entity:

```php
$user->isOwner($article);

// Or with a custom relation_key
$user->isOwner($article, 'owner_id');

// Or pass it as ['model' => id]
$user->isOwner(['article' => $article->id]);

// Or with a custom model path
$user->isOwner(['App\\Article' => $article->id]);
```

<h3 id="simple_policy">Dynamic Policy</h3>

Using dynamic policy is straightforward too. Just add **authorize** middleware to your routes. You can pass the foreign key as a parameter (default is user_id). This middleware checks the foreign key of your entity.

> **NOTE:** It is necessary to use route model binding on your controllers.
