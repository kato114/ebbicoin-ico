<?php

class Users extends MY_Controller
{
    
    public function __construct()
    {
        parent::__construct();
        $this->load->model('admin/user_model', 'this_model');
    }

    public function getUsers( $field, $dir )
    {
        $response = $this->this_model->getUsers($field, $dir);
        $this->_response($response);
    }

    public function getUser( $id )
    {
        $response = $this->this_model->getUser( $id );
        $this->_response($response);
    }

    public function add(){

        $_POST = (array) json_decode( file_get_contents( 'php://input' ) );

        if ( $this->form_validation->run() === FALSE ){

            $errors = array(
                'status' => ERROR_CODE,
                'message' => validation_errors( '<p class="text-danger">', '</p>' )
            );
            $this->_response( $errors );
            
        } else {
            $result = $this->this_model->add( $_POST );
            $this->_response( $result );
        }

    }

    public function changeStatus()
    {

        $_POST = (array) json_decode( file_get_contents( 'php://input' ) );

        if ( $this->form_validation->run() === FALSE ){

            $errors = array(
                'status' => ERROR_CODE,
                'message' => validation_errors( '<p class="text-danger">', '</p>' )
            );
            $this->_response( $errors );
            
        } else {
            $result = $this->this_model->changeStatus( $_POST );
            $this->_response( $result );
        }
    }

    public function edit(){

        $_POST = (array) json_decode( file_get_contents( 'php://input' ) );
        if ( $this->form_validation->run() === FALSE ){
           
            $errors = array(
                'status' => ERROR_CODE,
                'message' => validation_errors( '<p class="text-danger">', '</p>' ),
                'data' => 'hello'
            );
            $this->_response( $errors );
            
        } else {

            $result = $this->this_model->edit( $_POST );
            $this->_response( $result );
        }

    }

    public function addUserEbbiCoinBalance()
    {

        $_POST = (array) json_decode( file_get_contents( 'php://input' ) );

        if ( $this->form_validation->run() === FALSE ){

            $errors = array(
                'status' => ERROR_CODE,
                'message' => validation_errors( '<p class="text-danger">', '</p>' )
            );
            $this->_response( $errors );
            
        } else {
            $result = $this->this_model->addUserEbbiCoinBalance( $_POST );
            $this->_response( $result );
        }
    }

    public function balance()
    {
        $result = $this->this_model->balance( $_POST );
        $this->_response( $result );
    }

    public function transaction()
    {
        $_POST = (array) json_decode( file_get_contents( 'php://input' ) );
        $result = $this->this_model->transaction( $_POST );
        $this->_response( $result );
    }

    public function statics()
    {
        $result = $this->this_model->statics( $_POST );
        $this->_response( $result );
    }

    public function setOption()
    {
        $_POST = (array) json_decode( file_get_contents( 'php://input' ) );
        $result = $this->this_model->setOption( $_POST );
        $this->_response( $result );
    }
    
    public function getOption()
    {
        $_POST = (array) json_decode( file_get_contents( 'php://input' ) );
        $result = $this->this_model->getOption( $_POST );
        $this->_response( $result );
    }
}
