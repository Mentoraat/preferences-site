<?=validation_errors(); ?>

<?=form_open('users/tryRegister'); ?>

    <label for='userid'>Net ID:</label>
    <input name='userid' type='text' value='<?=isset($this->form_validation->error_array()['userid']) ? '' : set_value('userid'); ?>' />

    <label for='password'>Password:</label>
    <input name='password' type='password' />

    <label for='passconf'>Retype password:</label>
    <input name='passconf' type='password' />

    <label for='studentid'>Student ID:</label>
    <input name='studentid' type='number' value='<?=isset($this->form_validation->error_array()['studentid']) ? '' : set_value('studentid'); ?>'/>

    <button type='submit'>Register</button>
</form>
