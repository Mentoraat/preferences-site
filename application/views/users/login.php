<?php echo validation_errors(); ?>

<?php echo form_open('users/tryLogin/' . $class . '/' . $method); ?>

    <div>
        <label for='netid'>Net ID:</label>
        <input name='netid' type='text' />
    </div>

    <div>
        <label for='password'>Password:</label>
        <input name='password' type='password' />
    </div>

    <button type='submit'>Log in</button>
</form>
