<?php
/**
 * Plugin Name: Users Registered List
 * Plugin URI:  http://ovirium.com
 * Description: Adds sortable `Registered` column to the list of users in wp-admin area
 * Author:      slaFFik
 * Author URI:  http://ovirium.com
 * Version:     1.0
 */

/**
 * Just in case anyone will care about translation
 */
add_action('plugins_loaded', 'url_i18n');
function url_i18n() {
  load_plugin_textdomain( 'url', false, dirname( plugin_basename( __FILE__ ) ) . '/langs/' );
}

/**
 * Add Reg column to users list in admin area
 */
add_filter( 'manage_users_columns', 'url_modify_user_table' );
function url_modify_user_table( $column ) {
    $column['reg_date'] = __('Registered', 'url');

    return $column;
}

/**
 * @param string $val
 * @param string $column_name
 * @param int $user_id
 * @return string
 * @wp-hook manage_users_custom_column
 */
add_filter( 'manage_users_custom_column', 'url_modify_user_table_row', 10, 3 );
function url_modify_user_table_row( $val, $column_name, $user_id ) {
    $user = get_userdata( $user_id );

    switch ($column_name) {
        case 'reg_date' :
            $date_format = get_option('date_format', true);
            if(empty($date_format)){
                $date_format = 'F j, Y';
            }

            $time_format = get_option('time_format', true);
            if(empty($time_format)){
                $time_format = 'g:i a';
            }

            return date_i18n($date_format . ' @ ' . $time_format, strtotime($user->user_registered));
            break;

        default:
    }

    return $val;
}

/**
 * @param array $sortable
 * @return array
 * @wp-hook manage_users_sortable_columns
 */
add_filter( 'manage_users_sortable_columns', 'url_modify_user_table_sortable' );
function url_modify_user_table_sortable($sortable){
    $sortable['reg_date'] = 'user_registered';
    return $sortable;
}
