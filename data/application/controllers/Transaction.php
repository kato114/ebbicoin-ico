<?php

class Transaction extends MY_Controller
{
    
    function __construct(){
        parent::__construct();
        $this->load->model( 'transaction_model', 'this_model' );
    }

    public function index()
    {
        $_POST = (array) json_decode( file_get_contents( 'php://input' ) );

        if ( $this->form_validation->run() == FALSE ){

            $result = array(
                'status' => ERROR_CODE,
                'message' => validation_errors( '<p class="text-danger">', '</p>' )
            );

        } else {
            
            $result = $this->this_model->getData( $_POST );

        }

        $this->_response( $result );
    }

    public function add()
    {

        $_POST = (array) json_decode( file_get_contents( 'php://input' ) );

        if ( $this->form_validation->run() == FALSE ){

            $result = array(
                'status' => ERROR_CODE,
                'message' => validation_errors( '<p class="text-danger">', '</p>' )
            );

        } else {
            
            $result = $this->this_model->add( $_POST );

        }

        $this->_response( $result );

    }

    public function buyCoin()
    {

        $_POST = (array) json_decode( file_get_contents( 'php://input' ) );

        if ( $this->form_validation->run() == FALSE ){

            $result = array(
                'status' => ERROR_CODE,
                'message' => validation_errors( '<p class="text-danger">', '</p>' )
            );

        } else {
            
            $result = $this->this_model->buyCoin( $_POST );

        }

        $this->_response( $result );

    }

}