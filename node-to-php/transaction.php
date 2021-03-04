<?php

echo file_get_contents( 'https://svivunt29d.execute-api.us-east-1.amazonaws.com/transfer/' . $_GET['id'] . '/' . $_GET['receiver'] . '/' . $_GET['amount'] );