<select class="text" <?php echo $properties; ?>>
    <?php foreach ($pages as $slug => $properties) : ?>
        <option
            value="<?php echo $slug; ?>"
            <?php if ($slug == $value) echo 'selected="selected"'; ?>
        ><?php echo $properties['title']; ?></option>
    <?php endforeach; ?>
</select>