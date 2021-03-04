<?php

class Login_model extends MY_Model
{
    
    public function __construct()
    {
        parent::__construct();
    }

    public function login( $values )
    {
        $data['table'] = ADMIN_TABLE;
        $data['where'] = array(
            'username' => $values['username'],
            'password' => md5($values['password'])
        );

        $result = $this->_selectRecords( $data );

        if( !empty( $result ) ){

            $login['table']     =  ADMIN_TABLE;
            $login['update']    =  array(
                'status'        => 1
            );
            $login['where']     = array(
                'id'            => 1
            );

            $loginResult = $this->_updateRecords( $login );
            return array(
                'status'    => SUCCESS_CODE,
                'message'   => '<p class="alert alert-success">Logged in successfully. You will be redirected in 3 seconds.</p>'
            );

            // if( $loginResult !== FALSE ){
            // } else {
            //     return array(
            //         'status' => ERROR_CODE,
            //         'message' => '<p class="alert alert-warning">Your account has been verified but due to some issue can not login to your account now. Please try after some time.</p>',
            //     );
            // }

        } else {
            return array(
                'status' => ERROR_CODE,
                'message' => '<p class="alert alert-danger">Incorrect username and/or password</p>'
            );
        }

    }

    public function loginStatus( $values )
    {
        $data['table'] = ADMIN_TABLE;
        $data['where'] = array(
            'status'   => 1
        );

        $result = $this->_selectRecords( $data );

        if( !empty( $result ) ){
            return array(
                'status'    => SUCCESS_CODE,
                'message'   => 'User detail'
            );
        } else {
            return array(
                'status'    => ERROR_CODE,
                'message'   => 'User not found'
            );
        }

    }

    public function logout( $values )
    {
        $login['table']     = ADMIN_TABLE;
        $login['update']    = array( 'status'  => 0 );
        $login['where']     = array( 'id'      => 1 );
        
        $loginResult = $this->_updateRecords( $login );

        return array(
            'status'    => SUCCESS_CODE,
            'message'   => '<p class="alert alert-success">Logged Out Successfully</p>'
        );
    }

    public function changePassword( $values )
    {
        $login['table']     = ADMIN_TABLE;
        $login['update']    = array( 'password'     => md5( $values['password'] ) );
        $login['where']     = array( 'id'           => 1 );
        
        $loginResult = $this->_updateRecords( $login );

        return array(
            'status'    => SUCCESS_CODE,
            'class'     => 'success',
            'message'   => 'Password updated successfully.'
        );
        
    }

}
