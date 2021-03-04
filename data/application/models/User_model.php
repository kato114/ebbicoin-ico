<?php

class User_model extends MY_Model
{
    
    public function register( $values )
    {

        $this->load->model( 'api_model' );

        $this->load->library('utility');
        $token = $this->utility->token();

        $AuthObj    = new \Google\Authenticator\GoogleAuthenticator();
        $secret     = $AuthObj->generateSecret();

        $ethAddress = file_get_contents('https://004g69opha.execute-api.us-east-1.amazonaws.com/create');
        $ethAddress = json_decode( $ethAddress );

        $data['table']      =  USER_TABLE;
        $data['insert']     =  array(
            'username'      => $values['username'],
            'email'         => $values['email'],
            'referral'      => ( isset( $values['referral'] ) ) ? $values['referral'] : NULL,
            'password'      => md5($values['password']),
            'token'         => $token,
            'status'        => '0',
            'tfa_key'       => $secret,
            'tfa_status'    => '0',
            'eth_id'        => $ethAddress->privateKey,
            'eth_address'   => $ethAddress->address,
            'created_at'    => DATE_TIME
        );

        $result = $this->_insertRecord( $data );

        if( $result !== FALSE ){

            $view['token']      = $token;
            $view['username']   = $values['username'];

            $email['to']        = $values['email'];
            $email['subject']   = 'Registration Verification';
            $email['message']   = $this->load->view('email/registration_verification', $view, TRUE);

            $emailResult = $this->utility->email( $email );

            if( $emailResult === TRUE ){
                return array(
                    'status'    => SUCCESS_CODE,
                    'message'   => '<p class="alert alert-success">A verification link has been sent to your email. If you are unable to find the email, Please check your spam or promotions folder.</p>',
                    'data'      => array( 'user_id' => $result )
                );
            } else {
                return array(
                    'status'    => ERROR_CODE,
                    'message'   => '<p class="alert alert-danger">Unable to send email.</p>'
                );
            }

        } else {

            return array(
                'status'    => ERROR_CODE,
                'message'   => ERROR_RESPONSE
            );

        }
    }

    public function verify( $token = '' )
    {
        if( $token !== '' ){

            $data['table']  = USER_TABLE;
            $data['update'] = array( 'status' => 1 );
            $data['where']  = array( 'token' => $token );

            $result = $this->_updateRecords( $data );

            if( $result !== FALSE ){
                echo '<script>alert("Your account has been activated."); window.location="http://localhost/John-rework/home/login"</script>';
            } else {
                echo '<script>alert("Something went wrong. Please try again."); window.location="http://localhost/John-rework/"</script>';
            }

        } else {
            echo '<script>alert("Invalid token."); window.location="http://localhost/John-rework/"</script>';
        }
    }

    public function login( $values )
    {
        $data['table'] = USER_TABLE;
        $data['where'] = array(
            'username' => $values['username'],
            'password' => md5($values['password'])
        );

        $result = $this->_selectRecords( $data );

        if( !empty( $result ) ){

            if($result[0]['status'] == 1){

                if( $result[0]['tfa_status'] == 1){
                    $AuthObj        = new \Google\Authenticator\GoogleAuthenticator();
                    $currenctCode   = $AuthObj->getCode($result[0]['tfa_key']);

                    if($values['tfa_key'] != $currenctCode) {
                        return array(
                            'status' => ERROR_CODE,
                            'message' => '<p class="alert alert-danger">Incorrect two factor authentication password.</p>',
                        );
                    }
                }

                $this->load->library('utility');
                $token = $this->utility->token();

                $login['table']     =  LOGIN_TABLE;
                $login['insert']    =  array(
                    'user_id'       => $result[0]['id'],
                    'username'      => $values['username'],
                    'token'         => $token,
                    'ip_address'    => $_SERVER['REMOTE_ADDR'],
                    'created_at'    => DATE_TIME
                );

                $loginResult = $this->_insertRecord( $login );

                if( $loginResult !== FALSE ){
                    return array(
                        'status'    => SUCCESS_CODE,
                        'message'   => '<p class="alert alert-success">Logged in successfully. You will be redirected in 3 seconds.</p>',
                        'data'      => $login['insert']
                    );
                } else {
                    return array(
                        'status' => ERROR_CODE,
                        'message' => '<p class="alert alert-warning">Your account has been verified but due to some issue can not login to your account now. Please trya fter some time.</p>',
                    );
                }

            } else {
                return array(
                    'status' => ERROR_CODE,
                    'message' => '<p class="alert alert-danger">Your account has been deactivated or removed.</p>'
                );
            }

        } else {
            return array(
                'status' => ERROR_CODE,
                'message' => '<p class="alert alert-danger">Incorrect username and/or password</p>'
            );
        }

    }

