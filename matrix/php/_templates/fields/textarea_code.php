<style>
#frame_<?php echo $id; ?> {
    border:#c8c8c8 1px solid !important;
    border-radius:5px; -webkit-border-radius:5px; -moz-border-radius:5px;
}
</style>

<script type="text/javascript">
/* global editAreaLoader */
editAreaLoader.init({
    id: '<?php echo $id; ?>',
    start_highlight: true,
    allow_resize: "both",
    allow_toggle: true,
    word_wrap: true,
    language: "en",
    syntax: "php",
    replace_tab_by_spaces: 2,
});

editAreaLoader.iframe_css = "<style><?php echo $iframeCss; ?></style>";
</script>

<textarea class="text" <?php echo $properties; ?>><?php echo $value; ?></textarea>