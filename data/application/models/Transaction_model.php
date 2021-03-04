<?php

class Transaction_model extends MY_Model
{

    public function getData( $values )
    {
        $data['table'] = TRANSACTION_TABLE;
        $data['where'] = array( 'user_id' => $values['user_id'] );
        $data['order_by'] = array(
            array( 'column' => 'id', 'order' => 'DESC' )
        );

        $result = $this->_selectRecords( $data );

        if( !empty( $result ) ){
            return array(
                'status' => SUCCESS_CODE,
                'message' => 'Transactions list',
                'data'  => $result
            );
        } else {
            return array(
                'status' => SUCCESS_CODE,
                'message' => 'No Transactions Found',
            );
        }

    }

    public function add( $values )
    {

        $data['table'] = TRANSACTION_TABLE;
        $data['insert'] = array(
            'user_id'           => $values['user_id'],
            'transaction_id'    => '123',
            'exchange'          => $values['exchange'],
            'send_quantity'     => $values['send_quantity'],
            'send_rate'         => $values['send_rate'],
            'receive_quantity'  => $values['receive_quantity'],
            'receive_rate'      => $values['receive_rate'],
            'status'            => 1,
            'created_at'        => DATE_TIME
        );

        $result = $this->_insertRecord( $data );

        if( $result !== FALSE ){

            $data['table']      =  USER_TABLE;
            $data['set']        =  array( 
                array( 'ebbi_balance', 'ebbi_balance+' . (float) $values['receive_quantity'], FALSE )
            );
            $data['where']      =  array( 'id'     => $values['user_id'] );

            $result = $this->_updateRecords( $data );

            $userData['table'] = USER_TABLE;
            $userData['where'] = array( 'id' => $values['user_id'] );                

            $user = $this->_selectRecords( $userData );
            $user['referral'] = $user[0]['referral'];
            $referral = $user['referral'];

            if( $user['referral'] != '' ){

                for ($i=1; $i <= 3; $i++) {

                    $referralData['table']  = USER_TABLE;
                    $referralData['select'] = array( 'id', 'referral' );
                    $referralData['where']  = array( 'username' => $referral );                
        
                    $user = $this->_selectRecords( $referralData );
    
                    if( !empty( $user ) ){
    
                        switch ($i) {
                            case 1:
                                $rate = LEVEL1_RATE;
                                break;
                            case 2:
                                $rate = LEVEL2_RATE;
                                break;
                            case 3:
                                $rate = LEVEL3_RATE;
                                break;
                            default:
                                $rate = LEVEL3_RATE;
                                break;
                        }

                        $referral = $user[0]['referral'];

                        $amount = (float) $values['receive_quantity'];
                        $referralIncome = ( $amount * $rate ) / 100;

                        $data['table']      =  USER_TABLE;
                        $data['set']        =  array( 
                            array( 'ebbi_balance', 'ebbi_balance+' . $referralIncome, FALSE )
                        );
                        $data['where']      =  array( 'id'     => $user[0]['id'] );
            
                        $result = $this->_updateRecords( $data );

                        $transactionData['table'] = REFERRAL_TABLE;
                        $transactionData['insert'] = array(
                            'user_id'           => $user[0]['id'],
                            'referred_id'       => $values['user_id'],
                            'amount'            => $amount,
                            'referral_income'   => $referralIncome,
                            'level'             => $i,
                            'created_at'        => DATE_TIME
                        );

                        $result = $this->_insertRecord( $transactionData );
    
                    }
    
                }
            }

            return array(
                'status'    => SUCCESS_CODE,
                'message'   => 'Transaction has been placed successfully.',
                'class'     => 'success'
            );

        } else {
            return array(
                'status'    => ERROR_CODE,
                'message'   => 'Failed to place your order.',
                'class'     => 'danger'
            );
        }

    }

    public function buyCoin( $values )
    {

        $data['table'] = TRANSACTION_TABLE;
        $data['insert'] = array(
            'user_id'           => $values['user_id'],
            'transaction_id'    => '123',
            'exchange'          => $values['exchange'],
            'send_quantity'     => $values['send_quantity'],
            'send_rate'         => $values['send_rate'],
            'receive_quantity'  => $values['receive_quantity'],
            'receive_rate'      => $values['receive_rate'],
            'status'            => 1,
            'created_at'        => DATE_TIME
        );

        $result = $this->_insertRecord( $data );

        if( $result !== FALSE ){

            return array(
                'status'    => SUCCESS_CODE,
                'message'   => 'Transaction has been placed successfully.',
                'class'     => 'success'
            );

        } else {
            return array(
                'status'    => ERROR_CODE,
                'message'   => 'Failed to place your order.',
                'class'     => 'danger'
            );
        }

    }

    private function _makeEthereumTransaction( $values = '' )
    {
        $data['url'] = 'https://api.blockcypher.com/v1/eth/main/txs/new?token=ac2331eb02924674bfbc2a49a350a78b';
        $data['post'] = TRUE;
        $data['params'] = '{"inputs":[{"addresses": ["a90ea4cb1bfec70719315853a21388a55c61151a"]}],"outputs":[{"addresses": ["0xc14e35e7231c2e8f1b93737b6e840b69fd80fa87"], "value": 4200000000000000}]}';
        $data['header'] = 'application/json';
        $result = $this->_curlRequest( $data );
        return json_decode( $result );
    }

    public function getSoldCoins()
    {
        $data['table']  = TRANSACTION_TABLE;
        $data['select'] = array( 'ROUND(SUM(receive_quantity), 2) AS coins' );
        $data['where']  = array( 'created_at >' => '2018-05-17 23:59:59');
        $result = $this->_selectRecords( $data );
        return ( !empty( $result ) && !empty($result[0]['coins']) ) ? $result[0]['coins'] : 0 ;
    }

    public function getStageTransactions( $stage )
    {
        $data['select'] =  array( 't.id', 't.user_id', 't.send_quantity', 't.receive_quantity', 't.created_at', 'u.username', 'u.email' );
        $data['table']  =  TRANSACTION_TABLE . ' AS t';
        $data['joins']  =  array(
            array(
                'table' => USER_TABLE . ' AS u',
                'on'    => 't.user_id = u.id'
            )
        );
        $data['where']  =  array( 'stage' => $stage );

        $result = $this->_selectRecords( $data );

        if( !empty( $result ) ){
            return array(
                'status' => SUCCESS_CODE,
                'message' => 'Transactions list',
                'data'  => $result
            );
        } else {
            return array(
                'status' => SUCCESS_CODE,
                'message' => 'No Transactions Found',
            );
        }
    }

}