    public function forgetPassword( $values )
    {
        $data['table'] = USER_TABLE;
        $data['where'] = array( 'email' => $values['email'] );

        $result = $this->_selectRecords( $data );

        if( !empty( $result ) ){

            if( $result[0]['status'] == 9 ){
                return array(
                    'status'    => ERROR_CODE,
                    'message'   => 'Your account has been deactivated or removed.',
                    'class'     => ERROR_CLASS
                );
            } else if( $result[0]['status'] == 0 ){
                return array(
                    'status'    => ERROR_CODE,
                    'message'   => 'Your account verification is still pending. Please verify your account first.',
                    'class'     => ERROR_CLASS
                );
            } else {

                $this->load->library('utility');
                $token = $this->utility->token();

                $data['update'] = array(
                    'token' => $token
                );

                $updateResult = $this->_updateRecords( $data );

                if( $updateResult !== FALSE ){

                    $view['token']      = $token;
                    $view['username']   = $result[0]['username'];
                    $email['to']        = $values['email'];
                    $email['subject']   = 'Reset Password';
                    $email['message']   = $this->load->view('email/reset_password', $view, TRUE);
        
                    $emailResult = $this->utility->email( $email );

                    if( $emailResult === TRUE ){
                        return array(
                            'status'    => SUCCESS_CODE,
                            'message'   => 'Verification link sent to your email. If you are unable to find email in your inbox take a look at your spam folder.',
                            'class'     => SUCCESS_CLASS,
                            'data'      => array( 'user_id' => $result[0]['id'] )
                        );
                    } else {
                        return array(
                            'status'    => ERROR_CODE,
                            'message'   => 'Unable to send email.',
                            'class'     => ERROR_CLASS
                        );
                    }

                } else {
                    return array(
                        'status'    => ERROR_CODE,
                        'message'   => 'Your account has been detected but failed to generate a token for reset password. Please try after a while.',
                        'class'     => ERROR_CLASS
                    );
                }

            }

        } else {
            return array(
                'status'    => ERROR_CODE,
                'message'   => 'This email is not registered with us. Make sure that you entered correct email or get lost from here.',
                'class'     => ERROR_CLASS
            );
        }

    }

    public function checkToken( $token = '' )
    {
        if( $token !== '' ) {

            $data['table']  = USER_TABLE;
            $data['where']  = array( 'token' => $token );

            $result = $this->_selectRecords( $data );

            if( !empty( $result ) ){
                return TRUE;
            } else {
                echo '<script>alert("Invalid token.");</script>';
                return FALSE;
            }

        } else {
            echo '<script>alert("Invalid token.");</script>';
            return FALSE;
        }
    }

    public function reset( $token, $values )
    {
        $data['table'] = USER_TABLE;
        $data['update'] = array(
            'password' => md5($values['password'])
        );
        $data['where'] = array(
            'token' => $token
        );
        
        $result = $this->_updateRecords($data);
        
        if( $result !== FALSE ){
            echo '<script>alert("Your password has been updated successfully."); window.location="http://localhost/John-rework/";</script>';
        } else {
            echo '<script>alert("Failed to udpate password."); window.location="http://localhost/John-rework/";</script>';
        }
    }

