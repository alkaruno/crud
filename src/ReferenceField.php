<?php

namespace Xplosio\Crud;

use Xplosio\PhpFramework\Db;

class ReferenceField extends Field
{
    private $table;
    private $options;

    public function table($table)
    {
        $this->table = $table;
        return $this;
    }

    public function getHumanValue($value, $form = false)
    {
        return $form ? $value : $this->getOptions()[$value];
    }

    public function getOptions()
    {
        if ($this->options === null) {
            $this->options = Db::getPairs('SELECT id, name FROM `' . $this->table . '`');
        }

        return $this->options;
    }
}
