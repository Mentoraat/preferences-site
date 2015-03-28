<?=validation_errors(); ?>

<?=form_open('users/tryLogin/' . $class . '/' . $method); ?>

    <label for='netid'>Net ID:</label> <input name='netid' type='text' />
    <label for='password'>Password:</label> <input name='password' type='password' />

    <button type='submit'>Log in</button>
</form>
