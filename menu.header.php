<?php

function addmenu($debug) {

	$output = '<table>';
	$output .= '
	<tr><td><b>Portal</b></td></tr>
	<tr><td><a href="kpi.num_gadgets.php">Gadget Count</a></td></tr>
	<tr><td><a href="portal.gadget_available.php">Gadgets Available</a></td></tr>
	<tr><td><a href="portal.gadget_usage.php">Gadget Usage</a></td></tr>
	<tr><td><a href="portal.invite_status.php">Invite Status</a></td></tr>
	<tr><td><a href="kpi.num_teamtabs.php">Team Tabs</a></td></tr>
	<tr><td><b>Engine Block</b></td></tr>
	<tr><td><a href="eb.idp_available.php">Available IdPs</a></td></tr>
	<tr><td><a href="eb.sp_available.php">Available SPs</a></td></tr>
	<tr><td><a href="kpi.num_idp_sp.php">IdP and SP count</a></td></tr>
	<tr><td><a href="kpi.num_logins.php">Logins</a></td></tr>
	<tr><td><a href="eb.idp_logincount.php">IDP Logins</a></td></tr>
	<tr><td><a href="eb.sp_logincount.php">SP Logins</a></td></tr>

	';
	
	if ($debug) {
		$output .= '<tr><td><a href="dev.testing.php">Test</a></td></tr>';
	}
	
	$output .= '</table>';
	
	return $output;
}

?>