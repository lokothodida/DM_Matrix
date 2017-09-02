<script type="text/javascript">
/* global jQuery */
jQuery(function($) {
    $("#<?php echo $id; ?>").tagsInput();
});
</script>

<textarea <?php echo $properties; ?>><?php echo $value; ?></textarea>