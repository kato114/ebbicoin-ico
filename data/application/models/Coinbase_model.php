<?php

class Coinbase_model extends MY_Model
{

    public function addToken( $values )
    {

        $token = $this->checkToken( $values['scope'], $values['user_id'] );
        
        if( $token === FALSE ){

            $data['table']  = CB_TOKENS_TABLE;
            $data['insert'] = array(
                'user_id'           => $values['user_id'],
                'scope'             => $values['scope'],
                'token'             => $values['token'],
                'refresh_token'     => $values['refresh_token'],
                'created_at'        => DATE_TIME,
                'updated_at'        => DATE_TIME
            );

            $result = $this->_insertRecord( $data );

            if( $result !== FALSE ){
                return TRUE;
            } else {
                return FALSE;
            }

        } else {
            return TRUE;
        }

    }

    public function checkToken( $scope, $user_id )
    {

        $data['table']  = CB_TOKENS_TABLE;
        $data['where']  = array(
            'user_id'   => $user_id
        );
        $data['like']   = array(
            array(
                'column' => 'scope',
                'value'  => $scope
            )
        );

        $result = $this->_selectRecords( $data );

        if( ! empty( $result ) ){
            return $result[0];
        } else {
            return FALSE;
        }

    }

    public function getAllExpiringToken()
    {

        $data['table']  = CB_TOKENS_TABLE;
        $data['where']  = array(
            'updated_at < '   => date('Y-m-d H:i:s', time() - 3600)
        );

        $result = $this->_selectRecords( $data );

        return $result;
    }

    public function addAccount( $values )
    {

        $account = $this->checkAccount( $values['user_id'], $values['currency'] );
        
        if( $account === FALSE ){

            $data['table']  = ACCOUNTS_TABLE;
            $data['insert'] = array(
                'user_id'       => $values['user_id'],
                'account_id'    => $values['account_id'],
                'currency'      => $values['currency']
            );

            $result = $this->_insertRecord( $data );

            return ( $result !== FALSE ) ? TRUE : FALSE ;

        } else {
            return TRUE;
        }

    }

    public function checkAccount( $user_id, $currency )
    {

        $data['table']  = ACCOUNTS_TABLE;
        $data['where']  = array(
            'user_id'   => $user_id,
            'currency'  => $currency
        );

        $result = $this->_selectRecords( $data );

        if( ! empty( $result ) ){
            return $result[0];
        } else {
            return FALSE;
        }

    }

    public function updateAddress( $values )
    {

        $address = $this->checkAddress( $values['user_id'], $values['network'] );
        
        if( $address === TRUE ){

            $data['table'] = USER_TABLE;
            $data['where'] = array( 'id' => $values['user_id'] );

            if( $values['network'] == 'bitcoin' ){
                $data['update']     = array(
                    'btc_id'        => $values['id'],
                    'btc_address'   => $values['address']
                );
            } else if( $values['network'] == 'ethereum' ){
                $data['update']     = array(
                    'eth_id'        => $values['id'],
                    'eth_address'   => $values['address']
                );
            } else if( $values['network'] == 'litecoin' ){
                $data['update']     = array(
                    'ltc_id'        => $values['id'],
                    'ltc_address'   => $values['address']
                );
            } else if( $values['network'] == 'bitcoin cash' ){
                $data['update']     = array(
                    'bch_id'        => $values['id'],
                    'bch_address'   => $values['address']
                );
            }

            $result = $this->_updateRecords( $data );

            return ( $result !== FALSE ) ? TRUE : FALSE ;

        } else {
            return TRUE;
        }

    }

    public function checkAddress( $user_id, $network )
    {

        $data['table']  = USER_TABLE;
        $data['where']  = array(
            'id'   => $user_id
        );

        $result = $this->_selectRecords( $data );

        if( ! empty( $result ) ){

            if( $network == 'bitcoin' && $result[0]['btc_id'] != '' ){
                $response = FALSE;
            } else if( $network == 'ethereum' && $result[0]['eth_id'] != '' ){
                $response = FALSE;
            } else if( $network == 'litecoin' && $result[0]['ltc_id'] != '' ){
                $response = FALSE;
            } else if( $network == 'bitcoin cash' && $result[0]['bch_id'] != '' ){
                $response = FALSE;
            } else {
                $response = TRUE;
            }

        } else {
            $response = FALSE;
        }

        return $response;

    }

    public function refreshToken( $values )
    {

        $data['table'] = CB_TOKENS_TABLE;
        $data['where'] = array( 'id' => $values['user_id'] );
        $data['update'] = array(
            'token' => $values['token'],
            'refresh_token' => $values['refresh_token']
        );

        $result = $this->_updateRecords( $data );

        return ( $result !== FALSE ) ? TRUE : FALSE ;
        
    }

}