    public function loginStatus( $values )
    {
        $data['table'] = LOGIN_TABLE;
        $data['joins'] = array(
            array(
                'table' => USER_TABLE,
                'on'    => USER_TABLE . '.id = ' . LOGIN_TABLE . '.user_id'
            )
        );
        $data['where'] = array(
            LOGIN_TABLE . '.user_id'   => $values['user_id'],
            LOGIN_TABLE . '.token'     => $values['token'],
            USER_TABLE  . '.status'    => '1',
        );

        $result = $this->_selectRecords( $data );

        if( !empty( $result ) ){

            $user['table'] = USER_TABLE;
            $user['where'] = array( 'id' => $values['user_id'] );

            $userResult = $this->_selectRecords( $user );

            if( !empty( $userResult ) ){
                return array(
                    'status'    => SUCCESS_CODE,
                    'message'   => 'User detail',
                    'data'      => $userResult[0]
                );
            } else {
                return array(
                    'status'    => ERROR_CODE,
                    'message'   => 'User not found'
                );
            }

        } else {
            return array(
                'status'    => ERROR_CODE,
                'message'   => 'User not logged in'
            );
        }

    }

    public function resendEmail( $values )
    {
        $data['table']  = USER_TABLE;
        $data['select'] = array( 'username', 'email', 'token');
        $data['where']  = array( 'id' => $values['id'] );

        $result = $this->_selectRecords( $data );

        if( !empty( $result ) ){

            $this->load->library('utility');
            $view['token']      = $result[0]['token'];
            $view['username']   = $result[0]['username'];

            $email['to']        = $result[0]['email'];

            if( $values['type'] == 'register' ){
                $email['subject']   = 'Registration Verification';
                $email['message']   = $this->load->view('email/registration_verification', $view, TRUE);
            } else {
                $email['subject']   = 'Reset Password';
                $email['message']   = $this->load->view('email/reset_password', $view, TRUE);
            }
            

            $emailResult = $this->utility->email( $email );

            if( $emailResult === TRUE ){
                return array(
                    'status'    => SUCCESS_CODE,
                    'message'   => '<p class="alert alert-success">Verification link sent to your email.</p>'
                );
            } else {
                return array(
                    'status'    => ERROR_CODE,
                    'message'   => '<p class="alert alert-danger">Unable to send email.</p>'
                );
            }

        } else {
            return array(
                'status'    => ERROR_CODE,
                'message'   => '<p class="alert alert-danger">Unable to detect your account.</p>'
            );
        }

    }

    public function getUser( $id )
    {
        if( $id != '' ){

            $data['table']  = USER_TABLE;
            $data['where']  = array( 'id' => $id );

            $user = $this->_selectRecords( $data );

            if( !empty ( $user ) ){
                return array(
                    'status'    => SUCCESS_CODE,
                    'message'   => '<p class="alert alert-success">User Details.</p>',
                    'data'      => $user[0]
                );
            } else {
                return array(
                    'status'    => ERROR_CODE,
                    'message'   => '<p class="alert alert-danger">Unable to detect your account.</p>'
                );
            }

        } else {
            return array(
                'status'    => ERROR_CODE,
                'message'   => '<p class="alert alert-danger">Unable to detect your account.</p>'
            );
        }
    }

    public function createWallet( $id ) 
    {
        if( $id != '' ){
            $ethAddress = file_get_contents('https://004g69opha.execute-api.us-east-1.amazonaws.com/create');
            $ethAddress = json_decode( $ethAddress );
    
            $data['table']  =  USER_TABLE;
            $data['update'] =  array(
                'eth_id1'        => $ethAddress->privateKey,
                'eth_address1'   => $ethAddress->address,
            );
            $data['where']  =  array(
                'id'        => $id,
            );
    
            $result = $this->_updateRecords( $data );
            $user = $this->_selectRecords( $data );

            if( !empty ( $user ) ){
                return array(
                    'status'    => SUCCESS_CODE,
                    'message'   => '<p class="alert alert-success">User Details.</p>',
                    'data'      => $user[0]
                );
            } else {
                return array(
                    'status'    => ERROR_CODE,
                    'message'   => '<p class="alert alert-danger">Unable to detect your account.</p>'
                );
            }

        } else {
            return array(
                'status'    => ERROR_CODE,
                'message'   => '<p class="alert alert-danger">Unable to detect your account.</p>'
            );
        }
    }

