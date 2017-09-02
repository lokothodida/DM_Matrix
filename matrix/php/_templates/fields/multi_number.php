<span class="multi_number">
    <?php foreach ($inputs as $input) : ?>
        <label><?php echo $input->label; ?></label>
        <input
            type="number"
            value="<?php echo $input->value; ?>"
            style="margin-bottom: 4px;"
            name="post-<?php echo $input->name; ?>[]"
            <?php echo $input->properties; ?>
            class="text textmulti"
            value="<?php echo $input->value; ?>"
            placeholder="<?php echo $input->placeholder; ?>"
            <?php echo $input->readonly; ?>
            <?php echo $input->required; ?>
            pattern="<?php echo $input->validation; ?>"
        />
    <?php endforeach; ?>
</span>