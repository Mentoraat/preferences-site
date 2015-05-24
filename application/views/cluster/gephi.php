<?php
$students = $this->clustering->gephi();
?>
<h1>Nodes</h1>
<textarea>
Id,Label
<?php
foreach ($students['students'] as $student)
{
    echo $student->id . ",Student" . $student->id . "\n";
}
?>
</textarea>
<h1>Edges</h1>
<textarea>
Source,Target,Id,Label
<?php
foreach ($students['preferences'] as $i => $preference)
{
    echo $preference->studentid . "," . $preference->prefers_studentid . ",Directed," . $i . "," . $preference->order . "\n";
}
?>
</textarea>
