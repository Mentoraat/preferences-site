<?php
if (isset($status) && $status)
{
    ?>
    <div class="alert alert-danger" role="alert">
        <span>The preference process has been closed.</span>
    </div>
    <?php
}
?>

<?php
if ($wasSuccess)
{
    ?>
    <div class="alert alert-success" role="alert">
        <span>Your preferences are updated.</span>
    </div>
    <?php
}
?>

<?php echo validation_errors(); ?>

<div id='netIDProvider' class="panel panel-primary">
    <div class="panel-heading">Suggestion list:</div>
    <div class="panel-body">
        <ul class="list-group">No suggestions.</ul>
    </div>
</div>

<?php echo form_open('preferences/update'); ?>

    <input class='hidden' type='text' name='userid' value='<?php echo isset($userid) ? $userid : set_value('userid'); ?>' />

    <div id='studentPreferences' class="panel panel-default">
        <div class="panel-heading">Student preferences</div>
        <span class='testDescription'>Fill in the <strong>Net ID's</strong> of the students you prefer to be in a group with. Use the suggestion list on the right to quickly find a students netid.</span>

        <div class="panel-body">
            <?php
            for ($i = 0; $i < MAXIMUM_NUMBER_OF_PREFERENCES; $i++)
            {
                $preferred = isset($preferences[$i]) ? $preferences[$i] : '';
                ?>
                <div class='input name input-group'>
                    <label for='names[<?php echo $i; ?>]' class="input-group-addon"><?php echo $i + 1; ?></label>
                    <input type='text' name='names[<?php echo $i; ?>]' class="form-control" value='<?php echo set_value('names[' . $i . ']', $preferred); ?>' />
                </div>
                <?php
            }
            ?>
        </div>
    </div>

    <div id='groupRoles' class="panel panel-default">
        <div class="panel-heading">Group role test</div>
        <span class='testDescription'>Fill in these fields with the results of <a href='https://www.123test.nl/groepsrollentest/' target='__blank'>this test</a>.</span>

        <div class="panel-body">
            <?php
            foreach (
                array(
                    "Bedrijfsman",
                    "Brononderzoeker",
                    "Plant",
                    "Monitor",
                    "Vormer",
                    "Voorzitter",
                    "Zorgdrager",
                    "Groepswerker",
                    "Specialist"
                ) as $role)
            {
                $name = str_replace(' ', '', $role);
                $roleValue = isset($roles[$name]) ? $roles[$name] : 0;

                ?>
                <div class='input role input-group'>
                    <label for='role[<?php echo $name; ?>]' class="input-group-addon"><?php echo $role; ?></label>
                    <input type='number' name='role[<?php echo $name; ?>]' class="form-control" value='<?php echo set_value('role[' . $name . ']', $roleValue); ?>' max='25' />
                </div>
                <?php
            }
            ?>
        </div>
    </div>

    <button type='submit' class="btn btn-primary">Update</button>
</form>
