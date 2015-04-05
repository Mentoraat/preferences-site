<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Model {

    /**
     * The user id of the current user.
     * @var integer
     */
    private $id;

    /**
     * The net id of the current user.
     * @var string
     */
    private $netid;

    /**
     * Create the user. Initializes fields if session exists.
     */
    public function __construct()
    {
        parent::__construct();

        $id = NULL;
        $netid = NULL;

        if ($this->session->has_userdata('userid'))
        {
            $this->id = $this->session->userid;
            $this->netid = $this->session->netid;
        }
    }

    /**
     * Get the user id of the current user.
     *
     * @return integer The user ID.
     */
    public function getUserId()
    {
        return $this->id;
    }

    /**
     * Get the net id of the current user.
     *
     * @return string The net ID.
     */
    public function getNetId()
    {
        return $this->netid;
    }

    /**
     * Check whether the provided user id is the current user.
     *
     * @param integer $userid The user id to check for.
     * @return boolean If the specified user id is the current user.
     */
    public function isCurrentUser($userid)
    {
        return $this->getUserId() === $userid;
    }

    /**
     * Check whether the provided net id is of the current user.
     *
     * @param string $userid The net id to check for.
     * @return boolean If the specified net id is of the current user.
     */
    public function isCurrentNetid($netid)
    {
        return $this->getNetId() === $netid;
    }

    /**
     * Check if the current user is logged in.
     *
     * @return boolean If the current user is logged in.
     */
    public function isLoggedIn()
    {
        return $this->id != NULL;
    }

    /**
     * Check whether the provided netid already exists in the database.
     *
     * @param integer $netid The netid to check
     * @return boolean If the netid already exists in the database.
     */
    public function alreadyRegistered($netid)
    {
        $value = $this->db->query('
            SELECT 1
            FROM users
            WHERE netid = ' . $this->db->escape($netid)
        )->first_row();

        return isset($value);
    }

    /**
     * Inverted boolean of $this->alreadyRegistered($netid).
     *
     * @param boolean $netid The invertion of $this->alreadyRegistered($netid).
     * @return void
     */
    public function notAlreadyRegistered($netid)
    {
        return !$this->alreadyRegistered($netid);
    }

    /**
     * Register a specific user with netid and password.
     *
     * @param  integer $netid The netid of the user to be registered.
     * @param  string $password The hashed password of the user.
     * @return void
     */
    public function register($netid, $password)
    {
        $this->db->query('
        INSERT INTO users (netid, password)
        VALUES (
            ' . $this->db->escape($netid) . ',
            ' . $this->db->escape($password) . '
            )
        ');

        $this->setUser($netid);
    }

    public function clear()
    {
        $this->id = NULL;
        $this->netid = NULL;
        $this->session->sess_destroy();
    }

    /**
     * Set the current user to the provided netid and update the session.
     *
     * @param integer $netid The netid of the current user.
     * @return void
     */
    public function setUser($netid)
    {
        $user = $this->db->query('
            SELECT id, netid
            FROM users
            WHERE netid = ' . $this->db->escape($netid)
        )->first_row();

        $this->id = $user->id;
        $this->netid = $user->netid;

        $data = array(
            'userid' => $this->id,
            'netid' => $this->netid
        );

        $this->session->set_userdata($data);
    }

    /**
     * Check whether the password in the post array is of the same user
     * as the netid in the post array.
     *
     * @return boolean If the password matches the password from the database.
     */
    public function passwordMatches()
    {
        $netid = $this->input->post('netid');
        $password = $this->input->post('password');

        $user = $this->db->query('
            SELECT password
            FROM users
            WHERE netid = ' . $this->db->escape($netid)
        )->first_row();

        return password_verify($password, $user->password);
    }

}
