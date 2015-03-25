<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Model {

    var $netid;

    function __construct()
    {
        $user = $this->db->query('
            SELECT *
            FROM users
            WHERE id = 1
        ')->first_row();

        $this->netid = $user->netid;
    }

    public function exists_by_netid($netid)
    {
        if (is_array($netid))
        {
            foreach ($netid as $single_net_id)
            {
                if (!$this->exists_by_netid($single_net_id))
                {
                    return FALSE;
                }
            }

            return TRUE;
        }

        $value = $this->db->query('
            SELECT 1
            FROM users
            WHERE netid = ' . $this->db->escape($netid)
        )->first_row();

        return isset($value);
  }
}
