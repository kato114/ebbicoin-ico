<?php

class User extends MY_Controller
{
    
    function __construct(){
        parent::__construct();
        $this->load->helper('url');
        $this->load->helper('cookie');
        $this->load->model( 'user_model', 'this_model' );
        $this->load->model( 'transaction_model', 'transactions' );
    }

    public function index()
    {
        $this->load->library('utility');
        $token = $this->utility->token();

        $email['to']        = $values['email'];
        $email['subject']   = 'Registration Verification';
        $email['message']   = $this->load->view('email/registration_verification', $view, TRUE);

        $emailResult = $this->utility->email( $email );

        var_dump($emailResult);
        
        echo '<pre>';
        echo "Welcome to EbbiCoin";
    }

    public function referral($code) {
        $cookie = array(
            'name'   => 'referral',
            'value'  => $code,                            
            'expire' =>  time() + (10 * 365 * 24 * 60 * 60),                                                                                   
            'secure' => FALSE
        );
        $this->input->set_cookie($cookie);
        redirect('http://localhost/John-rework/');
    }

    public function login(){

        $_POST = (array) json_decode( file_get_contents( 'php://input' ) );

        if ( $this->form_validation->run() == FALSE ){
            $result = array(
                'status' => ERROR_CODE,
                'message' => validation_errors( '<p class="text-danger">', '</p>' )
            );

        } else {
            $result = $this->this_model->login( $_POST );

        }

        $this->_response( $result );

    }

    public function register(){

        $_POST = (array) json_decode( file_get_contents( 'php://input' ) );
        if ( $this->form_validation->run() === FALSE ){
            $errors = array(
                'status' => ERROR_CODE,
                'message' => validation_errors( '<p class="text-danger">', '</p>' )
            );
            $this->_response( $errors );
            
        } else {
            $result = $this->this_model->register( $_POST );

            // $this->transactions->add(array(
            //     'user_id' => $result['data']['user_id'],
            //     'exchange' => '',
            //     'send_quantity' => 0,
            //     'send_rate' => 0,
            //     'receive_quantity' => 0,
            // ));
            
            $this->_response( $result );
        }

    }

    public function verify( $token = NULL )
    {

        $result = $this->this_model->verify( $token );

    }

    public function forgetPassword()
    {

        $_POST = (array) json_decode(file_get_contents( 'php://input' ));

        if ( $this->form_validation->run() == FALSE ){

            $result = array(
                'status'    => ERROR_CODE,
                'class'     => ERROR_CLASS,
                'message'   => validation_errors( '<label>', '</label>' )
            );
            
        } else {
            $result = $this->this_model->forgetPassword ( $_POST );
        }

        $this->_response( $result );

    }

    public function reset( $token = NULL )
    {
        
        if( $this->input->post() ){

            if ( $this->form_validation->run() === FALSE ){
                $this->load->view( 'reset-password' );
            } else {
                $result = $this->this_model->reset( $token, $this->input->post() );
            }            

        } else {

            $result = $this->this_model->checkToken( $token );

            if( $result === TRUE ){
                $this->load->view( 'reset-password' );
            } else {
                redirect( 'http://localhost/John-rework/' );
            }

        }

    }

    public function loginStatus()
    {
        $_POST = (array) json_decode( file_get_contents( 'php://input' ) );
        $result = $this->this_model->loginStatus( $_POST );
        $this->_response($result);
        
    }

    public function resendEmail()
    {
        $_POST = (array) json_decode( file_get_contents( 'php://input' ) );
        $result = $this->this_model->resendEmail( $_POST );
        $this->_response($result);
    }

    public function getUser( $id = NULL )
    {
        $response = $this->this_model->getUser( $id );
        $this->_response($response);
    }

    public function createWallet( $id = NULL )
    {
        $response = $this->this_model->createWallet( $id );
        $this->_response($response);
    }
    
    public function balance()
    {
        $_POST = (array) json_decode( file_get_contents( 'php://input' ) );
        
        if ( $this->form_validation->run() == FALSE ){

            $result = array(
                'status' => ERROR_CODE,
                'message' => validation_errors( '<p class="text-danger">', '</p>' )
            );

        } else {
            
            $result = $this->this_model->getBalance( $_POST );

        }

        $this->_response( $result );
    }

    public function edit( $id = NULL ){

        $_POST = (array) json_decode( file_get_contents( 'php://input' ) );

        if ( $this->form_validation->run() === FALSE ){

            $errors = array(
                'status' => ERROR_CODE,
                'message' => validation_errors( '<p class="text-danger">', '</p>' )
            );
            $this->_response( $errors );
            
        } else {
            $result = $this->this_model->edit( $id, $_POST );
            $this->_response( $result );
        }

    }

