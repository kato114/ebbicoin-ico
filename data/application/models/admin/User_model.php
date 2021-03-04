<?php

class User_model extends MY_Model
{
    
    public function __construct()
    {
        parent::__construct();
    }

    public function getUsers( $field, $dir )
    {
        
        $data['table'] = USER_TABLE;
        $data['select'] = array( 'id', 'username', 'email', 'ebbi_balance', 'status', 'created_at' );
        $data['order_by'] = array( 
            array(
                'column' => $field == NULL ? 'created_at' : $field, 
                'order' => $dir == 0? 'ASC' : 'DESC' 
            )
        );

        $result = $this->_selectRecords( $data );

        if(!empty( $result )  ){
            return array(
                'status'    => SUCCESS_CODE,
                'message'   => 'Users List',
                'data'      => $result
            );
        } else {
            return array(
                'status'    => ERROR_CODE,
                'messgae'   => 'No User Found'
            );
        }

    }

    public function getUser( $id )
    {
        
        $data['table'] = USER_TABLE;
        $data['select'] = array( 'id', 'username', 'email', 'password' );
        $data['where'] = array( 'id' => $id );

        $result = $this->_selectRecords( $data );

        if(!empty( $result )  ){
            return array(
                'status'    => SUCCESS_CODE,
                'message'   => 'Users List',
                'data'      => $result[0]
            );
        } else {
            return array(
                'status'    => ERROR_CODE,
                'messgae'   => 'No User Found'
            );
        }

    }
    
    public function add( $values )
    {
        $this->load->library('utility');
        $token = $this->utility->token();

        $ethAddress = file_get_contents('https://004g69opha.execute-api.us-east-1.amazonaws.com/create');
        $ethAddress = json_decode( $ethAddress );

        $data['table']      =  USER_TABLE;
        $data['insert']     =  array(
            'username'      => $values['username'],
            'email'         => $values['email'],
            'password'      => md5($values['password']),
            'token'         => $token,
            'status'        => '1',
            'created_at'    => DATE_TIME,
            'eth_id'        => $ethAddress->privateKey,
            'eth_address'   => $ethAddress->address,
        );

        $result = $this->_insertRecord( $data );

        if( $result !== FALSE ){

            return array(
                'status'    => SUCCESS_CODE,
                'message'   => '<p class="alert alert-success">User Added Successfully.</p>'
            );

        } else {

            return array(
                'status'    => ERROR_CODE,
                'message'   => ERROR_RESPONSE
            );

        }

    }
    
    public function edit( $values )
    {
        $this->load->library('utility');
        $token = $this->utility->token();

        $data['table']      =  USER_TABLE;
        $data['update']     =  array(
            'username'      => $values['username'],
            'email'         => $values['email']
        );

        if($values['password'] !== NULL && strlen($values['password']) !== 0) {
            $data['update']['password'] = md5($values['password']);
        }

        $data['where']      =  array( 'id' => $values['id'] );

        $result = $this->_updateRecords( $data );

        if( $result !== FALSE ){

            return array(
                'status'    => SUCCESS_CODE,
                'message'   => '<p class="alert alert-success">User Updated Successfully.</p>'
            );

        } else {

            return array(
                'status'    => ERROR_CODE,
                'message'   => ERROR_RESPONSE
            );

        }

    }
    
    public function changeStatus( $values ) 
    {

        $data['table']      =  USER_TABLE;
        $data['update']     =  array( 'status' => $values['status'] );
        $data['where']      =  array( 'id'     => $values['id'] );

        if($values['status'] == 3) {
            $result = $this->_deleteRecord($data);
            $msg = 'User Deleted Successfully.';
        } else {
            $result = $this->_updateRecords( $data );
            $msg = 'User Status Updated Successfully.';
        }

        if( $result !== FALSE ){

            return array(
                'status'    => SUCCESS_CODE,
                'message'   => '<p class="alert alert-success">' . $msg . '</p>'
            );

        } else {

            return array(
                'status'    => ERROR_CODE,
                'message'   => ERROR_RESPONSE
            );

        }

    }
    
    public function addUserEbbiCoinBalance( $values ) 
    {

        $data['table']      =  USER_TABLE;
        $data['set']        =  array( 
            array( 'ebbi_balance', $values['coin'], FALSE )
        );
        $data['where']      =  array( 'id'     => $values['user_id'] );

        $result = $this->_updateRecords( $data );

        if( $result !== FALSE ){

            return array(
                'status'    => SUCCESS_CODE,
                'message'   => '<p class="alert alert-success">Coins Added to user account.</p>'
            );

        } else {

            return array(
                'status'    => ERROR_CODE,
                'message'   => ERROR_RESPONSE
            );

        }

    }

