<?php

use Xplosio\Crud\ImageField;
use Xplosio\Crud\ReferenceField;

/** @var Xplosio\Crud\Field[] $fields */
/** @var array $data */
/** @var array $lang */

?>

<h1 class="page-header">
    <a href="<?php echo $this->url ?>"><?php echo $this->title; ?></a> /
    <?php echo empty($data) ? 'Add' : 'Edit' ?>
</h1>

<form action="" method="post" enctype="multipart/form-data" class="form">
    <?php foreach ($this->fields as $field): ?>
        <?php

        $value = '';
        if (isset($_POST[$field->name])) {
            $value = $_POST[$field->name];
        } elseif (isset($data[$field->name])) {
            $value = $data[$field->name];
        }

        $name = $field->name;
        $id = 'input-' . $name;

        ?>
        <div class="field-group">
            <label for="<?php echo $id; ?>" class="label"><?php echo $field->title ?></label>
            <?php if ($field instanceof ReferenceField) { ?>
                <select name="<?php echo $name; ?>" id="<?php echo $id; ?>" class="select">
                    <option value=""></option>
                    <?php foreach ($field->getOptions() as $k => $v) { ?>
                        <option value="<?php echo $k ?>"<?php if ($k == $value) echo ' selected="selected"'; ?>><?php echo $v ?></option>
                    <?php } ?>
                </select>
            <?php } elseif ($field instanceof ImageField) { ?>
                <?php if (!empty($value)) { ?>
                    <div>
                        <img src="/<?php echo $value ?>" style="max-width: 300px !important;"/>
                    </div>
                <?php } ?>
                <input type="hidden" name="<?php echo $name; ?>" value="<?php echo $value ?>"/>
                <input type="file" name="<?php echo $name; ?>" id="<?php echo $id; ?>" class="file">
            <?php } elseif ($field->textarea) { ?>
                <textarea name="<?php echo $name ?>" id="<?php echo $id; ?>" class="textarea" rows="10"><?php echo $value; ?></textarea>
            <?php } else { ?>
                <input type="text" name="<?php echo $name ?>" value="<?php echo $value; ?>" id="<?php echo $id; ?>" class="text"/>
            <?php } ?>
        </div>
    <?php endforeach; ?>
    <div class="buttons">
        <button type="submit" class="button button-primary"><?php echo $lang['buttons.save'] ?></button>
        <button type="submit" name="__delete" class="button button-danger"><?php echo $lang['buttons.delete'] ?></button>
    </div>
</form>