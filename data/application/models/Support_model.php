<?php

class Support_model extends MY_Model
{

    public function ticketList( $user_id )
    {
        
        $data['table'] = TICKETS_TABLE;
        $data['where'] = array( 'user_id' => $user_id );
        $data['order_by'] = array(
            array(
                'column' => 'id',
                'order'  => 'DESC'
            )
        );

        $result = $this->_selectRecords( $data );
        
        return array(
            'status'    => SUCCESS_CODE,
            'message'   => '<p class="alert alert-success">Ticket List</p>',
            'data'      => $result
        );

    }

    public function getTickets()
    {
        
        $data['table'] = TICKETS_TABLE;
        $data['order_by'] = array(
            array(
                'column' => 'id',
                'order'  => 'DESC'
            )
        );

        $result = $this->_selectRecords( $data );
        
        return array(
            'status'    => SUCCESS_CODE,
            'message'   => '<p class="alert alert-success">Ticket List</p>',
            'data'      => $result
        );

    }

    public function getTicket( $ticket_id )
    {
        
        $data['table'] = TICKETS_TABLE;
        $data['where'] = array( 'id' => $ticket_id );
        $data['order_by'] = array(
            array(
                'column' => 'id',
                'order'  => 'DESC'
            )
        );

        $result = $this->_selectRecords( $data );

        if( ! empty( $result ) ){

            $data['table']  = TICKET_CMNT_TABLE . ' AS tc';
            $data['select'] = array( 'tc.comment', 'tc.image', 'tc.created_at', 'IFNULL(u.username, "admin") AS username' );
            $data['joins']  = array(
                array(
                    'table' => USER_TABLE . ' AS u',
                    'on'    => 'u.id=tc.user_id',
                    'type'  => 'LEFT'
                )
            );
            $data['where'] = array( 'ticket_id' => $ticket_id );
            $data['order_by'] = array(
                array(
                    'column' => 'tc.id',
                    'order'  => 'DESC'
                )
            );

            $comments = $this->_selectRecords( $data );

            $ticket = array(
                'ticket'    => $result[0],
                'comments'  => $comments
            );

            return array(
                'status'    => SUCCESS_CODE,
                'message'   => '<p class="alert alert-success">Ticket List</p>',
                'data'      => $ticket
            );

        } else {
            
            return array(
                'status'    => ERROR_CODE,
                'message'   => '<p class="alert alert-success">Ticket detail not found</p>'
            );

        }       
        

    }
    
    public function add( $values )
    {

        $image = "";

        if( $values['image'] !== "" ){
            $image = uniqid().$values['image']->filename;
            @file_put_contents(APPPATH . '../assets/tickets/' . $image, base64_decode($values['image']->value));
        }
        
        $data['table'] = TICKETS_TABLE;
        $data['insert'] = array(
            'user_id'       => $values['user_id'],
            'title'         => $values['title'],
            'description'   => $values['description'],
            'image'         => $image,
            'status'        => 0,
            'created_at'    => DATE_TIME
        );

        $result = $this->_insertRecord( $data );

        if( $result !== FALSE ){

            $this->load->model( 'user_model' );
            $this->load->library( 'utility' );

            $user = $this->user_model->getUser( $values['user_id'] );

            if( !empty( $user['data'] ) ){

                $view['username']   = $user['data']['username'];
    
                $email['to']        = $user['data']['email'];
                $email['subject']   = 'EbbiCoin Support Ticket';
                $email['message']   = $this->load->view('email/ticket_generate', $view, TRUE);
    
                $emailResult = $this->utility->email( $email );

            }
            
            return array(
                'status'    => SUCCESS_CODE,
                'message'   => '<p class="alert alert-success">Ticket Added Successfully</p>'
            );

        } else {
            
            return array(
                'status'    => SUCCESS_CODE,
                'message'   => '<p class="alert alert-danger">Failed to add ticket</p>'
            );

        }

    }
    
    public function addComment( $values )
    {

        $image = "";

        if( $values['image'] !== "" ){
            $image = uniqid().$values['image']->filename;
            file_put_contents(APPPATH . '../assets/tickets/' . $image, base64_decode($values['image']->value));
        }
        
        $data['table'] = TICKET_CMNT_TABLE;
        $data['insert'] = array(
            'ticket_id'     => $values['ticket_id'],
            'user_id'       => $values['user_id'],
            'comment'       => $values['comment'],
            'image'         => $image,
            'created_at'    => DATE_TIME
        );

        $result = $this->_insertRecord( $data );

        if( $result !== FALSE ){
            $ticket = $this->getTicket( $values['ticket_id'] );

            $data['table']  =  TICKETS_TABLE;
            $data['update'] =  array(
                'status'    => ( $ticket['data']['ticket']['user_id'] == $values['user_id'] ? 0 : 1 )
            );
            $data['where']  =  array(
                'id'        => $values['ticket_id']
            );
    
            $result         =  $this->_updateRecords( $data );

            $ticket = $this->getTicket( $values['ticket_id'] );

            $this->load->model( 'user_model' );
            $this->load->library( 'utility' );

            $user = $this->user_model->getUser( $ticket['data']['ticket']['user_id'] );

            if( !empty( $user['data'] ) ){

                $view['username']   = $user['data']['username'];
                $view['commentor']  = ( $ticket['data']['ticket']['user_id'] == $values['user_id'] ) ? 'You' : 'Admin';
                $view['comment']    = $values['comment'];
    
                $email['to']        = $user['data']['email'];
                $email['subject']   = 'EbbiCoin Support Ticket';
                $email['message']   = $this->load->view('email/ticket_comment', $view, TRUE);
    
                $emailResult = $this->utility->email( $email );

            }
            
            return array(
                'status'    => SUCCESS_CODE,
                'message'   => '<p class="alert alert-success">Comment Added Successfully</p>'
            );

        } else {
            
            return array(
                'status'    => SUCCESS_CODE,
                'message'   => '<p class="alert alert-danger">Failed to add Comment</p>'
            );

        }

    }

    public function closeTicket( $ticket_id )
    {
        $data['table']  =  TICKETS_TABLE;
        $data['update'] =  array(
            'status'    => 2
        );
        $data['where']  =  array(
            'id'        => $ticket_id
        );

        $result         =  $this->_updateRecords( $data );

        return array(
            'status'    => SUCCESS_CODE,
            'message'   => 'This ticket has been closed successfully.',
            'class'     => 'success'
        );

    }

}
