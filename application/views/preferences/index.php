<?=validation_errors(); ?>

<?=form_open('preferences/update'); ?>

    <input class='hidden' type='text' name='userid' value='<?=isset($userid) ? $userid : set_value('userid'); ?>' />

    <?php
    for ($i = 0; $i < MAXIMUM_NUMBER_OF_PREFERENCES; $i++)
    {
        $preferred = isset($preferences[$i]) ? $preferences[$i] : '';
        ?>
        <input class='name' type='text' name='names[<?=$i; ?>]' value='<?=set_value('names[' . $i . ']', $preferred); ?>'/>
        <?php
    }
    ?>

    <button type='submit'>Update</button>
</form>