    public function twoFactorAuthQRCode( $username = NULL, $secret = NULL )
    {
        $g = new \Google\Authenticator\GoogleAuthenticator();
        $result = array(
            'status'    => SUCCESS_CODE,
            'message'   => 'QR Generated',
            'data'      => array(
                'link'  => '<img src="'.$g->getURL($username, 'infograins.com', $secret, 'EbbiCoin').'" />'
            ),
        );
        $this->_response( $result );
    }

    public function enable2FA( $id = NULL ){

        $_POST = (array) json_decode( file_get_contents( 'php://input' ) );

        if ( $this->form_validation->run() === FALSE ){

            $errors = array(
                'status' => ERROR_CODE,
                'message' => validation_errors( '<p class="text-danger">', '</p>' )
            );
            $this->_response( $errors );
            
        } else {
            $result = $this->this_model->enable2FA( $id, $_POST );
            $this->_response( $result );
        }

    }

    public function disable2FA( $id = NULL ){

        $_POST = (array) json_decode( file_get_contents( 'php://input' ) );

        if ( $this->form_validation->run() === FALSE ){

            $errors = array(
                'status' => ERROR_CODE,
                'message' => validation_errors( '<p class="text-danger">', '</p>' )
            );
            $this->_response( $errors );
            
        } else {
            $result = $this->this_model->disable2FA( $id, $_POST );
            $this->_response( $result );
        }

    }

    public function deactivate2FA( $id = NULL ){

        $_POST = (array) json_decode( file_get_contents( 'php://input' ) );

        if ( $this->form_validation->run() === FALSE ){

            $errors = array(
                'status' => ERROR_CODE,
                'message' => validation_errors( '<p class="text-danger">', '</p>' )
            );
            $this->_response( $errors );
            
        } else {
            $result = $this->this_model->deactivate2FA( $id, $_POST );
            $this->_response( $result );
        }

    }

    public function changePassword( $id = NULL ){

        $_POST = (array) json_decode( file_get_contents( 'php://input' ) );

        if ( $this->form_validation->run() === FALSE ){

            $errors = array(
                'status' => ERROR_CODE,
                'message' => validation_errors( '<p class="text-danger">', '</p>' )
            );
            $this->_response( $errors );
            
        } else {
            $result = $this->this_model->changePassword( $id, $_POST );
            $this->_response( $result );
        }

    }

    public function getUserAccount( $id = NULL, $currency='ETH' )
    {
        $response = $this->this_model->getUserAccount( $id, $currency );
        $this->_response($response);
    }

    public function getTeamMembers( $username = NULL )
    {
        $response = $this->this_model->getTeamMembers( $username );
        $this->_response($response);
    }

    public function getUserReferralIncome( $user_id = null)
    {
        $response = $this->this_model->getUserReferralIncome( $user_id );
        $this->_response($response);
    }

    public function EthToEbbiCronJob()
    {
        $this->this_model->EthToEbbiCronJob();
    }

    public function updateBalance( $user_id = null)
    {
        $response = $this->this_model->updateBalance( $user_id );
        $this->_response($response);
    }

    public function haveQuestion()
    {
        
        $_POST = (array) json_decode( file_get_contents( 'php://input' ) );

        if( isset( $_POST['email'] ) ){

            $this->load->library( 'utility' );

            $view['question']   = $_POST;

            $email['to']        = 'presales@ebbicoin.com';
            $email['subject']   = 'Have a Question Data';
            $email['message']   = $this->load->view('email/have_question', $view, TRUE);

            $this->utility->email( $email );

            $response = array(
                'status' => SUCCESS_CODE,
                'message' => '<p class="alert alert-success">Your message has been sent. Thank you for visit us.</p>'
            );

            $this->_response($response);

        }

    }

    public function transfer( $user_id = null)
    {
        $_POST = (array) json_decode( file_get_contents( 'php://input' ) );

        if ( $this->form_validation->run() == FALSE || $user_id == null){
            $result = array(
                'status' => ERROR_CODE,
                'message' => validation_errors( '<p class="text-danger">', '</p>' )
            );

        } else {
            $result = $this->this_model->transfer( $user_id, $_POST );

        }

        $this->_response( $result );
    }
    
    public function transferEther( $user_id = null)
    {
        $_POST = (array) json_decode( file_get_contents( 'php://input' ) );

        if ( $this->form_validation->run() == FALSE || $user_id == null){
            $result = array(
                'status' => ERROR_CODE,
                'message' => validation_errors( '<p class="text-danger">', '</p>' )
            );

        } else {
            $result = $this->this_model->transferEther( $user_id, $_POST );

        }

        $this->_response( $result );
    }
    
    public function getOption()
    {
        $_POST = (array) json_decode( file_get_contents( 'php://input' ) );
        $result = $this->this_model->getOption( $_POST );
        $this->_response( $result );
    }
}
