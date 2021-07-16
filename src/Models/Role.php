<?php

namespace Iya30n\DynamicAcl\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'permissions'
    ];

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
     * user relation
     * 
     * @return string
     */
    public function users()
    {
        return $this->belongsToMany(config('auth.providers.users.model'));
    }
}
