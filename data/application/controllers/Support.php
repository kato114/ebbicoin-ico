<?php

class Support extends MY_Controller
{
    
    function __construct()
    {
        parent::__construct();
        $this->load->model('support_model', 'this_model');
    }

    public function index( $user_id )
    {
        $result = $this->this_model->ticketList( $user_id );
        $this->_response( $result );
    }

    public function getTickets()
    {
        $result = $this->this_model->getTickets();
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

    public function view( $ticket_id )
    {
        $result = $this->this_model->getTicket( $ticket_id );
        $this->_response( $result );
    }

    public function addComment()
    {
        $_POST = (array) json_decode( file_get_contents( 'php://input' ) );

        if ( $this->form_validation->run() == FALSE ){

            $result = array(
                'status' => ERROR_CODE,
                'message' => validation_errors( '<p class="text-danger">', '</p>' )
            );

        } else {
            
            $result = $this->this_model->addComment( $_POST );

        }

        $this->_response( $result );
    }

    public function closeTicket( $user_id )
    {
        $result = $this->this_model->closeTicket( $user_id );
        $this->_response( $result );
    }

}
