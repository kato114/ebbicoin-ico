<?php

 class Utility
 {

    private $CI;

    function __construcyt ()
    {
        
    }
    
    public function email ( $data )
    {
        $this->CI = & get_instance();
        $config = Array(
            'protocol'  => 'smtp',
            'smtp_host' => 'smtp.mailgun.org',
            'smtp_port' => 587,
            'smtp_user' => 'postmaster@mail.ebbicoin.com',
            'smtp_pass' => '5e2d9576e6d6c56e008337a79b5fee6d-3d0809fb-250c6b02',
            'mailtype'  => 'html', 
            'charset'   => 'iso-8859-1',
            'newline'   => "\r\n",
            'crlf'      => "\r\n"
        );
        $this->CI->load->library('email', $config);
        $this->CI->email->set_mailtype("html");
        $this->CI->email->from("postmaster@mail.ebbicoin.com", "DO NOT RESPOND");
        $this->CI->email->to( $data['to'] );
        $this->CI->email->subject( $data['subject'] );
        $this->CI->email->message( $data['message'] );
        $result = $this->CI->email->send();
        return $result;
    }

    public function token( $length = 32, $type = 'alnum' )
    {
        $this->CI = & get_instance();
        $this->CI->load->helper('string');
        return uniqid() . date('YmdHisu') . random_string( $type, $length );
    }

 }
 