    public function balance()
    {
        $balance = @file_get_contents( API_URL . 'balance.php?address=' . ADMIN_ETH_ADDR );

        return array(
            'status'    => SUCCESS_CODE,
            'message'   => 'Balance detail',
            'data'      => $balance
        );

    }

    public function transaction( $values )
    {

        $transferBalance = $values['amount'] - 0.00021;

        if( $transferBalance < 0 ){
            return array(
                'status'    => ERROR_CODE,
                'message'   => 'Minimum transfer amount is 0.00022',
                'class'     => 'danger'
            );
        }

        $balance = @file_get_contents( API_URL . 'balance.php?address=' . ADMIN_ETH_ADDR );

        $balance = json_decode( $balance );
        $balance = ( double ) $balance->eth;

        if( $balance < $transferBalance ){
            return array(
                'status'    => ERROR_CODE,
                'message'   => 'Your account does not have this much balance to transfer. You have ' . $balance . 'ETH',
                'class'     => 'danger'
            );
        }

        $transaction = @file_get_contents( API_URL . 'transaction.php?id=' . ADMIN_ETH_KEY . '&receiver=' . $values['address'] . '&amount=' . $transferBalance );
        $transaction = json_decode( $transaction );

        if( ! isset( $transaction->code ) ){

            return array(
                'status'    => SUCCESS_CODE,
                'message'   => 'Amount transfered successfully.',
                'class'     => 'success'
            );

        } else {
            return array(
                'status'    => ERROR_CODE,
                'message'   => 'Failed to transfer',
                'class'     => 'danger'
            );
        }
    }
    
    public function statics()
    {

        $data['table']  =  USER_TABLE;
        $users          = $this->_selectRecords( $data );

        $data['select'] = array( 'SUM(ebbi_balance) AS ebbi' );
        $ebbiCoins      = $this->_selectRecords( $data );

        $data['select'] = array();
        $data['where']  = array( 'status' => 1 );
        $active_users   = $this->_selectRecords( $data );

        $data['table']  =  TRANSACTION_TABLE;
        $data['select'] = array( 'SUM(send_quantity) AS eth' );
        $eth_transfered = $this->_selectRecords( $data );

        return array(
            'status'    => SUCCESS_CODE,
            'message'   => 'Statics Detail',
            'data'      => array(
                'accounts'          =>  ( ! empty( $users ) ) ? count( $users ) : 0,
                'active_accounts'   =>  ( ! empty( $active_users ) ) ? count( $active_users ) : 0,
                'ebbicoins'         =>  ( ! empty( $ebbiCoins ) ) ? round( (double) $ebbiCoins[0]['ebbi'], 8 ) : 0,
                'eth_transfered'    =>  ( ! empty( $eth_transfered ) ) ? round( (double) $eth_transfered[0]['eth'], 8 ) : 0,
            )
        );

    }

    public function setOption( $value )
    {
        $data['table']      =  OPTION_TABLE;
        $data['where']      =  array( 'opt_key'     => 'current_stage' );

        $result = $this->_selectRecords( $data );

        if(!empty( $result )  ){
            $data['set']        =  array( 
                array( 'opt_value', $value['stage'], FALSE )
            );

            $result = $this->_updateRecords( $data );
        } else {
            $data['insert']     =  array(
                'opt_key'      => 'current_stage',
                'opt_value'    => $value['stage']
            );
            $result = $this->_insertRecord( $data );
        }

        $data['where']      =  array( 'opt_key'     => 'current_stage_price' );
        $result = $this->_selectRecords( $data );

        if(!empty( $result )  ){
            $data['set']        =  array( 
                array( 'opt_value', $value['price'], FALSE )
            );

            $result = $this->_updateRecords( $data );
        } else {
            $data['insert']     =  array(
                'opt_key'      => 'current_stage_price',
                'opt_value'    => $value['price']
            );

            $result = $this->_insertRecord( $data );
        }
        
        return array(
            'status'    => SUCCESS_CODE,
            'message'   => 'Updated Successfully',
            'class'     => 'success'
        );
    }

    public function getOption( $value )
    {
        $data['table']      =  OPTION_TABLE;
        $data['where']  = array( 'opt_key' => 'current_stage' );
        $stage = $this->_selectRecords( $data );
        
        $data['where']  = array( 'opt_key' => 'current_stage_price' );
        $price = $this->_selectRecords( $data );

        return array(
            'status'    => SUCCESS_CODE,
            'message'   => 'Stage Option',
            'data'      => array(
                'stage'   =>  ( ! empty( $stage ) ) ? $stage[0]['opt_value'] : '',
                'price'   =>  ( ! empty( $price ) ) ? $price[0]['opt_value'] : '',
            )
        );
    }
}
