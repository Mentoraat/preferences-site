<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Model {

    /**
     * The user id of the current user.
     * @var integer
     */
    private $id = NULL;

    /**
     * The net id of the current user.
     * @var string
     */
    private $netid = NULL;

    /**
     * If the user is an admin
     * @var boolean
     */
    private $admin = NULL;

    /**
     * Create the user. Initializes fields if session exists.
     */
    public function __construct()
    {
        parent::__construct();

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

    public function getUserIdFromNetId($netId)
    {
        return $this->db->query('
            SELECT id
            FROM users
            WHERE netid = ' . $this->db->escape($netId)
        )->first_row()->id;
    }

    public function isAdmin()
    {
        if ($this->admin === NULL && $this->getUserId())
        {
            $admin = $this->db->query('
                SELECT 1
                FROM admins
                WHERE user_id = ' . $this->getUserId()
            )->first_row();

            $this->admin = isset($admin);
        }

        return $this->admin;
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
    }

    public function setFormValidationRegistration()
    {
  		$this->form_validation->set_rules(
  			'netid',
  			'Net ID',
  			array(
  				'required',
  				'strtolower',
  				'regex_match[/^\w+$/]',
  				array(
  					'notAlreadyRegistered',
  					array($this->user, 'notAlreadyRegistered')
  				)
  			),
  			array(
  				'regex_match' => 'Your NetId can only contain word characters',
  				'notAlreadyRegistered' => 'This Net ID is already registered. If this is not you, please contact an administrator.'
  			)
  		);

  		$this->form_validation->set_rules(
  			'password',
  			'Password',
  			'trim|required'
  		);

  		$this->form_validation->set_rules(
  			'passconf',
  			'Password Confirmation',
  			'trim|required|matches[password]'
  		);

  		$this->form_validation->set_rules(
  			'studentid',
  			'Student ID',
  			array(
  				'required',
  				'integer',
  				'greater_than[1000000]'
  			)
  		);

  		$this->form_validation->set_rules(
  			'email',
  			'E-mail address',
  			array(
  				'required',
  				'trim',
  				'valid_email'
  			)
  		);

  		$this->form_validation->set_rules(
  			'gender',
  			'Gender',
  			array(
  				'required',
  				array(
  					'isMaleOrFemale',
  					function($gender)
  					{
  						return in_array($gender, array('male', 'female'));
  					}
  				)
  			),
  			array(
  				'isMaleOrFemale' => 'Your gender must be male of female.'
  			)
  		);

  		$this->form_validation->set_rules(
  			'first',
  			'First study',
  			array(
  				'required',
  				array(
  					'isYesOrNo',
  					function($first)
  					{
  						return in_array($first, array('school', 'cs', 'other'));
  					}
  				)
  			),
  			array(
  				'isYesOrNo' => 'Your previous education should be high school, computer science or an other study.'
  			)
  		);

  		$this->form_validation->set_rules(
  			'english',
  			'English mentoraat',
  			array(
  				'required',
  				array(
  					'isYesOrNo',
  					function($english)
  					{
  						return in_array($english, array('yes', 'no'));
  					}
  				)
  			),
  			array(
  				'isYesOrNo' => 'Mentoraat is either in English or not.'
  			)
  		);
    }

    public function clear()
    {
        foreach (get_object_vars($this) as $field => $value)
        {
            $this->$field = NULL;
        }

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

        return isset($user) && password_verify($password, $user->password);
    }

}