    public function getBalance( $values )
    {
        $data['table']  = USER_TABLE;
        $data['where']  = array( 'id' => $values['user_id'] );

        $user = $this->_selectRecords( $data );

        if( !empty ( $user ) ){

            $this->load->model( 'api_model' );
            
            /* $btc_balance = $this->api_model->balance( $user[0]['btc_id'], 'BTC' );
            $eth_balance = $this->api_model->balance( $user[0]['eth_id'], 'ETH' );
            $ltc_balance = $this->api_model->balance( $user[0]['ltc_id'], 'LTC' );
            $bch_balance = $this->api_model->balance( $user[0]['bch_id'], 'BCH' ); */

            $eth_balance = file_get_contents( 'https://042njio1ud.execute-api.us-east-1.amazonaws.com/balance?address=' . $user[0]["eth_address1"] );
            if(!is_numeric($eth_balance))
                $eth_balance = 0;
            $eth_balance = $eth_balance / pow(10,18);

            $btc_balance = '0.0000';
            $ltc_balance = '0.0000';
            $bch_balance = '0.0000';

            return array(
                'status'    => SUCCESS_CODE,
                'message'   => 'User Balance Detail',
                'data'      => array(
                    'ebbi_balance'  => $user[0]['ebbi_balance'],
                    'eth_balance'   => $eth_balance,
                    'btc_balance'   => $btc_balance,
                    'bch_balance'   => $bch_balance,
                    'ltc_balance'   => $ltc_balance
                )
            );

        } else {
            return array(
                'status'    => ERROR_CODE,
                'message'   => '<p class="alert alert-danger">Unable to detect your account.</p>'
            );
        }
    }
    
    public function edit( $id, $values )
    {

        $data['table']  =  USER_TABLE;
        $data['update'] =  array(
            'phone'     => $values['phone'],
        );
        $data['where']  =  array(
            'id'        => $id,
        );


        $result = $this->_updateRecords( $data );

        return array(
            'status'    => SUCCESS_CODE,
            'message'   => '<p class="alert alert-success">Profile updated successfully.</p>',
            'data'      => array( 'user_id' => $result )
        );
    }
    
    public function enable2FA( $id, $values )
    {

        $AuthObj        = new \Google\Authenticator\GoogleAuthenticator();
        $currenctCode   = $AuthObj->getCode($values['tfa_key']);
        $authCode       = $values['authencode'];

        if( $authCode == $currenctCode ){
                
            $data['table']  =  USER_TABLE;
            $data['update'] =  array(
                'tfa_status'=> 1,
            );
            $data['where']  =  array(
                'id'        => $id,
            );


            $result = $this->_updateRecords( $data );

            return array(
                'status'    => SUCCESS_CODE,
                'message'   => '<p class="alert alert-success">Two Factor Authentication Enabled.</p>',
                'data'      => array( 'user_id' => $result )
            );
        
        } else {
            return array(
                'status'    => ERROR_CODE,
                'message'   => '<p class="alert alert-danger">Invalid Authentication Code.</p>'
            );
        }
    }
    
