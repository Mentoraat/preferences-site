<h4>Number of students registered:</h4>
<?=$totalStudents; ?>

<h4>List of students that have no preferences. Total: <?=$total; ?></h4>

<?php
echo 'Showing: ' . count($students);
showAsList($students, function($student) {
    return $student->netid;
});
?>

<h4>Open/Close the registration process</h4>
<p>Currently the registration is: <?=$registrationStatus; ?></p>
<a href="<?=site_url('admin/registration/' . $newRegistrationStatus); ?>"><?=$newRegistrationStatus; ?> registration</a>

<h4>Open/Close the preferences process</h4>
<p>Currently the preferences process is: <?=$preferencesStatus; ?></p>
<a href="<?=site_url('admin/preferences/' . $newPreferenceStatus); ?>"><?=$newPreferenceStatus; ?> preference process</a>
