<script>
/* global jQuery */
jQuery(function($) {
    $('.<?php echo $id; ?>').jqte();
});
</script>

<span class="multi_rte">
    <?php foreach ($textareas as $textarea) : ?>
        <label><?php echo $textarea->label; ?></label>
        <textarea
        class="text <?php echo $id; ?>"
        name="<?php echo $id; ?>[]"
        <?php echo $textarea->properties; ?>
        ><?php echo $textarea->value; ?></textarea>
    <?php endforeach; ?>
</span>