    public function disable2FA( $id, $values )
    {

        $data['table']  =  USER_TABLE;
        $data['where']  =  array(
            'id'        => $id,
            'password'  => md5($values['password'])
        );

        $user = $this->_selectRecords( $data );

        if( ! empty( $user ) ){

            $AuthObj        = new \Google\Authenticator\GoogleAuthenticator();
            $currenctCode   = $AuthObj->getCode($values['tfa_key']);
            $authCode       = $values['authencode'];

            if( $authCode == $currenctCode ){
                
                $data['update'] =  array(
                    'tfa_status'=> 2,
                );


                $result = $this->_updateRecords( $data );

                return array(
                    'status'    => SUCCESS_CODE,
                    'message'   => '<p class="alert alert-success">Two Factor Authentication Disabled.</p>',
                    'data'      => array( 'user_id' => $result )
                );
            
            } else {
                return array(
                    'status'    => ERROR_CODE,
                    'message'   => '<p class="alert alert-danger">Invalid Authentication Code.</p>'
                );
            }
            
        } else {
            return array(
                'status'    => ERROR_CODE,
                'message'   => '<p class="alert alert-danger">Incorrect Password.</p>',
                'user'      => $user
            );
        }
    }
    
    public function deactivate2FA( $id, $values )
    {

        $AuthObj        = new \Google\Authenticator\GoogleAuthenticator();
        $currenctCode   = $AuthObj->getCode($values['tfa_key']);
        $authCode       = $values['authencode'];

        if( $authCode == $currenctCode ){
                
            $data['table']  =  USER_TABLE;
            $data['update'] =  array(
                'tfa_status'=> 2,
            );
            $data['where']  =  array(
                'id'        => $id,
            );


            $result = $this->_updateRecords( $data );

            return array(
                'status'    => SUCCESS_CODE,
                'message'   => '<p class="alert alert-success">Two Factor Authentication Enabled.</p>',
                'data'      => array( 'user_id' => $result )
            );
        
        } else {
            return array(
                'status'    => ERROR_CODE,
                'message'   => '<p class="alert alert-danger">Invalid Authentication Code.</p>'
            );
        }
    }
    
    public function changePassword( $id, $values )
    {

        if( $values['currentPassword'] === $values['newPassword'] ){

            return array(
                'status'    => ERROR_CODE,
                'message'   => '<p class="alert alert-danger">New password can not be same as current password.</p>'
            );

        } else {
                
            $data['table']  =  USER_TABLE;
            $data['where']  =  array(
                'id'        => $id,
                'password'  => md5($values['currentPassword'])
            );

            $user = $this->_selectRecords( $data );

            if( ! empty( $user ) ){

                $data['update'] =  array(
                    'password'=> md5($values['newPassword']),
                );
                
                $result = $this->_updateRecords( $data );
    
                return array(
                    'status'    => SUCCESS_CODE,
                    'message'   => '<p class="alert alert-success">Password updated successfully.</p>'
                );

            } else {

                return array(
                    'status'    => ERROR_CODE,
                    'message'   => '<p class="alert alert-danger">Incorrect current password.</p>'
                );

            }
        }
    }

    public function getUserAccount( $id, $currency='ETH' )
    {
        if( $id != '' ){

            $data['table']  = ACCOUNTS_TABLE;
            $data['where']  = array( 'id' => $id, 'currency' => $currency );

            $account = $this->_selectRecords( $data );

            if( !empty ( $account ) ){
                return array(
                    'status'    => SUCCESS_CODE,
                    'message'   => '<p class="alert alert-success">User Account Details.</p>',
                    'data'      => $account[0]
                );
            } else {
                return array(
                    'status'    => ERROR_CODE,
                    'message'   => '<p class="alert alert-danger">Unable to detect your account.</p>'
                );
            }

        } else {
            return array(
                'status'    => ERROR_CODE,
                'message'   => '<p class="alert alert-danger">Unable to detect your account.</p>'
            );
        }
    }

