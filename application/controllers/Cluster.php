<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cluster extends Admin_Controller {

    //var $fileName = 'clustering/StudClust/Dpref.csv';
    //var $directoryName = 'clustering'.DIRECTORY_SEPARATOR;//."StudClust".DIRECTORY_SEPARATOR;

    /**
     * Shortcut to $this->output()
     *
     * @return $this->output
     */
    public function index()
    {
        $this->output();
    }

    private function generateClustering($students)
    {
      $output = '';

      $delimiter = ',';

      foreach ($students as $name => $preferences)
    	{
    	    $output .= rtrim($name, ' ') . ' ';
    	}

    	$output = rtrim($output, ' ') . PHP_EOL;

      foreach ($students as $name => $preferences)
      {
  	      foreach ($preferences as $prefers => $preference)
          {
              $output .= $preference . $delimiter;
          }

          $output = rtrim($output, $delimiter) . PHP_EOL;
      }

      return $output;
    }

    /**
     * Output in matrix format the content of the preferences table.
     *
     * @return void
     */
    public function output()
    {
	      $directory = APPPATH.'clustering'.DIRECTORY_SEPARATOR.'StudClust'.DIRECTORY_SEPARATOR;
        $students = $this->clustering->generate();

        $output = $this->generateClustering($students);

        file_put_contents($directory."Dpref.csv", $output);

        $roles = $this->clustering->generateRoles();

        $this->generateBelbin($roles, $directory, '');

        $this->load->page('cluster/output');
    }

    private function generateBelbin($roles, $directory, $language)
    {
      $output = '';
      $delimiter = ',';

      $acceptedRoles = array(
        "Bedrijfsman",
        "Brononderzoeker",
        "Plant",
        "Monitor",
        "Vormer",
        "Voorzitter",
        "Zorgdrager",
        "Groepswerker",
        "Specialist"
      );

      foreach ($acceptedRoles as $role)
      {
        $output .= $role . ' ';
      }

      $output = rtrim($output, ' ') . PHP_EOL;

      foreach ($roles as $student => $role)
      {
          $max = max($role);

          foreach ($acceptedRoles as $accepted)
          {
              if (array_key_exists($accepted, $role) && $role[$accepted] > 0)
              {
                  $output .= (($role[$accepted] === $max) + 1) . $delimiter;
              }
              else
              {
                  $output .= '0' . $delimiter;
              }
          }

          $output = rtrim($output, $delimiter) . PHP_EOL;
      }

      file_put_contents($directory."Dbelbin" . $language . ".csv", $output);
    }

    public function gephi()
    {
        $this->load->page('cluster/gephi');
    }

    public function download($fileName)
    {
        $fullFileName = APPPATH.'clustering/' . $fileName . '.csv';
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
