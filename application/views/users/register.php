<?php
if ($status === 'closed')
{
    ?>
    <div class="form_error center">
        <span>The registration has been closed.</span>
    </div>
    <?php
}

$errors = $this->form_validation->error_array();
?>

<div class='center' id='register'>
    <?=validation_errors(); ?>

    <?=form_open('users/tryRegister'); ?>

        <div class="input-group">
            <label for='netid' class="input-group-addon">Net ID:</label>
            <input name='netid' type='text' class="form-control" value='<?= (isset($errors['netid']) ? '' : set_value('netid')); ?>' />
        </div>

        <div class="input-group">
            <label for='password' class="input-group-addon">Password:</label>
            <input name='password' type='password' class="form-control" />
        </div>

        <div class="input-group">
            <label for='passconf' class="input-group-addon">Retype password:</label>
            <input name='passconf' type='password' class="form-control" />
        </div>

        <div class="input-group">
            <label for='studentid' class="input-group-addon">Student ID:</label>
            <input name='studentid' type='number' class="form-control" value='<?= (isset($errors['studentid']) ? '' : set_value('studentid')); ?>'/>
        </div>

        <div class="input-group">
            <label for='email' class="input-group-addon">E-mail:</label>
            <input name='email' type='email' class="form-control" value='<?= (isset($errors['email']) ? '' : set_value('email')); ?>'/>
        </div>

        <div class="input-group gender">
            <span class="input-group-addon">Gender:</span>
            <div class="form-control">
                <div class="labels">
                    <label for='gender'>Male</label>
                    <label for='gender'>Female</label>
                </div>
                <div class="radios">
                    <input name='gender' type='radio' value="male" <?= ((isset($errors['gender']) || set_value('gender') !== 'male') ? '' : 'checked'); ?>/>
                    <input name='gender' type='radio' value="female" <?= ((isset($errors['gender']) || set_value('gender') !== 'female') ? '' : 'checked'); ?>/>
                </div>
            </div>
        </div>

        <div class="input-group first-study">
            <span class="input-group-addon">First study:</span>
            <div class="form-control">
                <div class="labels">
                    <label for='first'>Yes</label>
                    <label for='first'>No</label>
                </div>
                <div class="radios">
                    <input name='first' type='radio' value="yes" <?= ((isset($errors['first']) || set_value('first') !== 'yes') ? '' : 'checked'); ?>/>
                    <input name='first' type='radio' value="no" <?= ((isset($errors['first']) || set_value('first') !== 'no') ? '' : 'checked'); ?>/>
                </div>
            </div>
        </div>

        <button type='submit' class="btn btn-success">Register</button>
    </form>
</div>
