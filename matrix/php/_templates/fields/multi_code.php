<script>
    <?php foreach ($textareas as $index => $textarea) : ?>
    editAreaLoader.init({
        id: '<?php echo $id; ?>_<?php echo $index; ?>',
        start_highlight: true,
        allow_resize: "both",
        allow_toggle: true,
        word_wrap: true,
        language: "en",
        syntax: "php"
    });
    <?php endforeach; ?>
</script>

<span class="multi_code">
    <?php foreach ($textareas as $index => $textarea) : ?>
    <label><?php echo $textarea->label; ?></label>
    <textarea
        class="text"
        id="<?php echo $id; ?>_<?php echo $index; ?>"
        name="<?php echo $id; ?>[]"
        <?php echo $textarea->properties; ?>
    ><?php echo $textarea->value; ?></textarea>
    <?php endforeach; ?>
</span>