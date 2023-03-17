<?php

namespace Iya30n\DynamicAcl\Macros;

use Illuminate\Support\Str;
use Exception;
use Illuminate\Database\Eloquent\Model;

class IsOwner
{
    public static function GetMacro(): callable
    {
        return function ($entity, $foreignKey = null) {
            if (!$entity instanceof Model && !is_array($entity)) return true;

            if (is_array($entity)) {
                $entityName = array_key_first($entity);
                $modelNamespace = Str::contains($entityName, '\\') ? $entityName : "\\App\\Models\\" . ucfirst($entityName);

                if (!class_exists($modelNamespace)) return true;

                $entity = app($modelNamespace)->findOrFail($entity[$entityName]);
            }

            $foreignKey = $foreignKey ?? $this->getForeignKey();

            $relationId = $entity->getOriginal($foreignKey);

            throw_if($relationId === null, new Exception("\"$foreignKey\" is not defined on " . get_class($entity)));

            return $relationId == $this->getOriginal($this->getKeyName());
        };
    }
}