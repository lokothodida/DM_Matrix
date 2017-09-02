<style>
div.<?php echo $id; ?> .jqte_editor {
    min-height: <?php echo $height; ?>;
}
</style>

<script type="text/javascript">
/* global jQuery */
jQuery(function($) {
    $('textarea.<?php echo $id; ?>').jqte({
        placeholder: <?php echo $placeholder; ?>
    });
});
</script>

<div class="<?php echo $id; ?>">
    <textarea class="<?php echo $id; ?>" <?php echo $properties; ?>><?php echo $value; ?></textarea>
</div>