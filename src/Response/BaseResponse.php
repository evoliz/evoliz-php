<?php

namespace Evoliz\Client\Response;

abstract class BaseResponse
{
    /**
     * @param array $data Response array to build the object
     */
    public function __construct(array $data)
    {
        $this->assignAttribute($this, $data);
    }

    /**
     * Assign response attributes retrieved from the API
     *
     * @param  BaseResponse|\stdClass $object Object to assign
     * @param  array                  $array  Value to assign to the object
     * @return void
     */
    public function assignAttribute($object, array $array)
    {
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $object->$key = new \stdClass();
                $this->assignAttribute($object->$key, $value);
            } else {
                $object->$key = $value;
            }
        }
    }
}
