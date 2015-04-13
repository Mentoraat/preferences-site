<?=validation_errors(); ?>

<?=form_open('preferences/update'); ?>

    <input class='hidden' type='text' name='userid' value='<?=isset($userid) ? $userid : set_value('userid'); ?>' />

    <?php
    for ($i = 0; $i < MAXIMUM_NUMBER_OF_PREFERENCES; $i++)
    {
        $preferred = isset($preferences[$i]) ? $preferences[$i] : '';
        ?>
        <div class='name'>
            <label for='names[<?=$i; ?>]'><?=$i + 1; ?></label>
            <input class='name' type='text' name='names[<?=$i; ?>]' value='<?=set_value('names[' . $i . ']', $preferred); ?>' />
        </div>
        <?php
    }
    ?>

    <span class='testDescription'>Fill in the below fields using the results of <a href='https://www.123test.com/team-roles-test/'>this test</a>.</span>

    <?php
    foreach (
        array(
            'Analyst',
            'Chairman',
            'Completer',
            'Driver',
            'Executive',
            'Expert',
            'Explorer',
            'Innovater',
            'Team Player'
        ) as $role)
    {
        $name = str_replace(' ', '', $role);
        $roleValue = isset($roles[$name]) ? $roles[$name] : 0;
        
        ?>
        <div class='role'>
            <label for='role[<?=$name; ?>]'><?=$role; ?></label>
            <input class='role' type='number' name='role[<?=$name; ?>]' value='<?=set_value('role[' . $name . ']', $roleValue); ?>' min='1' max='100' />
        </div>
        <?php
    }
    ?>

    <button type='submit'>Update</button>
</form>
