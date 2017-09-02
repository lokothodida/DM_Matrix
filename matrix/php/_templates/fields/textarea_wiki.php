<script language="javascript">
/* global jQuery, GSWikiSettings */
jQuery(function($) {
    $("#<?php echo $id; ?>").markItUp(GSWikiSettings);
});
</script>

<textarea <?php echo $properties; ?>><?php echo $value; ?></textarea>