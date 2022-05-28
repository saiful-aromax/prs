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
defined('SITE_NAME') OR define('SITE_NAME', 'CI 3.1.4');
defined('EXT') OR define('EXT', '.php');


// Software Name & Version
define('SOFTWARE_NAME', 'Omega ERP');
define('SOFTWARE_NAME_VERSION', ' v-1.0.0');


define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

define('DEFINE_ROLE_ID', 2);
define('DECIMAL_PLACE', 4);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

define('FOPEN_READ', 'rb');
define('FOPEN_READ_WRITE', 'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE', 'ab');
define('FOPEN_READ_WRITE_CREATE', 'a+b');
define('FOPEN_WRITE_CREATE_STRICT', 'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

//Multisite configuration - detecting configuration based on URL
$organization = 'CLI';
if (isset($_SERVER['REMOTE_ADDR'])) {
    //If not from command line
    if (isset($_SERVER['REQUEST_URI'])) {
        $path = $_SERVER['REQUEST_URI'];
        $url_segment = explode("/", $path);
        $organization = $url_segment[1];     // For Local Server
        //$organization=$url_segment[2];   // For LIVE Server
    }
}
if (defined('CIUnit_Version') || defined('SIMPLETEST')) {
    $organization = 'test';
}

//define('SITE_NAME', $organization);

// Define Ajax Request
define('IS_AJAX', isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
//  
define('DISPLAY_DATE_FORMAT', 'F j, Y'); //	Date formate

define('ROW_PER_PAGE', 15); //	Used in pagination
// Image

define('IMAGE_UPLOAD_PATH', 'media/images/' . SITE_NAME . '_picture/');
define('IMAGE_UPLOAD_SIZE', 2000);
define('IMAGE_UPLOAD_MAX_WIDTH', 1000);
define('IMAGE_UPLOAD_MAX_HEIGHT', 1000);
define('IMAGE_UPLOAD_ALLOWED_TYPES', 'gif|jpg|jpeg|png');

// Signature
define('MEMBER_SIGNATURE_IMAGE_UPLOAD_PATH', 'media/images/' . SITE_NAME . '/member_signature/');
define('SIGNATURE_IMAGE_UPLOAD_SIZE', 50);
define('SIGNATURE_IMAGE_UPLOAD_MAX_WIDTH', 200);
define('SIGNATURE_IMAGE_UPLOAD_MAX_HEIGHT', 100);

//notification text file
define('Notification_text_file', 'application/logs/global_notification.txt');

//Form Field Size
define('ROLE_ID_PRIVILEGE', 1);

//Form Field Size
define('FIELD_MAX_SIZE_NOTE', 255);

//Invoice Form height
define('INVOICE_FULL_HEIGHT', 100);
define('INVOICE_PER_ROW_HEIGHT', 40);

//sytem message for end users
define('DELETE_CONFIRMATION_MESSAGE', 'Are you sure want to delete this data?'); //	Message for delete confirmation
define('REJECT_CONFIRMATION_MESSAGE', 'Are you sure want to reject this data?');
define('APPROVE_CONFIRMATION_MESSAGE', 'Are you sure want to approve this data?');
define('UNAPPROVE_CONFIRMATION_MESSAGE', 'Are you sure want to unapprove this data?');
define('DEPENDENT_DATA_FOUND', 'Sorry! Information can\'t be deleted. Dependent data found.');
define('DELETE_ERROR_MESSAGE', 'Sorry! Information can\'t be deleted.');
define('INVALID_PARAM', 'Wrong data.');
define('ADD_MESSAGE', 'Data has been added successfully');
define('ORD_RECEIVE_MESSAGE', 'Order Data has been Received successfully');
define('EDIT_CONFIRMATION_MESSAGE', 'Are you sure want to edit this data?'); //	Message for edit confirmation
define('EDIT_MESSAGE', 'Data has been updated successfully');
define('DELETE_MESSAGE', 'Data has been deleted successfully');
define('REJECT_MESSAGE', 'Data has been rejected successfully');
define('APPROVE_MESSAGE', 'Data has been approved successfully');
define('UNAPPROVE_MESSAGE', 'Data has been unapproved successfully');
define('DELETE_SYSTEM_ENTITY_MESSAGE', 'You are trying to delete a system entity. This data can not be deleted.');
define('EXISTS', 'Combination already exists!');
/*------ Constants for Accounting Module -------*/
define('ASSET_TYPE_START', 10);
define('ASSET_TYPE_END', 19);
define('LIABILITY_TYPE_START', 20);
define('LIABILITY_TYPE_END', 29);
define('INCOME_TYPE_START', 30);
define('INCOME_TYPE_END', 39);
define('EXPENDITURE_TYPE_START', 40);
define('EXPENDITURE_TYPE_END', 49);
define('EQUITY_TYPE_START', 50);
define('EQUITY_TYPE_END', 59);
define('CASH_TYPE_ID', 11);
define('BANK_TYPE_ID', 12);

/*------ Constants for Business Sectors -------*/
define('REAL_ESTATE_SECTOR', 0);
define('IT_BUSINESS_SECTOR', 1);
define('GROCERY_SHOP_SECTOR', 2);
define('FILLING_STATION_SECTOR', 3);
define('FISHARY_SECTOR', 4);
define('BREAKFIELD_SECTOR', 5);
define('FURNITURE_SECTOR', 6);
define('TEXTILE_SECTOR', 7);
define('MILL_FACTORY_SECTOR', 8);
define('INDUSTRY_SECTOR', 9);
define('FISH_FEED_MILL_SECTOR', 10);
define('FOOD_AND_BEVERAGE_SECTOR', 11);

// this is used for dashboard graphical report
define('MIN_BRANCH_NO_GREPORT', 3);
define('MID_BRANCH_NO_GREPORT', 5);
define('MAX_BRANCH_NO_GREPORT', 7);
define('DREPORTREFRESHTIME', 300);
define('DREPORTTIMEDURATION', 300000);
define('DREPORTSLIDERTIME', 15000);
define('GRAPHREPORTWIDTH', 475);
define('GRAPHREPORTHEIGHT', 280);
define('DIVIDEMESSAGE', 'Total in Thousand');

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
defined('FILE_READ_MODE') OR define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') OR define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE') OR define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE') OR define('DIR_WRITE_MODE', 0755);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/
defined('FOPEN_READ') OR define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE') OR define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE') OR define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE') OR define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE') OR define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE') OR define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT') OR define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT') OR define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

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
defined('EXIT_SUCCESS') OR define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR') OR define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG') OR define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE') OR define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS') OR define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') OR define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT') OR define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE') OR define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN') OR define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX') OR define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code

define('IS_SKIP_INSERT_QUERY_ON_AUDIT_LOG', true);
define('AUDIT_TRAIL', true);
