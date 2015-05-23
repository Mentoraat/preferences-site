<?php
if ($status === 'closed')
{
    ?>
    <div class="form_error center">
        <span>The registration has been closed.</span>
    </div>
    <?php
}
?>

<?=validation_errors(); ?>

<?=form_open('users/tryRegister', array(
    'class' => 'center'
)); ?>

    <label for='netid'>Net ID:</label>
    <input name='netid' type='text' value='<?php echo isset($this->form_validation->error_array()['netid']) ? '' : set_value('netid'); ?>' />

    <label for='password'>Password:</label>
    <input name='password' type='password' />

    <label for='passconf'>Retype password:</label>
    <input name='passconf' type='password' />

    <label for='studentid'>Student ID:</label>
    <input name='studentid' type='number' value='<?php echo isset($this->form_validation->error_array()['studentid']) ? '' : set_value('studentid'); ?>'/>

    <button type='submit'>Register</button>
</form>
