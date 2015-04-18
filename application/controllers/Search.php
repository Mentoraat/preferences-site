<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Search extends AJAX_Controller {

    public function byNetId()
    {
        $this->form_validation->set_rules(
			'value',
			'Value',
			'required|min_length[3]'
		);

        if ($this->form_validation->run() === FALSE)
        {
            $this->fail();
        }
        else
        {
            $this->succeed(array(
                'netids' => array_map(
                        function($netid) {
                            return $netid->netid;
                        },
                        $this->student->like($this->input->post('value'))
                    )
            ));
        }
    }
}
