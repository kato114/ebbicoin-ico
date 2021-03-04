<?php

class Option_model extends MY_Model
{
    public function getOption( $key )
    {
		$data['table']  =  OPTION_TABLE;
		$data['where']  = array( 'opt_key' => $key );

		$result = $this->_selectRecords( $data );

		if( !empty( $result ) )
			return $result[0]["opt_value"];
		else
			return '';
    }
}
