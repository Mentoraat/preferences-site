<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Failedlogin extends CI_Model {

    /**
     * Get how many subsequent times the credentials for this netid were invalid.
     * @param  string $netid The net id to check for.
     * @return integer        The number of subsequent invalid credentials supplied.
     */
    private function times($netid)
    {
        $times = $this->db->query('
        SELECT times
        FROM failedlogins
        WHERE netid = ' . $this->db->escape($netid)
        )->first_row();

        return $times->times;
    }

    /**
     * Check if the netid hasn't been providing invalid credentials too
     * many times.
     * @param string $netid The net id to check for.
     */
    public function notFailedToMany($netid)
    {
        return $this->times($netid) <= MAXIMUM_NUMBER_OF_FAILED_LOGINS;
    }

    /**
     * Increment the number of failed logins for the specified netid.
     * @param  string $netid The netid that failed to login
     * @return void
     */
    public function increment($netid)
    {
        $alreadyFailed = $this->db->query('
        SELECT 1
        FROM failedlogins
        WHERE netid = ' . $this->db->escape($netid)
        )->first_row();

        if (isset($alreadyFailed))
        {
            $this->db->query('
            UPDATE failedlogins
            SET times = times + 1
            WHERE netid = ' . $this->db->escape($netid)
            );
        }
        else
        {
            $this->db->query('
            INSERT INTO failedlogins (netid, times)
            VALUES (' . $this->db->escape($netid) . ',1)'
            );
        }
    }

    /**
     * Reset the number of failed logins for the specified netid.
     * This must only be done when a login for the netid was succesful.
     * @param string $netid The netid that succeeded to log in.
     * @return void
     */
    public function reset($netid)
    {
        $this->db->query('
        DELETE FROM failedlogins
        WHERE netid = ' . $this->db->escape($netid)
        );
    }
}
