<?php

namespace Iya30n\DynamicAcl\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * Set the permissions of role as serialized
     *
     * @param string $value
     *
     * @return void
     */
    public function setPermissionsAttribute($value)
    {
        $this->attributes['permissions'] = serialize($value);
    }

    /**
     * get unserialized permissions
     *
     * @return Array
     */
    public function getPermissionsAttribute(): array
    {
        return unserialize($this->attributes['permissions']);
    }

    /**
     * get count of attached admins
     *
     * @return integer
     */
    public function getUsersCount()
    {
        return $this->users->count();
    }

    /**
     * check if current rule is super admin role
     *
     * @return bool
     */
    public function is_super_admin()
    {
        return $this->name == 'super_admin';
    }

    /**
     * user relation
     * 
     * @return string
     */
    public function users()
    {
        $userModel = config()->has('easy_panel.user_model') ? config('easy_panel.user_model') : config('auth.providers.users.model');
        return $this->belongsToMany($userModel);
    }
}
