<?php

class Api_model extends MY_Model
{
    
    private $url = 'http://192.168.1.120:3000/';

    public function getAccountId( $currency = 'ETH' )
    {
        $data['table'] = ACCOUNTS_TABLE;
        $data['where'] = array( 'currency' => $currency );

        $result = $this->_selectRecords( $data );

        if( !empty( $result ) ) {
            return $result[0]['account_id'];
        } else {
            return FALSE;
        }

    }

    public function createAddress( $currency = 'ETH' )
    {
        $account_id = $this->getAccountId( $currency );

        if( $account_id !== FALSE ){

            $url = $this->url . 'create-address/' . $account_id;

            $result = $this->_api( $url );

            if( isset( $result->address->id ) && isset( $result->address->address ) ){
                return array(
                    'id' => $result->address->id,
                    'address' => $result->address->address
                );
            } else {
                return FALSE;
            }

        } else {
            return FALSE;
        }

    }

    public function balance( $address_id, $currency = 'ETH' )
    {
        $account_id = $this->getAccountId( $currency );

        if( $account_id !== FALSE ){

            $url = $this->url . 'address-detail/' . $account_id . '/' . $address_id;

            $result = $this->_api( $url );

            if( isset( $result->address->id ) && isset( $result->address->address ) ){
                return $result->address->account->balance->amount;
            } else {
                return FALSE;
            }

        } else {
            return FALSE;
        }
    }

    private function _api( $url )
    {
        $result = @file_get_contents( $url );
        return json_decode( $result );
    }

}
