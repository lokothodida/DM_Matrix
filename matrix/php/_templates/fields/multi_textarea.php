<span class="multi_textarea">
    <?php foreach ($textareas as $textarea) : ?>
        <label><?php echo $textarea->label; ?></label>
        <textarea
        class="text"
        name="post-<?php echo $textarea->name; ?>[]"
        <?php echo $textarea->properties; ?>
        ><?php echo $textarea->value; ?></textarea>
    <?php endforeach; ?>
</span>