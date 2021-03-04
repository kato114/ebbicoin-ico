<?php

echo file_get_contents( 'https://042njio1ud.execute-api.us-east-1.amazonaws.com/balance?address=' . $_GET['address'] );