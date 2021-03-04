<?php

$config = array(
    'user/register' => array(
        array(
            'field' => 'username',
            'label' => 'Username',
            'rules' => 'trim|required|is_unique[users.username]',
            'errors' => array(
                'required' => 'Username can not be empty',
                'is_unique' => 'This username is already taken.',
            )
        ),
        array(
            'field' => 'email',
            'label' => 'Email',
            'rules' => 'trim|required|valid_email|is_unique[users.email]',
            'errors' => array(
                'required' => 'Email can not be empty',
                'is_unique' => 'This email is already registered with us.',
            )
        ),
        array(
            'field' => 'password',
            'label' => 'Password',
            'rules' => 'trim|required',
            'errors' => array(
                'required' => 'Password can not be empty'
            )
        ),
        array(
            'field' => 'confPassword',
            'label' => 'Confirm Password',
            'rules' => 'trim|required|matches[password]',
            'errors' => array(
                'required' => 'Confirm Password can not be empty',
                'matches' => 'Password did not match.',
            )
        ),
    ),
    'user/login'    => array(
        array(
            'field' => 'username',
            'label' => 'Username',
            'rules' => 'trim|required',
        ),
        array(
            'field' => 'password',
            'label' => 'Password',
            'rules' => 'trim|required',
        ),
    ),
    'user/transfer'    => array(
        array(
            'field' => 'username',
            'label' => 'Username',
            'rules' => 'trim|required',
        ),
        array(
            'field' => 'password',
            'label' => 'Password',
            'rules' => 'trim|required',
        ),
        array(
            'field' => 'amount',
            'label' => 'Amount',
            'rules' => 'trim|required',
        ),
    ),
    'user/transferEther'    => array(
        array(
            'field' => 'address',
            'label' => 'Address',
            'rules' => 'trim|required',
        ),
        array(
            'field' => 'password',
            'label' => 'Password',
            'rules' => 'trim|required',
        ),
        array(
            'field' => 'amount',
            'label' => 'Amount',
            'rules' => 'trim|required',
        ),
    ),
    'user/forgetPassword'    => array(
        array(
            'field' => 'email',
            'label' => 'Email',
            'rules' => 'trim|required|valid_email',
        ),
    ),
    'user/reset' => array(
        array(
            'field' => 'password',
            'label' => 'Password',
            'rules' => 'trim|required',
            'errors' => array(
                'required' => 'Password can not be empty'
            )
        ),
        array(
            'field' => 'confPassword',
            'label' => 'Confirm Password',
            'rules' => 'trim|required|matches[password]',
            'errors' => array(
                'required' => 'Confirm Password can not be empty',
                'matches' => 'Password did not match.',
            )
        ),
    ),
    'user/balance' => array(
        array(
            'field'  => 'user_id',
            'label'  => 'User ID',
            'rules'  => 'trim|required',
            'errors' => array(
                'required'  => 'User ID can not be empty',
            )
        ),
    ),
    'user/edit' => array(
        array(
            'field' => 'phone',
            'label' => 'Phone Number',
            'rules' => 'trim|required',
            'errors' => array(
                'required' => 'Phone Number can not be empty'
            )
        ),
    ),
    'user/enable2FA' => array(
        array(
            'field' => 'tfa_key',
            'label' => '2FA Key',
            'rules' => 'trim|required',
            'errors' => array(
                'required' => '2FA Key can not be empty'
            )
        ),
        array(
            'field' => 'authencode',
            'label' => 'Authentication Code',
            'rules' => 'trim|required',
            'errors' => array(
                'required' => 'Authentication Code can not be empty'
            )
        ),
    ),
    'user/disable2FA' => array(
        array(
            'field' => 'tfa_key',
            'label' => '2FA Key',
            'rules' => 'trim|required',
            'errors' => array(
                'required' => '2FA Key can not be empty'
            )
        ),
        array(
            'field' => 'password',
            'label' => 'Password',
            'rules' => 'trim|required',
            'errors' => array(
                'required' => 'Password can not be empty'
            )
        ),
        array(
            'field' => 'authencode',
            'label' => 'Authentication Code',
            'rules' => 'trim|required',
            'errors' => array(
                'required' => 'Authentication Code can not be empty'
            )
        ),
    ),
    'user/deactivate2FA' => array(
        array(
            'field' => 'tfa_key',
            'label' => '2FA Key',
            'rules' => 'trim|required',
            'errors' => array(
                'required' => '2FA Key can not be empty'
            )
        ),
        array(
            'field' => 'authencode',
            'label' => 'Authentication Code',
            'rules' => 'trim|required',
            'errors' => array(
                'required' => 'Authentication Code can not be empty'
            )
        ),
    ),
    'user/changePassword' => array(
        array(
            'field' => 'currentPassword',
            'label' => 'Currenct password',
            'rules' => 'trim|required',
            'errors' => array(
                'required' => 'Currenct password can not be empty'
            )
        ),
        array(
            'field' => 'newPassword',
            'label' => 'New password',
            'rules' => 'trim|required',
            'errors' => array(
                'required' => 'New password can not be empty'
            )
        ),
        array(
            'field' => 'confPassword',
            'label' => 'Confirm password',
            'rules' => 'trim|required',
            'errors' => array(
                'required' => 'Confirm password can not be empty'
            )
        ),
    ),
    'transaction/index' => array(
        array(
            'field'  => 'user_id',
            'label'  => 'User ID',
            'rules'  => 'trim|required',
            'errors' => array(
                'required'  => 'User ID can not be empty',
            )
        ),
    ),
    'transaction/add' => array(
        array(
            'field'  => 'user_id',
            'label'  => 'User ID',
            'rules'  => 'trim|required',
            'errors' => array(
                'required'  => 'User ID can not be empty',
            )
        ),
        array(
            'field'  => 'exchange',
            'label'  => 'Exchange',
            'rules'  => 'trim|required',
            'errors' => array(
                'required'  => 'Exchange type type not found',
            )
        ),
        array(
            'field'  => 'send_quantity',
            'label'  => 'Send Quantity',
            'rules'  => 'trim|required',
            'errors' => array(
                'required'  => 'Send Quantity not found',
            )
        ),
        array(
            'field'  => 'send_rate',
            'label'  => 'Send Rate',
            'rules'  => 'trim|required',
            'errors' => array(
                'required'  => 'Send Rate not found',
            )
        ),
        array(
            'field'  => 'receive_quantity',
            'label'  => 'Receive Quantity',
            'rules'  => 'trim|required',
            'errors' => array(
                'required'  => 'Receive Quantity not found',
            )
        ),
        array(
            'field'  => 'receive_rate',
            'label'  => 'Receive Rate',
            'rules'  => 'trim|required',
            'errors' => array(
                'required'  => 'Receive Rate not found',
            )
        ),
    ),
    'transaction/buyCoin' => array(
        array(
            'field'  => 'user_id',
            'label'  => 'User ID',
            'rules'  => 'trim|required',
            'errors' => array(
                'required'  => 'User ID can not be empty',
            )
        ),
        array(
            'field'  => 'exchange',
            'label'  => 'Exchange',
            'rules'  => 'trim|required',
            'errors' => array(
                'required'  => 'Exchange type type not found',
            )
        ),
        array(
            'field'  => 'send_quantity',
            'label'  => 'Send Quantity',
            'rules'  => 'trim|required',
            'errors' => array(
                'required'  => 'Send Quantity not found',
            )
        ),
        array(
            'field'  => 'send_rate',
            'label'  => 'Send Rate',
            'rules'  => 'trim|required',
            'errors' => array(
                'required'  => 'Send Rate not found',
            )
        ),
        array(
            'field'  => 'receive_quantity',
            'label'  => 'Receive Quantity',
            'rules'  => 'trim|required',
            'errors' => array(
                'required'  => 'Receive Quantity not found',
            )
        ),
        array(
            'field'  => 'receive_rate',
            'label'  => 'Receive Rate',
            'rules'  => 'trim|required',
            'errors' => array(
                'required'  => 'Receive Rate not found',
            )
        ),
    ),
    'support/add' => array(
        array(
            'field'  => 'user_id',
            'label'  => 'User ID',
            'rules'  => 'trim|required',
            'errors' => array(
                'required'  => 'User ID not found',
            )
        ),
        array(
            'field'  => 'title',
            'label'  => 'Title',
            'rules'  => 'trim|required',
            'errors' => array(
                'required'  => 'Title not found',
            )
        ),
        array(
            'field'  => 'description',
            'label'  => 'Description',
            'rules'  => 'trim|required',
            'errors' => array(
                'required'  => 'Description not found',
            )
        ),
    ),
    'support/addComment' => array(
        array(
            'field'  => 'user_id',
            'label'  => 'User ID',
            'rules'  => 'trim|required',
            'errors' => array(
                'required'  => 'User ID not found',
            )
        ),
        array(
            'field'  => 'comment',
            'label'  => 'Comment',
            'rules'  => 'trim|required',
            'errors' => array(
                'required'  => 'Comment not found',
            )
        ),
    ),
    'coinbase/wallet_accounts_read' => array(
        array(
            'field'  => 'scope',
            'label'  => 'Scope',
            'rules'  => 'trim|required',
            'errors' => array(
                'required'  => 'Scope not found',
            )
        ),
        array(
            'field'  => 'user_id',
            'label'  => 'User ID',
            'rules'  => 'trim|required',
            'errors' => array(
                'required'  => 'User ID not found',
            )
        ),
    ),
    'coinbase/wallet_addresses_read' => array(
        array(
            'field'  => 'account_id',
            'label'  => 'Account ID',
            'rules'  => 'trim|required',
            'errors' => array(
                'required'  => 'Account ID not found',
            )
        ),
        array(
            'field'  => 'user_id',
            'label'  => 'User ID',
            'rules'  => 'trim|required',
            'errors' => array(
                'required'  => 'User ID not found',
            )
        ),
    ),
    'coinbase/wallet_buys_create' => array(
        array(
            'field'  => 'user_id',
            'label'  => 'User ID',
            'rules'  => 'trim|required',
            'errors' => array(
                'required'  => 'User ID not found',
            )
        ),
        array(
            'field'  => 'currency',
            'label'  => 'Currency',
            'rules'  => 'trim|required',
            'errors' => array(
                'required'  => 'Currency not found',
            )
        ),
        array(
            'field'  => 'payment_method',
            'label'  => 'Payment Method',
            'rules'  => 'trim|required',
            'errors' => array(
                'required'  => 'Payment Method not found',
            )
        ),
        array(
            'field'  => 'send_quantity',
            'label'  => 'Send Quantity',
            'rules'  => 'trim|required',
            'errors' => array(
                'required'  => 'Send Quantity not found',
            )
        ),
        array(
            'field'  => 'send_rate',
            'label'  => 'Send rate',
            'rules'  => 'trim|required',
            'errors' => array(
                'required'  => 'Send rate not found',
            )
        ),
        array(
            'field'  => 'receive_quantity',
            'label'  => 'Receive quantity',
            'rules'  => 'trim|required',
            'errors' => array(
                'required'  => 'Receive quantity not found',
            )
        ),
        array(
            'field'  => 'receive_rate',
            'label'  => 'Receive rate',
            'rules'  => 'trim|required',
            'errors' => array(
                'required'  => 'Receive rate not found',
            )
        ),
    ),
    'coinbase/wallet_payment_methods_read' => array(
        array(
            'field'  => 'user_id',
            'label'  => 'User ID',
            'rules'  => 'trim|required',
            'errors' => array(
                'required'  => 'User ID not found',
            )
        ),
    ),
    'coinbase/wallet_transactions_send' => array(
        array(
            'field'  => 'user_id',
            'label'  => 'User ID',
            'rules'  => 'trim|required',
            'errors' => array(
                'required'  => 'User ID not found',
            )
        ),
        array(
            'field'  => 'to',
            'label'  => 'To',
            'rules'  => 'trim|required',
            'errors' => array(
                'required'  => 'Receiver address not found',
            )
        ),
        array(
            'field'  => 'currency',
            'label'  => 'Currency',
            'rules'  => 'trim|required',
            'errors' => array(
                'required'  => 'Currency not found',
            )
        ),
        array(
            'field'  => 'send_quantity',
            'label'  => 'Send Quantity',
            'rules'  => 'trim|required',
            'errors' => array(
                'required'  => 'Send Quantity not found',
            )
        ),
        array(
            'field'  => 'send_rate',
            'label'  => 'Send rate',
            'rules'  => 'trim|required',
            'errors' => array(
                'required'  => 'Send rate not found',
            )
        ),
        array(
            'field'  => 'receive_quantity',
            'label'  => 'Receive quantity',
            'rules'  => 'trim|required',
            'errors' => array(
                'required'  => 'Receive quantity not found',
            )
        ),
        array(
            'field'  => 'receive_rate',
            'label'  => 'Receive rate',
            'rules'  => 'trim|required',
            'errors' => array(
                'required'  => 'Receive rate not found',
            )
        ),
    ),
    'admin/login/adminLogin'    => array(
        array(
            'field' => 'username',
            'label' => 'Username',
            'rules' => 'trim|required',
        ),
        array(
            'field' => 'password',
            'label' => 'Password',
            'rules' => 'trim|required',
        ),
    ),
    'admin/users/add' => array(
        array(
            'field' => 'username',
            'label' => 'Username',
            'rules' => 'trim|required|is_unique[users.username]',
            'errors' => array(
                'required' => 'Username can not be empty',
                'is_unique' => 'This username is already taken.',
            )
        ),
        array(
            'field' => 'email',
            'label' => 'Email',
            'rules' => 'trim|required|valid_email|is_unique[users.email]',
            'errors' => array(
                'required' => 'Email can not be empty',
                'is_unique' => 'This email is already registered with us.',
            )
        ),
        array(
            'field' => 'password',
            'label' => 'Password',
            'rules' => 'trim|required',
            'errors' => array(
                'required' => 'Password can not be empty'
            )
        ),
        array(
            'field' => 'confPassword',
            'label' => 'Confirm Password',
            'rules' => 'trim|required|matches[password]',
            'errors' => array(
                'required' => 'Confirm Password can not be empty',
                'matches' => 'Password did not match.',
            )
        ),
    ),
    'admin/users/changeStatus'    => array(
        array(
            'field' => 'id',
            'label' => 'User ID',
            'rules' => 'trim|required',
        ),
        array(
            'field' => 'status',
            'label' => 'Status',
            'rules' => 'trim|required',
        ),
    ),
    'admin/users/edit' => array(
        array(
            'field'  => 'id',
            'label'  => 'User ID',
            'rules'  => 'trim|required',
            'errors' => array(
                'required'  => 'User ID can not be empty',
            )
        ),
        array(
            'field'  => 'username',
            'label'  => 'Username',
            'rules'  => 'trim|required',
            'errors' => array(
                'required'  => 'Username can not be empty',
            )
        ),
        array(
            'field' => 'email',
            'label' => 'Email',
            'rules' => 'trim|required|valid_email',
            'errors' => array(
                'required'  => 'Email can not be empty',
            )
        ),
    ),
    'admin/users/addUserEbbiCoinBalance' => array(
        array(
            'field'  => 'user_id',
            'label'  => 'User ID',
            'rules'  => 'trim|required',
            'errors' => array(
                'required'  => 'User ID can not be empty',
            )
        ),
        array(
            'field'  => 'coin',
            'label'  => 'Coin',
            'rules'  => 'trim|required',
            'errors' => array(
                'required'  => 'Coin can not be empty',
            )
        ),
    ),
);