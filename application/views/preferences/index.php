<?php
if (isset($status) && $status === 'closed')
{
    ?>
    <div class="form_error center">
        <span>The preference process has been closed.</span>
    </div>
    <?php
}
?>

<div id='netIDProvider'>
    <h4>Suggestion list:</h4>
    <div>
        <ul>No suggestions.</ul>
    </div>
</div>

<?php echo validation_errors(); ?>

<?php echo form_open('preferences/update'); ?>

    <input class='hidden' type='text' name='userid' value='<?php echo isset($userid) ? $userid : set_value('userid'); ?>' />

    <div id='studentPreferences'>
        <h3>Student preferences</h3>

        <?php
        for ($i = 0; $i < MAXIMUM_NUMBER_OF_PREFERENCES; $i++)
        {
            $preferred = isset($preferences[$i]) ? $preferences[$i] : '';
            ?>
            <div class='name'>
                <label for='names[<?php echo $i; ?>]'><?php echo $i + 1; ?></label>
                <input class='name' type='text' name='names[<?php echo $i; ?>]' value='<?php echo set_value('names[' . $i . ']', $preferred); ?>' />
            </div>
            <?php
        }
        ?>
    </div>

    <div id='groupRoles'>
        <h3>Group role test</h3>
        <span class='testDescription'>Fill in these fields with the results of <a href='https://www.123test.com/team-roles-test/' target='__blank'>this test</a>.</span>

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
                <label for='role[<?php echo $name; ?>]'><?php echo $role; ?></label>
                <input class='role' type='number' name='role[<?php echo $name; ?>]' value='<?php echo set_value('role[' . $name . ']', $roleValue); ?>' min='1' max='100' />
            </div>
            <?php
        }
        ?>
    </div>

    <button type='submit'>Update</button>
</form>
