<nav class="navbar navbar-primary">
    <div id='navigation' class='container'>
        <div class='right'>
            <?php
            if ($this->user->isLoggedIn())
            {
                ?>
                <a class="navbar-brand" href='<?php echo site_url('users/logout'); ?>'>Log out</a>
                <?php
            }
            else {
                ?>
                <a class="navbar-brand" href='<?php echo site_url('users/login'); ?>'>Log in</a>
                <?php
            }
            ?>
        </div>

        <a class="navbar-brand" href='<?php echo site_url(); ?>'>Home</a>
        <a class="navbar-brand" href='<?php echo site_url('preferences'); ?>'>Preferences</a>
    </div>
</nav>
