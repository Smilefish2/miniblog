<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Test extends MY_Controller {

	/**
	 * 默认控制器
	 *
	 */
	public function index()
	{
		$this->load->helper(array('form', 'url'));
		$this->load->view('test');//html5Upload:false
	}
}

/* End of file test.php */
/* Location: ./application/controllers/test.php */