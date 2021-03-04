<?php

class Transaction extends MY_Controller
{
    
    function __construct(){
        parent::__construct();
        $this->load->model( 'transaction_model', 'this_model' );
    }

    public function getStageTransactions($stage)
    {
        $resposne = $this->this_model->getStageTransactions( $stage );
        $this->_response( $resposne );
    }

}