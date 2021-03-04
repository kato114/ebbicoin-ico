<?php

class Home extends MY_Controller
{
    
    function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        
    }
    
    public function getData()
    {

        $this->load->model( 'user_model' );
        $this->load->model( 'transaction_model', 'transactions' );
        $this->load->model( 'option_model', 'options' );
        
        $data['users'] = $this->user_model->userCount();
        $data['soldCoins'] = $this->transactions->getSoldCoins();

        switch( $this->options->getOption('current_stage') ) {
            case 0:
                $data['stage'] = 'Pre-ICO';
                break;
            case 1:
                $data['stage'] = 'First Round';
                break;
            case 2:
                $data['stage'] = 'Second Round';
                break;
            case 3:
                $data['stage'] = 'Third Round';
                break;
            case 4:
                $data['stage'] = 'Fourth Round';
                break;
            default:
                $data['stage'] = 'First Round';
                break;
        }

        $response = array(
            'status'    => SUCCESS_CODE,
            'message'   => 'Detail found',
            'data'      => $data
        );

        $this->_response( $response );

    }

    public function getSoldCoins()
    {

        $this->load->model( 'transaction_model', 'transactions' );

        $data['soldCoins'] = $this->transactions->getSoldCoins();

        $response = array(
            'status'    => SUCCESS_CODE,
            'message'   => 'Detail found',
            'data'      => $data
        );

        $this->_response( $response );

    }

    public function getTime( $user_id = null )
    {
        $this->load->model( 'user_model' );
        $stime = strtotime($this->user_model->getBuyTime( $user_id ));
        $ctime = strtotime("now");

        $response = array(
            'status'    => SUCCESS_CODE,
            'message'   => 'Detail found',
            'data'      => $ctime - $stime
        );

        $this->_response( $response );
    }

    public function setTime( $user_id = null )
    {
        $this->load->model( 'user_model' );
        $this->user_model->setBuyTime( $user_id );

        $response = array(
            'status'    => SUCCESS_CODE,
            'message'   => 'Detail found',
        );

        $this->_response( $response );
    }
}
