<?php

class MY_Model extends CI_Model
{
    
    function __construct(){
        
        parent::__construct();
        $this->load->database();

    }

    /*  
     *  @function    :- _selectRecords
     *  @params      :- $data array [required] || $where array [optonal] || $resultType string [optional]
     *  @return      :- array || object
     *  @author      :- Hemant Anjana
     *  @created On  :- 2018-01-30 05:20 PM
     *  This function is used for selecting records from database using user's  given table and condition     *  
     */ 
    protected function _selectRecords( $data, $resultType = 'array' ){

        $result = array();

        // set select columns
        if( !empty( $data['select'] ) )
            $this->db->select( $data['select'] );
        else
            $this->db->select('*');

        // set table name
        $this->db->from( $data['table'] );

        // set joins
        if( isset( $data['joins'] ) ){
            foreach ($data['joins'] as $join) {
                if( isset( $join['type'] ) ){
                    $this->db->join( $join['table'], $join['on'], $join['type'] );
                } else {
                    $this->db->join( $join['table'], $join['on'] );
                }
            }
        }

        // set if there any condition
        if( !empty( $data['where'] ) )
            $this->db->where( $data['where'] );

        // set if there any wher in condition
        if( isset( $data['where_in'] ) )
        {
            foreach( $data['where_in'] as $where_in )
            {
                $this->db->where_in( $where_in['column'], $where_in['values'] );
            }            
        }

        // like condition
        if( isset( $data['like'] ) ){
            foreach( $data['like'] as $like ){
                $this->db->like($like['column'], $like['value']);
            }
        }

        // group by
        if( isset( $data['group_by'] ) ){
            $this->db->group_by( $data['group_by'] );
        }

        // order by statement
        if( isset( $data['order_by'] ) )
        {
            foreach( $data['order_by'] as $order_by ){
                if( isset( $order_by['order'] ) )
                {
                    $this->db->order_by( $order_by['column'], $order_by['order'] );
                } else {
                    $this->db->order_by( $order_by['column'] );
                }
            }
            
        }

        // get data
        $result = $this->db->get();
        
        // result type
        if( $resultType == 'array' )
            $records = $result->result_array();
        else if( $resultType == 'object' )
            $records = $result->result_object();
        else
            $records = $result->result();

        // return query records
        return $records;

    }

    /*  
     *  @Function    :- _insertRecord
     *  @params      :- $data array [required]
     *  @return      :- boolean || integer
     *  @Author      :- Hemant Anjana
     *  @Created On  :- 2018-01-30 05:38 PM
     *  This function is used for inserting data in database
     */ 
    protected function _insertRecord( $data ){

        // insert data
        $this->db->insert($data['table'], $data['insert']);

        // get inserted id
        $id = $this->db->insert_id();

        // check data insert or not and return result
        if( $id !== '' )
            return $id;
        else
            return FALSE;

    }

    /*  
     *  @Function    :- _insertBatchRecords
     *  @params      :- $data array [required]
     *  @return      :- boolean || integer
     *  @Author      :- Hemant Anjana
     *  @Created On  :- 2018-01-30 05:50 PM
     *  This function is used for inserting bulk records in database
     */ 
    protected function _insertBatchRecords( $data ){

        // insert data
        $this->db->insert_batch($data['table'], $data['insert']);

        // get inserted id
        $id = $this->db->insert_id();

        // check data insert or not and return result
        if( $id !== '' )
            return $id;
        else
            return FALSE;

    }

    /*  
     *  @Function    :- _updateRecords
     *  @params      :- $data array [required]
     *  @return      :- boolean || integer
     *  @Author      :- Hemant Anjana
     *  @Created On  :- 2018-01-30 05:42 PM
     *  This function is used for updating data in database
     */ 
    protected function _updateRecords( $data ){

        // set condition
        $this->db->where($data['where']);

        // set values
        if( isset( $data['set'] ) ){
            foreach( $data['set'] as $set ){
                if( isset( $set[2] ) )
                    $this->db->set( $set[0],  $set[1],  $set[2] );
                else
                    $this->db->set( $set[0],  $set[1] );
            }
        }

        // update data
        if( isset ( $data['update'] ) ){
            $this->db->update($data['table'], $data['update']);
        } else {
            $this->db->update($data['table']);
        }
        

        // get affected rows count
        $rows = $this->db->affected_rows();
        
        // check data update or not and return result
        if( $rows > 0 )
            return $rows;
        else
            return FALSE;

    }

    /*  
     *  @Function    :- _deleteRecord
     *  @params      :- $data array [required]
     *  @return      :- boolean
     *  @Author      :- Hemant Anjana
     *  @Created On  :- 2018-01-30 05:47 PM
     *  This function is used for delete data from database
     */ 
    protected function _deleteRecord( $data ){

        // set condition
        $this->db->where($data['where']);

        // delete data
        $this->db->delete($data['table']);

        // get affected rows count
        $rows = $this->db->affected_rows();

        // check data delete or not and return result
        if( $rows > 0 )
            return TRUE;
        else
            return FALSE;

    }

    /*  
     *  @Function    :- _curlRequest
     *  @params      :- $data array [required]
     *  @return      :- any
     *  @Author      :- Hemant Anjana
     *  @Created On  :- 2018-02-22 02:00 PM
     *  This function is used for make any request with curl
     */
    protected function _curlRequest( $data )
    {

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $data['url']);

        if( isset( $data['post'] ) && $data['post'] === TRUE ){
            curl_setopt($ch, CURLOPT_POST, 1);
            if( isset( $data['params'] ) ){
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data['params']);
            }
        }

        if( isset( $data['header'] ) ){
            curl_setopt($ch, CURLOPT_HTTPHEADER, 
                array(                                                                          
                    'Content-Type: ' . $data['header'],                                                                                
                    'Content-Length: ' . strlen( $data['params'] )
                )                                                                       
            ); 
        }

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec ($ch);
        curl_close ($ch);

        return $response;

    }

}