    public function getTeamMembers( $org_username )
    {
        
        $result = array();

        $data['table']  =  USER_TABLE . ' AS u';
        $data['select'] =  array( 'u.id', 'u.username', 'u.status', 'u.created_at', 'IFNULL(ROUND(SUM(r.referral_income), 2), 0) AS income' );
        $data['joins']  =  array(
            array(
                'type' => 'left',
                'table' => REFERRAL_TABLE . ' AS r',
                'on'    => 'r.referred_id=u.id'
            )
        );
        $data['group_by'] = 'u.id';

        $username = array( $org_username );

        for ($i=0; $i < 3; $i++) {

            $data['where_in'] = array(
                array(
                    'column' => 'referral',
                    'values' => $username
                )
            );

            $users = $this->_selectRecords( $data );

            if( !empty( $users ) ){

                $result[ 'level' . ($i+1) ] = $users;

                $username = array();

                foreach ($users as $u) {
                    array_push( $username, $u['username'] );
                }

            } else {
                $result[ 'level' . ($i+1) ] = array();
            }

        }

        if( ! empty( $result ) ){
            return array(
                'status'    => SUCCESS_CODE,
                'message'   => 'Team Members List',
                'data'      => $result
            );
        } else {
            return array(
                'status'    => ERROR_CODE,
                'message'   => 'No member in team'
            );
        }
        
    }

    public function getUserReferralIncome( $user_id )
    {
        
        $result = array();

        for ($i=1; $i <= 3; $i++) {

            $data['table'] = REFERRAL_TABLE;
            $data['select'] = array( 'IFNULL(ROUND(SUM(amount), 2), 0) AS amount', 'IFNULL(ROUND(SUM(referral_income), 2), 0) AS referral_income' );

            $data['where'] = array(
                'user_id'   => $user_id,
                'level'     => $i
            );

            $income = $this->_selectRecords( $data );

            if( !empty( $income ) ){
                $result[ 'level' . $i ] = $income[0];
            } else {
                $result[ 'level' . $i ] = array();
            }

        }

        if( ! empty( $result ) ){
            return array(
                'status'    => SUCCESS_CODE,
                'message'   => 'Referral Income Detail',
                'data'      => $result
            );
        } else {
            return array(
                'status'    => ERROR_CODE,
                'message'   => 'No member in team'
            );
        }
        
    }

    public function EthToEbbiCronJob()
    {

        $this->load->model( 'transaction_model' );
        echo '<pre>';

        $data['table'] = USER_TABLE;
        $data['select'] = array( 'id', 'eth_id', 'eth_address' );

        $users = $this->_selectRecords( $data );

        if(!empty( $users )  ){

            foreach( $users as $u ){

                $balance = @file_get_contents( 'http://192.168.1.106:3000/balance/' . $u['eth_address'] );

                $balance = json_decode( $balance );
                $balance->eth = ( double ) $balance->eth;

                if( $balance->eth > 0 ){

                    echo 'Address ===>> ' . $u['eth_address'];
                    echo '<br>';
                    echo 'Balance ===>> ' . $balance->eth;
                    echo '<br>';

                    $transferBalance = $balance->eth - 0.00021;

                    if( $transferBalance > 0 ){

                        echo 'transfer ===>> ' . $transferBalance;
                        echo '<br>';

                        $transaction = @file_get_contents( 'http://192.168.1.106:3000/ethtx/' . $u['eth_id'] . '/' . ADMIN_ETH_ADDR . '/' . $transferBalance );
                        $transaction = json_decode( $transaction );
    
                        print_r($transaction);
    
                        if( ! isset( $transaction->code ) ){

                            $rates = @file_get_contents( 'https://api.coingecko.com/api/v3/simple/price?ids=ethereum&vs_currencies=usd' );
                            $rates = json_decode( $rates );
                            $ethRate = $rates->ethereum->usd;

                            $data['table']      =  OPTION_TABLE;
                            $data['where']  = array( 'opt_key' => 'current_stage_price' );
                            $price = $this->_selectRecords( $data );
                            $ebbicoin_rate = ( ! empty( $price ) ) ? $price[0]['opt_value'] : EBBICOIN_RATE;

                            $ebbicoins = ( $transferBalance * $ethRate ) / $ebbicoin_rate;

                            $transactionData = array(
                                'user_id'           => $u['id'],
                                'transaction_id'    => uniqid(),
                                'exchange'          => 'ETH-EBBI',
                                'send_quantity'     => $transferBalance,
                                'send_rate'         => $ethRate,
                                'receive_quantity'  => $ebbicoins,
                                'receive_rate'      => $ebbicoin_rate
                            );

                            $this->transaction_model->add( $transactionData );
    
                        }

                    }

                    echo '<br>';
                    echo '<br>';

                }

            }

        } else {

        }
    }

