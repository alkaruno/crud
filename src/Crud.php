<?php

namespace Xplosio\Crud;

use Xplosio\PhpFramework\Db;

class Crud
{
    private $table;
    private $url;
    private $title;
    /*** @var Field[] */
    private $fields = [];
    private $listFields = [];
    private $listOrder = 'id DESC';
    private $callbacks = [];

    private function __construct()
    {
    }

    public static function create($options = [])
    {
        return new Crud();
    }

    public function table($table)
    {
        $this->table = $table;
        return $this;
    }

    public function url($url)
    {
        $this->url = $url;
        return $this;
    }

    public function title($title)
    {
        $this->title = $title;
        return $this;
    }

    public function fields()
    {
        foreach ($this->getArrayFunctionParam(func_get_args()) as $field) {
            $this->fields[$field->name] = $field;
        }

        return $this;
    }

    public function listFields()
    {
        $this->listFields = $this->getArrayFunctionParam(func_get_args());
        return $this;
    }

    public function listOrder($order)
    {
        $this->listOrder = $order;
        return $this;
    }

    /*
     * save.before, save.after
     */
    public function callback($action, $function)
    {
        $this->callbacks[$action] = $function;
        return $this;
    }

    public function render()
    {
        ob_start();

        $id = $this->input('id');
        $id = $id != null ? (int)$id : null;
        $method = $_SERVER['REQUEST_METHOD'];

        if ($method == 'POST') {
            if (isset($_POST['__delete']) && $id != null) {
                $this->deleteItem($id);
            }
            $this->validateAndSave($id);
        } else {
            if ($id != null || $this->input('action') == 'add') {
                $this->showForm($id);
            } else {
                $this->showList();
            }
        }

        return ob_get_clean();
    }

    private function showList()
    {
        $rows = Db::getRows(sprintf('SELECT * FROM `%s` ORDER BY %s', $this->table, $this->listOrder));
        foreach ($rows as &$row) {
            foreach ($this->listFields as $field) {
                $row[$field] = $this->fields[$field]->getHumanValue($row[$field]);
            }
        }

        $this->showView('table', ['rows' => $rows, 'listFields' => $this->listFields]);
    }

    private function showForm($id)
    {
        $data = $id != null ? Db::getRow('SELECT * FROM `' . $this->table . '` WHERE id = ?', $id) : [];

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
            $pairs[] = '`' . $field->name . '` = ?';
            $values[] = $field->getDatabaseValue($value);
        }

        Db::update(sprintf('%s `%s` SET %s %s',
            $id == null ? 'INSERT INTO' : 'UPDATE',
            $this->table,
            implode(',', $pairs),
            $id != null ? 'WHERE id = ' . $id : ''
        ), $values);

        header('Location: ' . $this->url);
        exit;
    }

    private function deleteItem($id)
    {
        Db::update('DELETE FROM `' . $this->table . '` WHERE id = ?', $id);

        header('Location: ' . $this->url);
        exit;
    }

    private function input($name, $default = null)
    {
        return isset($_GET[$name]) ? $_GET[$name] : $default;
    }

    private function showView($view, $data)
    {
        $data['fields'] = $this->fields;
        extract($data);
        include __DIR__ . '/views/' . $view . '.php';
    }

    private function getArrayFunctionParam($args)
    {
        return count($args) == 1 && is_array($args[0]) ? $args[0] : $args;
    }
}