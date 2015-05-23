<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ConfigKey extends CI_Model {

    function get($key)
    {
        $value = $this->db->query('
        SELECT value
        FROM config
        WHERE `key` = ' . $this->db->escape($key) . '
        ')->row();

        return $value->value;
    }

}
