<?php
/*
Plugin Name: user-profile-change-email
Plugin URI: http://www.kalsom.co.uk/blog/plugins/user-profile-change-email/
Description: Send an email to the administrator telling that a user has changed their profile.
Version:  1.0
Author: Ken Brewer
Author URI: http://www.kalsom.co.uk
License:  GPL2

  Copyright 2011  Ken Brewer 

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/* ======================================================================== */
/* No need for an Options page. The plugin is either active or not.		 	*/
/* ======================================================================== */

/* ........................................................................ */

/* ======================================================================== */
/* Add a hook to collect data about the user profile change.			 	*/
/* ======================================================================== */ 

function upc_send_email ( $user_id, $old_user_data ) {

	$blog_name = get_option( 'blogname' );
	$blog_email = get_option( 'admin_email' ); 			// Where to send the email
	$old_data = wp_get_current_user( $user_id );
	$new_user_data = get_userdata( $user_id );
	
/* ======================================================================== */
/* It may be the administrator who is in here, in which case do NOTHING. 	*/
/* ======================================================================== */	

	if ( !current_user_can('administrator') ) {	// Is this an administrator?
	
/* ======================================================================== */
/* Any difference between the old and the new?				.			 	*/
/* ======================================================================== */ 	

		if  (  ( $old_data->data->user_firstname != $new_user_data->user_firstname )
			or ( $old_data->data->user_lastname != $new_user_data->user_lastname )
			or ( $old_data->data->nickname != $new_user_data->nickname )
			or ( $old_data->data->user_email != $new_user_data->user_email )
			or ( $old_data->data->user_url != $new_user_data->user_url )
			or ( $old_data->data->user_description != $new_user_data->user_description )
			or ( $old_data->data->user_pass != $new_user_data->user_pass )
						) {				// Yes there is
	
/* ======================================================================== */
/* Send an email to inform the administrator.				.			 	*/
/* ======================================================================== */ 
			$mailhead = "From: " . $blog_email;	
			$mailto = $blog_email;
			$mailsubj = $blog_name . ": User Profile Changed by ";
			$mailsubj .= $old_data->data->user_firstname . " " . $old_data->data->user_lastname;
	
	
			$mailbody  = $old_data->data->user_firstname . " " . $old_data->data->user_lastname;
			$mailbody .=" has changed their WordPress Profile. ";
			$mailbody .="\n\nUser Id = " . $user_id;
			$mailbody .="\n\nUser Log in = " . $new_user_data->user_login . "\n";									// Username

			$mailbody .="\nOld First Name = " .	$old_data->data->user_firstname;		//First Name
			if ( $old_data->data->user_firstname != $new_user_data->user_firstname ) {
				$mailbody .="\nNew First Name = " . $new_user_data->user_firstname . "\n";
			}
		
			$mailbody .="\nOld Last Name = " . 	$old_data->data->user_lastname;			// Last Name
			if ( $old_data->data->user_lastname != $new_user_data->user_lastname ) {
				$mailbody .="\nNew Last Name = " . 	$new_user_data->user_lastname . "\n";
			}
		
			$mailbody .="\nOld Nickname = " . 	$old_data->data->nickname;				// Nickname
			if ( $old_data->data->nickname != $new_user_data->nickname ) {
				$mailbody .="\nNew Nickname = " . 	$new_user_data->nickname . "\n";
			}
		
			$mailbody .="\nOld eMail = " . 		$old_data->data->user_email;			// E-mail
			if ( $old_data->data->user_email != $new_user_data->user_email ) {
				$mailbody .="\nNew eMail = " . 		$new_user_data->user_email . "\n";
			}
		
			$mailbody .="\nOld URL = " . 		$old_data->data->user_url;				// Website
			if ( $old_data->data->user_url != $new_user_data->user_url ) {
				$mailbody .="\nNew URL = " . 		$new_user_data->user_url . "\n";
			}
			
			$mailbody .="\nOld Description = " . 		$old_data->data->user_description;	// Description
			if ( $old_data->data->user_description != $new_user_data->user_description ) {
				$mailbody .="\nNew Description = " . $new_user_data->user_description . "\n";
			}
		
			if ( $old_data->data->user_pass != $new_user_data->user_pass ) {
				$mailbody .="\n\nPassword has been changed. " ;
			}

			$mailbody .="\n\nThank You.";
			
			mail($mailto, $mailsubj, $mailbody, $mailhead);
		}
	}
	return;
}

add_action( 'profile_update', 'upc_send_email', 10, 2 ); 
?>

