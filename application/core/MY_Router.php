<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class My_Router extends CI_Router {

    public function __construct()
    {
		  parent::__construct();
    }

    /**
     * Redirect to a page with first 2 parameters the current class and method.
     * Is mostly used in controller User->login().
     *
     * @param string $page The page to load with redirect.
     */
    public function redirectBack($page)
    {
        if ($this->class === 'user' && $this->method === 'login')
        {
            return redirect($page);
        }

        return redirect($page . '/' . $this->class . '/' . $this->method);
    }
}