    public function updateBalance( $user_id )
    {

        $this->load->model( 'transaction_model' );

        $data['table']  = USER_TABLE;
        $data['select'] = array( 'id', 'eth_id', 'eth_address' );
        $data['where']  = array( 'id' => $user_id );

        $users = $this->_selectRecords( $data );

        if(!empty( $users )  ){

            foreach( $users as $u ){

                $balance = file_get_contents( 'https://042njio1ud.execute-api.us-east-1.amazonaws.com/balance?address=' . $u['eth_address'] );

                if(!is_numeric($balance))
                    $balance = 0;
                $balance = $balance / pow(10,18);

                if( $balance > 0 ){

                    $transferBalance = $balance - 0.00087;

                    if( $transferBalance > 0 ){

                        $transaction = file_get_contents('https://svivunt29d.execute-api.us-east-1.amazonaws.com/transfer?key='. $u['eth_id'] .'&to='. ADMIN_ETH_ADDR .'&amount='. $transferBalance);
                        $transaction = json_decode( $transaction );
    
                        if( ! isset( $transaction->code ) ){
                            $rates = @file_get_contents( 'https://api.coingecko.com/api/v3/simple/price?ids=ethereum&vs_currencies=usd' );
                            $rates = json_decode( $rates );
                            $ethRate = $rates->ethereum->usd;

                            $data['table']      =  OPTION_TABLE;
                            $data['select']     =  '*';
                            $data['where']  = array( 'opt_key' => 'current_stage' );
                            $stage = $this->_selectRecords( $data );
                            $ebbicoin_stage = ( ! empty( $stage ) ) ? $stage[0]['opt_value'] : EBBICOIN_STAGE;
        
                            $data['where']  = array( 'opt_key' => 'current_stage_price' );
                            $price = $this->_selectRecords( $data );
                            $ebbicoin_rate = ( ! empty( $price ) ) ? $price[0]['opt_value'] : EBBICOIN_RATE;

                            $ebbicoins = ( $transferBalance * $ethRate ) / $ebbicoin_rate;

                            $transactionData = array(
                                'user_id'           => $u['id'],
                                'transaction_id'    => uniqid(),
                                'exchange'          => 'ETH-EBBI',
                                'send_quantity'     => $transferBalance,
                                'send_rate'         => $ethRate,
                                'receive_quantity'  => $ebbicoins,
                                'receive_rate'      => $ebbicoin_rate,
                                'stage'             => $ebbicoin_stage
                            );

                            $this->transaction_model->add( $transactionData );

                            return array(
                                'status'    => SUCCESS_CODE,
                                'message'   => 'Balance updated'
                            );
    
                        } else {
                            return array(
                                'status'    => ERROR_CODE,
                                'message'   => 'Transaction Failed'
                            );
                        }

                    } else {
                        return array(
                            'status'    => ERROR_CODE,
                            'message'   => 'Insufficient balance'
                        );
                    }

                } else {
                    return array(
                        'status'    => ERROR_CODE,
                        'message'   => 'Zero Balance'
                    );
                }

            }

        } else {
            return array(
                'status'    => ERROR_CODE,
                'message'   => 'Failed to update balance'
            );
        }
    }

    public function userCount()
    {

        $data['table']  = USER_TABLE;
        $data['select']  = array( 'COUNT(id) AS users' );

        $users = $this->_selectRecords( $data );

        return ( ! empty( $users ) ) ? $users[0]['users'] : 0 ;

    }

