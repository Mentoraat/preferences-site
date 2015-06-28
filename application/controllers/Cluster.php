<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cluster extends Admin_Controller {

    var $fileName = 'clustering/StudClust/Dpref.csv';

    /**
     * Shortcut to $this->output()
     *
     * @return $this->output
     */
    public function index()
    {
        $this->output();
    }

    /**
     * Output in matrix format the content of the preferences table.
     *
     * @return void
     */
    public function output()
    {
        $students = $this->clustering->generate();

        $output = '';

        $delimiter = ',';

        foreach ($students as $name => $preferences)
        {
            foreach ($preferences as $preference)
            {
                $output .= $preference . $delimiter;
            }

            $output = rtrim($output, $delimiter) . PHP_EOL;
        }

        file_put_contents("clustering/StudClust/Dpref.csv", $output);

        $roles = $this->clustering->generateRoles();

        $output = '';

        foreach ($roles as $student => $role)
        {
            $acceptedRoles = array(
                'Analyst',
                'Chairman',
                'Completer',
                'Driver',
                'Executive',
                'Expert',
                'Explorer',
                'Innovater',
                'TeamPlayer'
            );

            foreach ($acceptedRoles as $accepted)
            {
                if (array_key_exists($accepted, $role))
                {
                    $output .= $role[$accepted] . $delimiter;
                }
                else
                {
                    $output .= '-1' . $delimiter;
                }
            }

            $output = rtrim($output, $delimiter) . PHP_EOL;
        }

        file_put_contents("clustering/StudClust/Dbelbin.csv", $output);

        $this->load->page('cluster/output');
    }

    public function gephi()
    {
        $this->load->page('cluster/gephi');
    }

    public function download($fileName)
    {
        $fullFileName = 'clustering/StudClust/' . $fileName . '.csv';
        header("Pragma: public", true);
        header("Expires: 1"); // set expiration time
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header("Content-Disposition: attachment; filename=".$fileName . ".csv");
        header("Content-Transfer-Encoding: text");
        header("Content-Length: ".filesize($fullFileName));
        readfile($fullFileName);
    }

}
