<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cluster extends Admin_Controller {

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

        foreach ($students as $name => $preferences)
        {
            $output .= $name . ':';

            foreach ($preferences as $preference)
            {
                $output .= $preference . ',';
            }

            $output = rtrim($output, ',') . PHP_EOL;
        }

        file_put_contents('clustering/StudClust/preferences.csv', $output);

        $this->load->page('cluster/output');
    }

    public function gephi()
    {
        $this->load->page('cluster/gephi');
    }

    public function download()
    {
        $file = 'clustering/StudClust/preferences.csv';
        header("Pragma: public", true);
        header("Expires: 0"); // set expiration time
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header("Content-Disposition: attachment; filename=".basename($file));
        header("Content-Transfer-Encoding: binary");
        header("Content-Length: ".filesize($file));
    }

}
