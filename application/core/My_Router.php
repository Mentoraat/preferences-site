<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class My_Router extends CI_Router {

    public function redirectBack($page)
    {
        if ($this->class === 'user' && $this->method === 'login')
        {
            return redirect($page);
        }

        return redirect($page . '/' . $this->class . '/' . $this->method);
    }
}
