<script language="javascript">
/* global jQuery, GSMarkDownSettings */
jQuery(function($) {
    $("#<?php echo $id; ?>").markItUp(GSMarkDownSettings);
});
</script>

<textarea <?php echo $properties; ?>><?php echo $value; ?></textarea>