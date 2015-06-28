<?php
if ($this->user->isAdmin())
{
    ?>
    Instructions:
    <ul>
        <li><a href="<?=site_url('admin'); ?>">Go to the admin panel</a> to open/close the registration.</li>
        <li><a href="<?=site_url('admin'); ?>">Go to the admin panel</a> to open/close the preference process.</li>
        <li><a href="<?=site_url('cluster'); ?>">Obtain the clustering output</a></li>
        <li>Run the clustering on a local machine.</li>
    </ul>
    <?php
}
else
{
    ?>
    Hello and welcome to the mentoraat site.<br />

    <?php
    if (!$this->user->isLoggedIn())
    {
        ?>
        You must log in in order to use the website.<br>
        If don't have an account yet, please register <a href="<?=site_url('users/register'); ?>">here</a><br>
        If do have an account, please log in <a href="<?=site_url('users/login'); ?>">here</a>
        <?php
    }
    else
    {
        ?>
        You can update your preferences <a href="<?=site_url('preferences'); ?>">here</a>
        <?php
    }
}
