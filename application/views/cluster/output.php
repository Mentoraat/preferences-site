<?php
$students = $this->clustering->generate();
?>
<table>
    <thead>
        <tr>
            <th>Name</th>
        <?php
            foreach ($students as $student => $preferences)
            {
                echo '<th>' . $student . '</th>';
            }
        ?>
        </tr>
    </thead>
    <tbody>
        <?php
            foreach ($students as $student => $preferences)
            {
                echo '<tr>';

                echo '<td>' . $student . '</td>';

                foreach ($preferences as $prefers => $order)
                {
                    echo '<td>' . $order . '</td>';
                }

                echo '</tr>';
            }
        ?>
    </tbody>
</table>