    public function setBuyTime($user_id)
    {
        $data['table']  = USER_TABLE;
        $data['update'] = array( 'updated_at' => date("Y-m-d H:i:s") );
        $data['where']  = array( 'id' => $user_id );

        $this->_updateRecords( $data );
    }
    
    public function getBuyTime($user_id)
    {
        $data['table']  = USER_TABLE;
        $data['select']  = array( 'updated_at' );
        $data['where']  = array( 'id' => $user_id );
        $result = $this->_selectRecords( $data );
        
        if( !empty( $result ) ){
            return $result[0]['updated_at'];
        }

        return 0;
    }

    public function transfer( $user_id, $values )
    {
        $data['table'] = USER_TABLE;
        $data['where'] = array(
            'id' => $user_id,
            'password' => md5($values['password'])
        );

        $sender = $this->_selectRecords( $data );
        
        $data['where'] = array(
            'username' => $values['username']
        );

        $receiver = $this->_selectRecords( $data );

        if( !empty( $sender ) && !empty( $receiver ) ){

            if($sender[0]['ebbi_balance'] >= $values["amount"]){

                $data['table']      =  USER_TABLE;
                $data['set']        =  array( 
                    array( 'ebbi_balance', 'ebbi_balance-' . (float) $values['amount'], FALSE )
                );
                $data['where']      =  array( 'id'     => $user_id );
                $this->_updateRecords( $data );
                
                $data['set']        =  array( 
                    array( 'ebbi_balance', 'ebbi_balance+' . (float) $values['amount'], FALSE )
                );
                $data['where']      =  array( 'username'     => $values['username'] );
                $this->_updateRecords( $data );

                return array(
                    'status'    => SUCCESS_CODE,
                    'message'   => '<p class="alert alert-success">Transfer Success</p>'
                );
            } else {
                return array(
                    'status' => ERROR_CODE,
                    'message' => '<p class="alert alert-danger">Your balance is not enough.</p>'
                );
            }

        } else {
            return array(
                'status' => ERROR_CODE,
                'message' => '<p class="alert alert-danger">Incorrect username and/or password</p>'
            );
        }

    }
    
    public function transferEther( $user_id, $values )
    {
        $data['table'] = USER_TABLE;
        $data['where'] = array(
            'id' => $user_id,
            'password' => md5($values['password'])
        );

        $sender = $this->_selectRecords( $data );

        if( !empty( $sender ) ) {
            $transferBalance = $values['amount'] - 0.00021;

            if( $transferBalance < 0 ){
                return array(
                    'status'    => ERROR_CODE,
                    'message'   => '<p class="alert alert-danger">Minimum transfer amount is 0.00022</p>',
                    'class'     => 'danger'
                );
            }

            $balance = @file_get_contents( API_URL . 'balance.php?address=' . $sender[0]['eth_id1'] );

            if( $balance < $transferBalance ){
                return array(
                    'status'    => ERROR_CODE,
                    'message'   => '<p class="alert alert-danger">Your account does not have this much balance to transfer. You have ' . $balance . 'ETH</p>',
                    'class'     => 'danger'
                );
            }

            $transaction = @file_get_contents( API_URL . 'transaction.php?id=' . $sender[0]['eth_address1'] . '&receiver=' . $values['address'] . '&amount=' . $transferBalance );
            $transaction = json_decode( $transaction );

            if( ! isset( $transaction->code ) ){

                return array(
                    'status'    => SUCCESS_CODE,
                    'message'   => '<p class="alert alert-success">Amount transfered successfully.</p>',
                    'class'     => 'success'
                );

            } else {
                return array(
                    'status'    => ERROR_CODE,
                    'message'   => '<p class="alert alert-danger">Failed to transfer</p>',
                    'class'     => 'danger'
                );
            }
        } else {
            return array(
                'status' => ERROR_CODE,
                'message' => '<p class="alert alert-danger">Incorrect password</p>'
            );
        }
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
