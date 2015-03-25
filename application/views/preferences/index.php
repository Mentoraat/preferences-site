<?=validation_errors(); ?>

<?=form_open('preferences/update'); ?>

    <input class='hidden' name='netid' value='<?=isset($netid) ? $netid : set_value('netid'); ?>' />

    <?php
    for ($i = 0; $i < 10; $i++)
    {
        ?>
        <input class='name' name='names[<?=$i; ?>]' value='<?=set_value('names[' . $i . ']'); ?>'/>
        <?php
    }
    ?>

    <button type='submit'>Update</button>
</form>
