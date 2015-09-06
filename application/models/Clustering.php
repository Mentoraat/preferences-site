<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Clustering extends CI_Model {

    /**
     * Generate an array of all students with their preferences.
     * Does not contain non-saved preferences.
     *
     * @return array The array with all saved preferences.
     */
    public function generate()
    {
        $preferences = $this->db->query("
            SELECT
                allstudents.id,
                allstudents.netid,
                otherstudents.netid AS prefers_studentid,
                coalesce((
                    SELECT 6 - `order`
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

    public function generateRoles()
    {
        $roles = $this->db->query("
            SELECT
		students.id,
                students.netid,
                role,
                percentage
            FROM students
            LEFT OUTER JOIN team_roles
                ON students.netid = team_roles.netid
	    ORDER BY students.id
        ")->result();

        return array_reduce($roles, function(&$result, $role) {
            $result[$role->netid][$role->role] = $role->percentage;
            return $result;
        }, array());
    }

    public function gephi()
    {
        $students = $this->db->query("
            SELECT id
            FROM students
            ORDER BY id
	    ")->result();

        $preferences = $this->db->query("
            SELECT
                fromstudent.id AS studentid,
                tostudent.id AS prefers_studentid,
                6 - preferences.`order`
            FROM preferences
            JOIN students
                AS fromstudent
                ON fromstudent.netid = preferences.studentid
            JOIN students
                AS tostudent
                ON tostudent.netid = preferences.prefers_studentid
        ")->result();

        return array(
            'students' => $students,
            'preferences' => $preferences
        );
    }

}
