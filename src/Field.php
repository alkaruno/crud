<?php

namespace Xplosio\Crud;

class Field
{
    public $name;
    public $title;
    public $disabled = false;
    public $textarea = false;
    public $validation = [];

    public function __construct($name, $title)
    {
        $this->name = $name;
        $this->title = $title;
    }

    public function textarea()
    {
        $this->textarea = true;
        return $this;
    }

    public function disabled()
    {
        $this->disabled = true;
        return $this;
    }

    public function required($error = null)
    {
        $this->validation[] = ['required', $error];
        return $this;
    }

    public function regexp($regexp, $error = null)
    {
        $this->validation[] = ['regexp', $error, $regexp];
        return $this;
    }

    /** TODO возможно стоит поместить в Crud для экономии памяти и скорости */
    public function validate($value)
    {
        $handlers = [
            'required' => function ($value) {
                return !empty($value);
            },
            'regexp' => function ($value, $rule) {
                return preg_match('|' . $rule[1] . '|u', $value);
            }
        ];

        foreach ($this->validation as $rule) {

        }

        return null;
    }

    public function save($id, $value)
    {
        ;
    }

    public function getHumanValue($value, $form = false)
    {
        return $value;
    }

    public function getDatabaseValue($value)
    {
        return $value;
    }
}
