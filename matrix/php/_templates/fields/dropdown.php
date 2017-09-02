<select class="text" <?php echo $properties; ?>>
    <?php foreach ($options as $key => $option) : ?>
        <option
            value="<?php echo $key; ?>"
            <?php echo $option->selected; ?>
            ><?php echo $option->value; ?></option>
    <?php endforeach; ?>
</select>