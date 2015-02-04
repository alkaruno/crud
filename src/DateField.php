<?php

namespace Xplosio\Crud;

class DateField extends Field
{
    private $format = 'd.m.Y';

    public function format($format)
    {
        $this->format = $format;
        return $this;
    }

    public function getHumanValue($value, $form = false)
    {
        return \DateTime::createFromFormat('Y-m-d', $value)->format($this->format);
    }

    public function getDatabaseValue($value)
    {
        return \DateTime::createFromFormat($this->format, $value)->format('Y-m-d');
    }
}
