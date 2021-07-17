<?php

namespace Iya30n\DynamicAcl\Console\Commands;

use Illuminate\Console\Command;
use Iya30n\DynamicAcl\Models\Role;

class MakeAdmin extends Command
{
    protected $signature = 'make:admin {--role : make admin with super_admin role}';

    protected $description = 'make your super admin';

    public function handle()
    {
        $admin = app(config('auth.providers.users.model'));

        foreach ($admin->getFillable() as $field) {
            $inputField = $this->ask("insert the $field");

            if (in_array($inputField, [null, '', 'null',])) {
                $this->error("the field $field can not be null or empty");
                return;
            }

            if ($field == 'email' && $admin->where('email', $inputField)->exists()) {
                $this->error("Admin already exists!");
                return;
            }

            if ($field == 'password')
                $inputField = bcrypt($inputField);

            $admin->{$field} = $inputField;
        }

        $admin->save();

        $this->info('admin created successfully');

        if ($this->option('role'))
            $this->makeFullAccessRole($admin);
    }

    private function makeFullAccessRole($admin)
    {
        $role = Role::firstOrCreate(['name' => 'super_admin'], [
            'name' => 'super_admin',
            'permissions' => ['fullAccess' => 1]
        ]);

        $admin->roles()->sync($role->id);

        $this->info("super_admin access attached to {$admin->name}");
    }
}