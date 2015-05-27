<div class='center' id='input'>
    <?php echo validation_errors(); ?>

    <?php echo form_open('users/tryLogin/' . $class . '/' . $method); ?>

        <div class="input-group">
            <label for='netid' class="input-group-addon">Net ID:</label>
            <input name='netid' type='text' class="form-control"/>
        </div>

        <div class="input-group">
            <label for='password' class="input-group-addon">Password:</label>
            <input name='password' type='password' class="form-control"/>
        </div>

        <button type='submit' class="btn btn-primary">Log in</button>
    </form>

    <div id='register_message' class="panel">
        <h4>Don't have an account yet?</h4>
        <a href="<?=site_url('users/register'); ?>" class="btn btn-success">Register</a>
    </div>
</div>
