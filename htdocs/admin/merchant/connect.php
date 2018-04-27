<?php
/* Database config */

/*

$db_host		= '127.0.0.1';
$db_user		= 'k9833324_iac';
$db_pass		= 'iac012345';
$db_name	= 'k9833324_iac';
*/


    $db_host = "ec2-54-235-146-51.compute-1.amazonaws.com";
    $db_name = "d5d54qasauohqr";
    $db_user = "hufesilxijepeg";
    $db_pass = 'f5a4f61fa0c68b1f81befc9999472052596b3aa7505de0e38029844d6c854ddd';



/* End config */

$db = new PDO('pgsql:host='.$db_host.';dbname='.$db_name, $db_user, $db_pass);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

?>
