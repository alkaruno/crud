<?php
/** @var app\lib\Field[] $fields */
/** @var array $data */
?>
<form action="" method="post" enctype="multipart/form-data" class="form">
    <?php foreach ($fields as $field): ?>
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
            <?php if ($field instanceof \app\lib\ReferenceField) { ?>
                <select name="<?php echo $name; ?>" id="<?php echo $id; ?>" class="select">
                    <option value=""></option>
                    <?php foreach ($field->getOptions() as $k => $v) { ?>
                        <option value="<?php echo $k ?>"<?php if ($k == $value) echo ' selected="selected"'; ?>><?php echo $v ?></option>
                    <?php } ?>
                </select>
            <?php } elseif ($field->textarea) { ?>
                <textarea name="<?php echo $name ?>" id="<?php echo $id; ?>" class="textarea" rows="10"><?php echo $value; ?></textarea>
            <?php } else { ?>
                <input type="text" name="<?php echo $name ?>" value="<?php echo $value; ?>" id="<?php echo $id; ?>" class="text"/>
            <?php } ?>
        </div>
    <?php endforeach; ?>
    <div class="buttons">
        <button type="submit" class="button button-primary">Save</button>
        <button type="submit" name="__delete" class="button button-danger">Delete</button>
    </div>
</form>