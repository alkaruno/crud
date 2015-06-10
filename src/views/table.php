<?php
/** @var Xplosio\Crud\Crud $crud */
/** @var Xplosio\Crud\Field[] $fields */
/** @var array $rows */
/** @var array $lang */
?>

<h1 class="page-header"><?php echo $this->title ?></h1>

<a href="?action=add"><button class="button button-primary"><?php echo $lang['buttons.add'] ?></button></a><br><br>

<table class="table table-striped">
    <thead>
        <tr>
            <?php foreach ($this->listFields as $key => $name): ?>
                <th><?php echo $this->fields[$name]->title; ?></th>
            <?php endforeach; ?>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($rows as $row): ?>
            <tr>
                <?php foreach ($this->listFields as $key => $name): ?>
                    <?php
                        $value = $row[$name];
                        if ($key === 0) {
                            $value = sprintf('<a href="%s?id=%d">%s</a>', $this->url, $row['id'], $value);
                        }
                    ?>
                    <td><?php echo $value; ?></td>
                <?php endforeach; ?>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>