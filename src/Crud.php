<?php

namespace Xplosio\Crud;

use Xplosio\PhpFramework\Db;

class Crud
{
    private $table;
    private $url;
    private $title;
    private $options;
    /*** @var Field[] */
    private $fields = [];
    private $listFields = [];
    private $listOrder = 'id DESC';
    private $callbacks = [];

    const CALLBACK_BEFORE_SAVE = 'before.save';

    public function __construct($table, $url, $title)
    {
        $this->table = $table;
        $this->url = $url;
        $this->title = $title;
    }

    public function setOptions($options)
    {
        $this->options = $options;
        return $this;
    }

    public function addField(Field $field)
    {
        $this->fields[$field->name] = $field;
        return $this;
    }

    public function setListFields()
    {
        $this->listFields = $this->getArrayFunctionParam(func_get_args());
        return $this;
    }

    public function setListOrder($order)
    {
        $this->listOrder = $order;
        return $this;
    }

    public function addCallback($action, $function)
    {
        $this->callbacks[$action] = $function;
        return $this;
    }

    public function render()
    {
        ob_start();

        $id = $this->getInputParam('id');
        $id = $id !== null ? (int)$id : null;
        $method = $_SERVER['REQUEST_METHOD'];

        if ($method === 'POST') {
            if (array_key_exists('__delete', $_POST) && $id !== null) {
                $this->deleteItem($id);
            } else {
                $this->validateAndSave($id);
            }
        } else {
            if ($id !== null || $this->getInputParam('action') === 'add') {
                $this->showForm($id);
            } else {
                $this->showList();
            }
        }

        return ob_get_clean();
    }

    /* private */

    private function showList()
    {
        $rows = Db::getRows('SELECT * FROM `' . $this->table . '` ORDER BY ' . $this->listOrder);
        foreach ($rows as &$row) {
            foreach ($this->listFields as $field) {
                $row[$field] = $this->fields[$field]->getHumanValue($row[$field]);
            }
        }

        $this->showView('table', ['rows' => $rows, 'listFields' => $this->listFields]);
    }

    private function showForm($id)
    {
        $data = $id !== null ? Db::getRow('SELECT * FROM `' . $this->table . '` WHERE id = ?', $id) : [];

        foreach ($this->fields as $field) {
            if (!empty($data[$field->name])) {
                $data[$field->name] = $field->getHumanValue($data[$field->name], true);
            }
        }

        $this->showView('form', ['data' => $data]);
    }

    private function validateAndSave($id)
    {
        $pairs = $values = [];
        foreach ($this->fields as $field) {
            $value = !empty($_POST[$field->name]) ? trim($_POST[$field->name]) : null;
            $pairs[] = "`{$field->name}` = ?";
            $values[] = $field->getDatabaseValue($value);
        }

        Db::query(sprintf('%s `%s` SET %s %s',
            $id === null ? 'INSERT INTO' : 'UPDATE',
            $this->table,
            implode(',', $pairs),
            $id !== null ? 'WHERE id = ' . $id : ''
        ), $values);

        $id = $id === null ?: Db::getLastInsertId();

        header('Location: ' . $this->url);
        exit;
    }

    private function deleteItem($id)
    {
        Db::update('DELETE FROM `' . $this->table . '` WHERE id = ?', $id);

        header('Location: ' . $this->url);
        exit;
    }

    private function showView($view, $data)
    {
        extract($data);
        $lang = include(__DIR__ . '/l10n/' . $this->getOptionsParam('lang', 'en') . '.php');
        include __DIR__ . '/views/' . $view . '.php';
    }

    private function getArrayFunctionParam($args)
    {
        return count($args) === 1 && is_array($args[0]) ? $args[0] : $args;
    }

    private function getOptionsParam($name, $default = null)
    {
        $hasParam = is_array($this->options) && array_key_exists($name, $this->options);
        return $hasParam ? $this->options[$name] : $default;
    }

    private function getInputParam($name, $default = null)
    {
        return array_key_exists($name, $_GET) ? $_GET[$name] : $default;
    }
}
