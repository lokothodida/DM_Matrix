<select class="text" <?php echo $properties; ?>>
    <?php foreach ($records as $record) : ?>
        <option
            value="<?php echo $record->id; ?>"
            <?php echo $record->selected; ?>
        ><?php echo $record->value; ?></option>
    <?php endforeach; ?>
</select>