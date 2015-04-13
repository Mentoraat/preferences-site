<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Clustering extends CI_Model {

    public function generate()
    {
        $preferences = $this->db->query("
            SELECT
                allstudents.id,
                allstudents.netid,
                otherstudents.netid AS prefers_studentid,
                coalesce((
                    SELECT `order`
                    FROM preferences
                    WHERE preferences.studentid = allstudents.netid
                        AND preferences.prefers_studentid = otherstudents.netid
                ), 0) AS 'order'
            FROM students
                AS allstudents
            JOIN students
                AS otherstudents
            ORDER BY allstudents.id, otherstudents.id
	    ")->result();

        return array_reduce($preferences, function(&$result, $prefered) {
            $result[$prefered->netid][$prefered->prefers_studentid] = $prefered->order;
            return $result;
        }, array());
    }

}
