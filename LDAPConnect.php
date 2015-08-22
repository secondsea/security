 <?php   
$LDAPHost = "communityaction.us";
$dn = "OU=USERS, OU=COMMUNITYACTION,DC=communityaction,DC=us";    
$LDAPUserDomain = "@communityaction.us";
$ds=ldap_connect($LDAPHost);  // must be a valid LDAP server!
$LDAPUser = "ITSCCTrain"; 
$LDAPUserPassword = "AccessInfo1";
if ($ds) { 
	 ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3); 
 	 ldap_set_option($ds, LDAP_OPT_REFERRALS, 1);  
     $r=ldap_bind($ds,$LDAPUser.$LDAPUserDomain,$LDAPUserPassword);
	//Debug code to see if the ldap is working
	//$filter="(samaccountname=mcusack)";    //  replace mcusack with your logon if I am not here
	//echo $filter;
	//$sr=ldap_search($ds, $dn, $filter, $LDAPFieldsToFind);
	//$info = ldap_get_entries($ds, $sr);
	//echo $info["count"];
		}
