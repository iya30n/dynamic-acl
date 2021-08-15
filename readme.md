# Dynamic ACL

Dynamic ACL is a package that handles Access Control Level on your Laravel Application.

It's fast to running and simple to use.

Install and enjoy.

---

## Features

- Check routes dynamically on Admin permissions.
- Check user_id on your entities (if admin has access to this).

---

## Installation

> **NOTE:** you need to make your authentication (session based) system before.

> **NOTE:** you should define name for your routes.


~~composer require iya30n/dynamic-acl~~

i didn't publish it yet. you should install it locally. [more information](https://getcomposer.org/doc/05-repositories.md#path)


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

- **controllers_path:** this is your controllers namespace.
- **ignore_list:** you can add your routes to be ignore on check permissions.

---

### how to use the ACL?

it's very very simple.

just add **dynamicAcl** middleware to your routes.

> this middleware will check your admin access to current route.

---

### how to use dynamic policy?

> **NOTE:** you should use route model binding on your controllers.

it's very simple too.

just add **authorize** middleware to your routes.

> this middleware will check user_id on your entity.