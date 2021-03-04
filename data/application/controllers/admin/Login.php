<?php

class Login extends MY_Controller
{
    
    public function __construct()
    {
        parent::__construct();
        $this->load->model('admin/login_model', 'this_model');
    }

    public function adminLogin(){

        $_POST = (array) json_decode( file_get_contents( 'php://input' ) );

        if ( $this->form_validation->run() == FALSE ){

            $result = array(
                'status' => ERROR_CODE,
                'message' => validation_errors( '<p class="text-danger">', '</p>' )
            );

        } else {
            
            $result = $this->this_model->login( $_POST );

        }

        $this->_response( $result );

    }

    public function loginStatus()
    {
        $_POST = (array) json_decode( file_get_contents( 'php://input' ) );
        $result = $this->this_model->loginStatus( $_POST );
        $this->_response($result);
        
    }

    public function logout()
    {
        $_POST = (array) json_decode( file_get_contents( 'php://input' ) );
        $result = $this->this_model->logout( $_POST );
        $this->_response($result);
        
    }

    public function changePassword()
    {
        $_POST = (array) json_decode( file_get_contents( 'php://input' ) );
        $result = $this->this_model->changePassword( $_POST );
        $this->_response($result);
    }

}
