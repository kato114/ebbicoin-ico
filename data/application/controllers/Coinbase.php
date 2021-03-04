<?php

class Coinbase extends MY_Controller
{
    
    function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->model('coinbase_model', 'this_model');
    }

    public function index()
    {

        if( ! $this->input->get('scope') || ! $this->input->get('user_id') ) {
            redirect('http://localhost/John-rework/');
        } else {

            $result = $this->this_model->checkToken( $this->input->get('scope'), $this->input->get('user_id') );

            if( $result === TRUE ){
                redirect('coinbase/wallet_accounts_read');
            } else {
                redirect('coinbase/auth?scope=' . $this->input->get('scope') . '&user_id=' . $this->input->get('user_id'));
            }

        }

    }
    
    public function auth()
    {
        if( $this->input->get('code') ){

            $post = 'grant_type=authorization_code&code=' . $this->input->get('code') . '&client_id=' . COINBASE_CLIENT_ID . '&client_secret=' . COINBASE_CLIENT_SECRET . '&redirect_uri=' . COINBASE_REDIRECT_URI;
        
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL,"https://api.coinbase.com/oauth/token/");
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $TokenResult = curl_exec($ch);
            curl_close($ch);
            $TokenResultArray = json_decode($TokenResult);
        
            if( !isset( $TokenResultArray->error ) ){
                
                $values = array(
                    'user_id'       => $this->session->userdata('user_id'),
                    'scope'         => $this->session->userdata('scope'),
                    'token'         => $TokenResultArray->access_token,
                    'refresh_token' => $TokenResultArray->refresh_token
                );

                $result = $this->this_model->addToken( $values );

                if( $result === TRUE ){

                    $scope = $this->session->userdata('scope');
                    $params  = '?scope=' . $this->session->userdata('scope') . '&user_id=' . $this->session->userdata('user_id');
                    $params .= ( $this->session->userdata('account_id') ) ? '&account_id=' . $this->session->userdata('account_id') : '';                  
                    $this->session->sess_destroy();
                    redirect('coinbase/' . str_replace(':', '_', 'wallet_accounts_read') . $params );

                } else {
                    echo 'Failed to generate token.';
                    // redirect('coinbase/failed');
                }

                die();
                
            } else {
                echo $TokenResultArray->error;
            }
            
        } else {
        
            if( ! $this->input->get('scope') || ! $this->input->get('user_id') ) {
                redirect('http://localhost/John-rework/');
            }
    
            $this->session->set_userdata('scope', $this->input->get('scope'));
            $this->session->set_userdata('user_id', $this->input->get('user_id'));
    
            if( $this->input->get('account_id') ){
                $this->session->set_userdata('account_id', $this->input->get('account_id'));
            }
            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://www.coinbase.com/oauth/authorize?response_type=code&client_id=" . COINBASE_CLIENT_ID . "&redirect_uri=" . COINBASE_REDIRECT_URI . "&scope=" . $this->input->get('scope') . "&meta[send_limit_amount]=1&meta[send_limit_currency]=USD&meta[send_limit_period]=day" );
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $AuthResult = curl_exec($ch);
            curl_close($ch);
            echo $AuthResult;
            echo '<script type="text/javascript"> window.location.href=document.querySelectorAll("a")[0].href + "&output=embed"; </script>';

        }
    }

    public function success()
    {
        echo 'Token Generated Successfully.';
    }

    public function failed()
    {
        echo 'Failed to generate token.';
    }

    public function wallet_accounts_read()
    {
        $this->form_validation->set_data($this->input->get());

        if ( $this->form_validation->run() == FALSE ){

            $result = array(
                'status' => ERROR_CODE,
                'message' => validation_errors( '<p class="text-danger">', '</p>' )
            );

        } else {
            
            $result = $this->this_model->checkToken( 'wallet:accounts:read', $this->input->get('user_id') );

            if( $result === FALSE ){

                $result = array(
                    'status' => ERROR_CODE,
                    'message' => '<p class="alert alert-warning">We do not have permission to access this module from Coinbase. <a href="' . site_url('coinbase/auth?scope=wallet:accounts:read&user_id=' . $this->input->post('user_id') ) . '" target="_blank">Click here</a> for allow us to access. </p>'
                );

            } else {

                $result = file_get_contents( base_url( 'coinbase/wallet_accounts_read_api?token=' . $result['token'] ) );
                
                $result = json_decode( $result );

                if( ! isset( $result->errors ) ){

                    $account = array(
                        'user_id'       => $this->input->get('user_id'),
                        'account_id'    => $result->data[0]->id,
                        'currency'      => $result->data[0]->currency->code
                    );

                    $response = $this->this_model->addAccount( $account );

                    if( $response ){

                        redirect( 'coinbase/wallet_addresses_read?user_id=' . $this->input->get('user_id') . '&account_id=' . $result->data[0]->id );
                        
                    } else {
                        $result = array(
                            'status' => ERROR_CODE,
                            'message' => '<p class="alert alert-danger">Failed to authorize</p>'
                        );
                    }

                }
                
            }
        }

        $this->_response( $result );

    }

    public function wallet_accounts_read_api()
    {
        
        $authorization = 'Authorization: Bearer ' . $this->input->get('token');

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,"https://api.coinbase.com/v2/accounts");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization ));
        curl_exec($ch);
        curl_close($ch);
        
    }

    public function wallet_addresses_read()
    {
        $this->form_validation->set_data($this->input->get());

        if ( $this->form_validation->run() == FALSE ){
            $result = array(
                'status' => ERROR_CODE,
                'message' => validation_errors( '<p class="text-danger">', '</p>' )
            );

        } else {
            
            $token = $this->this_model->checkToken( 'wallet:addresses:read', $this->input->get('user_id') );

            if( $token === FALSE ){

                $this->redirectAuth( $this->input->get('user_id') );

            } else {
                $result = file_get_contents( base_url( 'coinbase/wallet_addresses_create_api?token=' . $token['token'] . '&account_id=' . $this->input->get('account_id') ) );

                $result = json_decode( $result );

                if( ! isset( $result->errors ) ){

                    if( empty( $result->data ) ){

                        $result = file_get_contents( base_url( 'coinbase/wallet_addresses_create_api?token=' . $token['token'] . '&account_id=' . $this->input->get('account_id') ) );                        
                        $result = json_decode( $result );
                        echo '<pre>'; print_r($result); die();

                    } else {

                        $address = array(
                            'user_id'   => $this->input->get('user_id'),
                            'id'        => $result->data->id,
                            'address'   => $result->data->address,
                            'network'   => $result->data->network
                        );

                        $response = $this->this_model->updateAddress( $address );

                        if( $response ){
                            redirect('http://localhost/John-rework/dashboard');
                        } else {
                            $result = array(
                                'status' => ERROR_CODE,
                                'message' => '<p class="alert alert-danger">Failed to authorize</p>'
                            );
                        }

                    }

                }
                
            }
        }

        $this->_response( $result );

    }

    public function wallet_addresses_read_api()
    {
        
        $authorization = 'Authorization: Bearer ' . $this->input->get('token');

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,"https://api.coinbase.com/v2/accounts/" . $this->input->get('account_id') . "/addresses");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization ));
        curl_exec($ch);
        curl_close($ch);
        
    }

    public function wallet_address_read()
    {
        
        $this->form_validation->set_data($this->input->get());

        if ( $this->form_validation->run() == FALSE ){
            $result = array(
                'status' => ERROR_CODE,
                'message' => validation_errors( '<p class="text-danger">', '</p>' )
            );

        } else {
            
            $account = $this->this_model->checkAccount( $this->input->get('user_id'), 'ETH' );

            if( $account === FALSE ){

                echo 'Invalid Account';
                die();

            }
            
            $result = $this->this_model->checkToken( 'wallet:buys:create', $this->input->get('user_id') );

            if( $result === FALSE ){

                $this->redirectAuth( $this->input->get('user_id') );

            } else {

                $result = file_get_contents( base_url( 'coinbase/wallet_buys_create_api?token=' . $result['token'] . '&account_id=' . $account['account_id'] . '&amount=' . $this->input->get('receive_quantity') . '&currency=' . $this->input->get('currency') . '&payment_method=' . $this->input->get('payment_method') ) );

                $result = json_decode( $result );

                if( ! isset( $result->errors ) ){

                }
            }
        }
    }

    public function wallet_address_read_api()
    {
        
        $authorization = 'Authorization: Bearer ' . $this->input->get('token');

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,"https://api.coinbase.com/v2/accounts/" . $this->input->get('account_id') . "/addresses/" . $this->input->get('address_id'));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization ));
        curl_exec($ch);
        curl_close($ch);
        
    }

    public function wallet_addresses_create_api()
    {
        
        $authorization = 'Authorization: Bearer ' . $this->input->get('token');

        $post = array(
            'name' => 'EbbiCoin Address'
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,"https://api.coinbase.com/v2/accounts/" . $this->input->get('account_id') . "/addresses");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization ));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode( $post ));
        curl_exec($ch);
        curl_close($ch);
        
    }

    public function wallet_buys_create()
    {
        $this->form_validation->set_data($this->input->get());

        if ( $this->form_validation->run() == FALSE ){
            $result = array(
                'status' => ERROR_CODE,
                'message' => validation_errors( '<p class="text-danger">', '</p>' )
            );

        } else {
            
            $account = $this->this_model->checkAccount( $this->input->get('user_id'), 'ETH' );

            if( $account === FALSE ){

                echo 'Invalid Account';
                die();

            }
            
            $result = $this->this_model->checkToken( 'wallet:buys:create', $this->input->get('user_id') );

            if( $result === FALSE ){

                $this->redirectAuth( $this->input->get('user_id') );

            } else {

                $result = file_get_contents( base_url( 'coinbase/wallet_buys_create_api?token=' . $result['token'] . '&account_id=' . $account['account_id'] . '&amount=' . $this->input->get('receive_quantity') . '&currency=' . $this->input->get('currency') . '&payment_method=' . $this->input->get('payment_method') ) );

                $result = json_decode( $result );

                if( ! isset( $result->errors ) ){

                    $this->load->model( 'transaction_model' );

                    $address = array(
                        'user_id'           => $this->input->get('user_id'),
                        'transaction_id'    => $result->data[0]->id,
                        'exchange'          => 'USD-ETH',
                        'send_quantity'     => $this->input->get('send_quantity'),
                        'send_rate'         => $this->input->get('send_rate'),
                        'receive_quantity'  => $this->input->get('receive_quantity'),
                        'receive_rate'      => $this->input->get('receive_rate')
                    );

                    $response = $this->transaction_model->buyCoin( $address );

                    if( $response ){
                        $result = array(
                            'status'    => SUCCESS_CODE,
                            'message'   => 'Thank you',
                            'class'     => 'success'
                        );
                    } else {
                        $result = array(
                            'status'    => ERROR_CODE,
                            'message'   => 'Failed to authorize',
                            'class'     => 'danger'
                        );
                    }

                } else {
                    $result = array(
                        'status' => ERROR_CODE,
                        'message' => $result->errors[0]->message,
                        'class'     => 'danger'
                    );
                }
                
            }
        }

        $this->_response( $result );

    }

    public function wallet_buys_create_api()
    {
        
        $authorization = 'Authorization: Bearer ' . $this->input->get('token');

        $post = array(
            'amount'            => $this->input->get('amount'),
            'currency'          => $this->input->get('currency'),
            'payment_method'    => $this->input->get('payment_method'),
            'commit'            => false
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,"https://api.coinbase.com/v2/accounts/" . $this->input->get('account_id') . "/buys");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization ));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode( $post ));
        curl_exec($ch);
        curl_close($ch);
        
    }

    public function wallet_payment_methods_read()
    {
        $this->form_validation->set_data($this->input->get());

        if ( $this->form_validation->run() == FALSE ){
            
            $result = array(
                'status' => ERROR_CODE,
                'message' => validation_errors( '<p class="text-danger">', '</p>' )
            );

        } else {
            
            $result = $this->this_model->checkToken( 'wallet:payment-methods:read', $this->input->get('user_id') );

            if( $result === FALSE ){

                $this->redirectAuth( $this->input->get('user_id') );

            } else {
                $result = file_get_contents( base_url( 'coinbase/wallet_payment_methods_read_api?token=' . $result['token'] ) );
                
                $result = json_decode( $result );

                if( ! isset( $result->errors ) ){
                    $result = array(
                        'status'    => SUCCESS_CODE,
                        'message'   => '<p class="alert alert-success">Payment methods</p>',
                        'data'      => $result->data
                    );
                } else {
                    $result = array(
                        'status' => ERROR_CODE,
                        'message' => '<p class="alert alert-danger">Failed to authorize</p>'
                    );
                }
                
            }
        }

        $this->_response( $result );

    }

    public function wallet_payment_methods_read_api()
    {
        
        $authorization = 'Authorization: Bearer ' . $this->input->get('token');

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,"https://api.coinbase.com/v2/payment-methods");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization ));
        curl_exec($ch);
        curl_close($ch);
        
    }

    public function wallet_transactions_send()
    {
        $_POST = (array) json_decode( file_get_contents( 'php://input' ) );

        if ( $this->form_validation->run() == FALSE ){
            $result = array(
                'status' => ERROR_CODE,
                'message' => validation_errors( '<p class="text-danger">', '</p>' )
            );

        } else {
            
            $account = $this->this_model->checkAccount( $this->input->post('user_id'), 'ETH' );

            if( $account === FALSE ){
                $result = array(
                    'status'    => ERROR_CODE,
                    'message'   => 'Invalid Account'
                );
            }
            
            $result = $this->this_model->checkToken( 'wallet:transactions:send', $this->input->post('user_id') );

            if( $result === FALSE ){

                $this->redirectAuth( $this->input->post('user_id') );

            } else {

                $result = file_get_contents( base_url( 'coinbase/wallet_transactions_send_api?token=' . $result['token'] . '&account_id=' . $account['account_id'] . '&type=send&to=' . $this->input->post('to') . '&amount=' . $this->input->post('send_quantity') . '&currency=ETH' ) );
                
                $result = json_decode( $result );

                if( ! isset( $result->errors ) ){

                    $this->load->model( 'transaction_model' );

                    $address = array(
                        'user_id'           => $this->input->get('user_id'),
                        'transaction_id'    => $result->data[0]->id,
                        'exchange'          => 'USD-ETH',
                        'send_quantity'     => $this->input->get('send_quantity'),
                        'send_rate'         => $this->input->get('send_rate'),
                        'receive_quantity'  => $this->input->get('receive_quantity'),
                        'receive_rate'      => $this->input->get('receive_rate')
                    );

                    $response = $this->transaction_model->buyCoin( $address );

                    if( $response ){
                        $result = array(
                            'status'    => SUCCESS_CODE,
                            'message'   => 'Thank you',
                            'class'     => 'success'
                        );
                    } else {
                        $result = array(
                            'status'    => ERROR_CODE,
                            'message'   => 'Failed to authorize',
                            'class'     => 'danger'
                        );
                    }
                    
                } else {
                    $result = array(
                        'status'    => ERROR_CODE,
                        'message'   => $result->errors[0]->message,
                        'class'     => 'danger'
                    );
                }
                
            }
        }

        $this->_response( $result );

    }

    public function wallet_transactions_send_api()
    {
        
        $authorization = 'Authorization: Bearer ' . $this->input->get('token');

        $post = array(
            'type'      => $this->input->get('type'),
            'to'        => $this->input->get('to'),
            'amount'    => $this->input->get('amount'),
            'currency'  => $this->input->get('currency')
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,"https://api.coinbase.com/v2/accounts/" . $this->input->get('account_id') . "/buys");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization ));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode( $post ));
        curl_exec($ch);
        curl_close($ch);
        
    }

    public function refreshToken()
    {

        $tokens = $this->this_model->getAllExpiringToken();

        foreach( $tokens as $token ){

            $post = 'grant_type=refresh_token&client_id=' . COINBASE_CLIENT_ID . '&client_secret=' . COINBASE_CLIENT_SECRET . '&refresh_token=' . $token['refresh_token'] ;
        
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL,"https://api.coinbase.com/oauth/token/");
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $TokenResult = curl_exec($ch);
            curl_close($ch);

            $TokenResult = json_decode($TokenResult);

            if( isset( $TokenResult->error ) ){
                echo $TokenResult->error_description;
                die();
            }

            $data = array(
                'user_id'       => $token['user_id'],
                'token'         => $TokenResult->access_token,
                'refresh_token' => $TokenResult->refresh_token
            );

            $result = $this->this_model->refreshToken( $data );

        }

    }

    public function redirectAuth( $params )
    {
        redirect( base_url() . 'coinbase/auth?scope=wallet:accounts:read,wallet:addresses:read,wallet:buys:read,wallet:buys:create,wallet:transactions:read,wallet:transactions:send,wallet:payment-methods:read&user_id=' . $params );
    }

}
