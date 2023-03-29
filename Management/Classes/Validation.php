<?php

namespace Management\Classes;

class Validation
{
    public $errors = [];
    public function validate(array $request, array $requirment)
    {
        foreach ($requirment as $key => $value) {
            foreach ($value as $validation_type) {
                if ($validation_type == 'required') {
                    $this->required($key, $request);
                }
                if ($validation_type == 'email') {
                    $this->email($key, $request);
                }
                if ($validation_type == 'integer') {
                    $this->integer($key, $request);
                }
                if (substr($validation_type, 0, 3) == 'max') {
                    $this->max($key, $request, substr($validation_type, 4));
                }
                if (substr($validation_type, 0, 3) == 'min') {
                    $this->min($key, $request, substr($validation_type, 4));
                }
            }
        }
    }

    public function required(string $key, array $value)
    {
        if (!$value[$key]) {
            $this->errors[] = "The $key field is required.";
        }
    }

    public function integer(string $key, array $value)
    {
        if (!filter_var($value[$key], FILTER_VALIDATE_INT)) {
            $this->errors[] = "The $key must be an integer.";
        }
    }

    public function email(string $key, array $value)
    {
        if (!filter_var($value[$key], FILTER_VALIDATE_EMAIL)) {
            $this->errors[] = "The $key must be a valid email address";
        }
    }

    public function max(string $key, array $value, int $range)
    {
        if (strlen($value[$key]) > $range) {
            $this->errors[] = "The $key must not have more than $range items.";
        }
    }

    public function min(string $key, array $value, int $range)
    {
        if (strlen($value[$key]) < $range) {
            $this->errors[] = "The $key must have at least $range items.";
        }
    }

    public function errors()
    {
        return $this->errors;
    }
}
