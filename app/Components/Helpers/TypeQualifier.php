<?php


namespace servelat\Components\Helpers;


class TypeQualifier
{
    public function getType($value)
    {
        $type = gettype($value);
        if ('resource' === $type) {
            return $this->getResourceType($value);
        }

        return $type;
    }

    public function getResourceType($value)
    {
        return get_resource_type($value);
    }
}