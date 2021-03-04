<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Display Debug backtrace
|--------------------------------------------------------------------------
|
| If set to TRUE, a backtrace will be displayed along with php errors. If
| error_reporting is disabled, the backtrace will not display, regardless
| of this setting
|
*/
defined('SHOW_DEBUG_BACKTRACE') OR define('SHOW_DEBUG_BACKTRACE', TRUE);

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
defined('FILE_READ_MODE')  OR define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') OR define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE')   OR define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE')  OR define('DIR_WRITE_MODE', 0755);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/
defined('FOPEN_READ')                           OR define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE')                     OR define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE')       OR define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE')  OR define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE')                   OR define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE')              OR define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT')            OR define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT')       OR define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

/*
|--------------------------------------------------------------------------
| Exit Status Codes
|--------------------------------------------------------------------------
|
| Used to indicate the conditions under which the script is exit()ing.
| While there is no universal standard for error codes, there are some
| broad conventions.  Three such conventions are mentioned below, for
| those who wish to make use of them.  The CodeIgniter defaults were
| chosen for the least overlap with these conventions, while still
| leaving room for others to be defined in future versions and user
| applications.
|
| The three main conventions used for determining exit status codes
| are as follows:
|
|    Standard C/C++ Library (stdlibc):
|       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
|       (This link also contains other GNU-specific conventions)
|    BSD sysexits.h:
|       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
|    Bash scripting:
|       http://tldp.org/LDP/abs/html/exitcodes.html
|
*/
defined('EXIT_SUCCESS')         OR define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR')           OR define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG')          OR define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE')    OR define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS')   OR define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD')  OR define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')      OR define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE')        OR define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN')       OR define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')       OR define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code

// response constant
defined('SUCCESS_CODE')         OR define('SUCCESS_CODE', 200);
defined('ERROR_CODE')           OR define('ERROR_CODE', 400);
defined('SUCCESS_CLASS')        OR define('SUCCESS_CLASS', 'success');
defined('ERROR_CLASS')          OR define('ERROR_CLASS', 'danger');
defined('BAD_RESPONSE')         OR define('BAD_RESPONSE', 'Bad Response');
defined('ERROR_RESPONSE')       OR define('ERROR_RESPONSE', '<p class="alert alert-danger">Something went wrong. Please try again.</p>');

// app level constant
defined('APP_NAME')             OR define('APP_NAME', 'Ebbi Coin');
defined('DATE_TIME')            OR define('DATE_TIME', date('Y-m-d H:i:s'));
defined('EMAIL_FROM')           OR define('EMAIL_FROM', 'info@ebbicoin.com');
defined('EMAIL_FROM_NAME')      OR define('EMAIL_FROM_NAME', 'info@ebbicoin.com');

// table name constant
defined('USER_TABLE')           OR define('USER_TABLE', 'users');
defined('LOGIN_TABLE')          OR define('LOGIN_TABLE', 'login');
defined('TRANSACTION_TABLE')    OR define('TRANSACTION_TABLE', 'transactions');
defined('ACCOUNTS_TABLE')       OR define('ACCOUNTS_TABLE', 'accounts');
defined('TICKETS_TABLE')        OR define('TICKETS_TABLE', 'tickets');
defined('TICKET_CMNT_TABLE')    OR define('TICKET_CMNT_TABLE', 'ticket_comments');
defined('CB_TOKENS_TABLE')      OR define('CB_TOKENS_TABLE', 'coinbase_tokens');
defined('ADMIN_TABLE')          OR define('ADMIN_TABLE', 'admin');
defined('REFERRAL_TABLE')       OR define('REFERRAL_TABLE', 'referral_income');
defined('OPTION_TABLE')         OR define('OPTION_TABLE', 'option');

// Referral rates
defined('LEVEL1_RATE')          OR define('LEVEL1_RATE', 10);
defined('LEVEL2_RATE')          OR define('LEVEL2_RATE', 5);
defined('LEVEL3_RATE')          OR define('LEVEL3_RATE', 2);

// 1 EbbiCoin = ? USD
defined('EBBICOIN_RATE')        OR define('EBBICOIN_RATE', 0.7);
defined('EBBICOIN_STAGE')       OR define('EBBICOIN_STAGE', 2);

// Admin Coinbase ETH Address 
defined('ADMIN_ETH_ADDR')       OR define('ADMIN_ETH_ADDR', '0x0d8560552BBE2F7Dd411396A64EAe27Ef9959E68');
defined('ADMIN_ETH_KEY')        OR define('ADMIN_ETH_KEY', '0x3ec737406eb015afb0ffaa3464d0706595dfc4d462e82f8d1592d15c212676b4');
defined('API_URL')              OR define('API_URL', 'http://localhost/John-rework/node-to-php/');

// Coinbase creadentials
defined('COINBASE_REDIRECT_URI')    OR define('COINBASE_REDIRECT_URI', 'http://192.168.1.120/ebbicoin/data/coinbase/auth');
defined('COINBASE_CLIENT_ID')       OR define('COINBASE_CLIENT_ID', 'e8d96fb8d7d05557d097a021b5f32bfb48ff262f20e54a22881ee44f209ea089');
defined('COINBASE_CLIENT_SECRET')   OR define('COINBASE_CLIENT_SECRET', '0f1c654fec80e50dc12040c946dc93a5e7962310e798118fc29629eee1d3faed');

