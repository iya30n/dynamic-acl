# Dynamic ACL

Dynamic ACL is a package that handles Access Control Level on your Laravel Application.

It's fast to running and simple to use.

<a href="#installation">Install</a> and enjoy.

---

## Features

- <a href="#check_routes">Check permissions:</a> Check routes dynamically on Admin permissions.
- <a href="#simple_policy">Simple policy:</a> Check user_id on your entities (if admin has access to this).

---

<span id="installation"><h3>Installation</h3></span>

> **NOTE:** you need to make your authentication (session based) system before.

> **NOTE:** you should define name for your routes.


```php
composer require iya30n/dynamic-acl
```


### publish config file

```php
php artisan vendor:publish
```

### migrate roles table

```php
php artisan migrate
```

> Don't worry about relationships, they handled already.

### run make:admin command

this command makes your first admin as super admin with fullAccess level.

```php
php artisan make:admin --role
```

### finish

just run your application and visit **locahost:8000/admin/roles** .

you'll see list of your roles.

you can create new one, edit or delete them.

---

### config

after publish vendor you can change config on **config/dynamicACL.php** file.

- **alignment:** you can change UI alignment to rtl or ltr. also when you change your lang, roles CRUD will be changing in (fa, en).
- **controllers_path:** this is your controllers namespace.
- **ignore_list:** you can add your routes to be ignore on check permissions.

---

<h3 id="check_routes">how to use the ACL?</h3>

just add **dynamicAcl** middleware to your routes.
> now you'll see list of the routes with **dynamicAcl** middleware on **localhost:8000/admin/roles/create**.
>
> also this middleware will check your admin access to current route.
---
<h3 id="list_of_the_roles">access to the roles</h3>
get list of the roles and use it on your own admin/user CRUD views.

you can use Role model to write your own queries and get list of the roles.

```php
use Iya30n\DynamicAcl\Models\Role;
```

---
<h3 id="sync_user_roles">sync user roles</h3>
you can use these methods on your CRUD user methods.

```php
$user->roles()->sync([1, 2, 3,...]);
// or
$user->roles()->attach([1, 2, 3,...]);
// or
$user->roles()->dettach([1, 2, 3,...]);
```

---
<h3 id="get_user_roles">get user roles</h3>

```php
$user->roles()->get();
```

---

<h3 id="check_user_access">check user access manually</h3>

call hasPermission method on user and pass the route name.

```php
$user->hasPermission('admin.articles');

// or

auth()->user()->hasPermission('admin.articles');
```

---

<h3 id="simple_policy">how to use dynamic policy?</h3>

> **NOTE:** you should use route model binding on your controllers.

it's very simple too.

just add **authorize** middleware to your routes.

> this middleware will check user_id on your entity.
