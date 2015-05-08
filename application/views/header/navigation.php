<nav>
    <div id='navigation' class='container'>
        <div class='right'>
            <?php
            if ($this->user->isLoggedIn())
            {
                ?>
                <a href='<?php echo site_url('users/logout'); ?>'>Log out</a>
                <?php
            }
            else {
                ?>
                <a href='<?php echo site_url('users/login'); ?>'>Log in</a>
                <?php
            }
            ?>
        </div>

        <a href='<?php echo site_url(); ?>'>Home</a>
        <a href='<?php echo site_url('preferences'); ?>'>Preferences</a>
    </div>
</nav>
