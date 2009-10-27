<?php
/*
$Id$

  This code is part of LDAP Account Manager (http://www.ldap-account-manager.org/)
  
  This code is based on phpLDAPadmin.
  Copyright (C) 2004  David Smith and phpLDAPadmin developers
  
  The original code was modified to fit for LDAP Account Manager by Roland Gruber.
  Copyright (C) 2005  Roland Gruber

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation; either version 2 of the License, or
  (at your option) any later version.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA


*/


/** 
 *  Updates or deletes a value from a specified 
 *  attribute for a specified dn.
 *  Variables that come in on the query string:
 *  - dn (rawurlencoded)
 *  - update_array (an array in the form expected by PHP's ldap_modify, except for deletions)
 *     (will never be empty: update_confirm.php ensures that)
 *
 * Attribute deletions:
 * To specify that an attribute is to be deleted (whether multi- or single-valued), 
 *  enter that attribute in the update array like this: attr => ''. For example, to
 *  delete the 'sn' attribute from an entry, the update array would look like this:
 *  Array (
 *     sn => ''
 *  )
 *
 * On success, redirect to edit.php
 * On failure, echo an error.
 *
 * @package lists
 * @subpackage tree
 * @author David Smith
 * @author Roland Gruber
 */

/** security functions */
include_once('../../lib/security.inc');
/** tree functions */
include_once('../../lib/tree.inc');
/** access to configuration */
include_once('../../lib/config.inc');
/** LDAP functions */
include_once('../../lib/ldap.inc');

// start session
startSecureSession();

// die if no write access
if (!checkIfWriteAccessIsAllowed()) die();

setlanguage();

$dn = $_POST['dn'];
$encoded_dn = rawurlencode( $dn );
$update_array = $_POST['update_array'];

// check for delete attributes (indicated by the attribute entry appearing like this: attr => '' 
foreach( $update_array as $attr => $val )
	if( ! is_array( $val ) )
		if( $val == '' ) {
			$update_array[ $attr ] = array();
		}
		else {
			$update_array[ $attr ] = $val;
		}
	else {
		foreach( $val as $i => $v ) {
			$update_array[ $attr ][ $i ] = $v;
		}
	}
			
$ds = $_SESSION['ldap']->server();
$res = @ldap_modify( $ds, $dn, $update_array );
if( $res )
{
	$redirect_url = "edit.php?dn=$encoded_dn";
	foreach( $update_array as $attr => $junk )
		$redirect_url .= "&modified_attrs[]=$attr";
	header( "Location: $redirect_url" );
}
else
{
	echo $_SESSION['header'];
	
	echo "<title>LDAP Account Manager</title>\n";
	echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"../../style/layout.css\">\n";
	echo "</head>\n";
	echo "<body>";
	StatusMessage('ERROR', _("Was unable to modify attribtues from DN: %s."), ldap_error( $ds ), array($dn));
	echo "</body></html>";
}

?>
