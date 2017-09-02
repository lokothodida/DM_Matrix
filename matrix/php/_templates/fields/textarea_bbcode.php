<script language="javascript">
/* global jQuery, GSBBCodeSettings */
jQuery(function($) {
    $("#<?php echo $id; ?>").markItUp(GSBBCodeSettings);
});
</script>

<textarea <?php echo $properties; ?>><?php echo $value; ?></textarea>