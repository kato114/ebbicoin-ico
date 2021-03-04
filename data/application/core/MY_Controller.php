<?php

class MY_Controller extends CI_Controller
{
    
    function __contruct(){

        parent::__contruct();

    }

    protected function _loadView($template, $data = array())
    {
        
        $data['header']     = $this->load->view('common/header', '', TRUE);
        $data['mainView']   = $this->load->view($template, '', TRUE);
        $data['footer']     = $this->load->view('common/footer', '', TRUE);

        $this->load->view('mainView', $data);

    }

    protected function _response($response = array())
    {
        
        if( !empty( $response ) ){
            echo json_encode($response);
            die();
        } else {
            $result = array(
                'status' => ERROR_CODE,
                'message' => BAD_RESPONSE
            );
            $this->_response($result);
        }

    }

}
