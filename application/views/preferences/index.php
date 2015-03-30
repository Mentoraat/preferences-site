<?=validation_errors(); ?>

<?=form_open('preferences/update'); ?>

    <input class='hidden' type='text' name='userid' value='<?=isset($userid) ? $userid : set_value('userid'); ?>' />

    <?php
    for ($i = 0; $i < 2; $i++)
    {
        ?>
        <input class='name' type='text' name='names[<?=$i; ?>]' value='<?=set_value('names[' . $i . ']'); ?>'/>
        <?php
    }
    ?>

    <button type='submit'>Update</button>
</form>
