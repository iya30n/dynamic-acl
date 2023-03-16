<?php

namespace Iya30n\DynamicAcl\Macros;

use Iya30n\DynamicAcl\ACL;

class HasPermission
{
    public static function GetMacro(): callable
    {
        return function (string $access, $entity = null, $foreignKey = null) {
            if (in_array($access, config('dynamicACL.ignore_list', []))) return true;

            if (!$this->allRoles)
                $this->allRoles = $this->roles()->get();

            $hasAccess = false;
            foreach ($this->allRoles as $role) {
                if (ACL::checkAccess('fullAccess', $role->permissions)) return true;

                $hasAccess = ACL::checkAccess($access, $role->permissions);

                if ($hasAccess) break;
            }

            return $hasAccess && $this->isOwner($entity, $foreignKey);
        };
    }
}