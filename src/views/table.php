<?php
/** @var app\lib\Field[] $fields */
/** @var array $rows */
/** @var array $listFields */
?>

<a href="?action=add"><button class="button button-primary">Add</button></a><br><br>

<table class="table table-striped">
    <thead>
        <tr>
            <?php foreach ($listFields as $key => $name): ?>
                <th><?php echo $fields[$name]->title; ?></th>
            <?php endforeach; ?>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($rows as $row): ?>
            <tr>
                <?php foreach ($listFields as $key => $name): ?>
                    <?php
                        $value = $row[$name];
                        if ($key == 0) {
                            $value = sprintf('<a href="%s?id=%d">%s</a>', $this->url, $row['id'], $value);
                        }
                    ?>
                    <td><?php echo $value; ?></td>
                <?php endforeach; ?>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>