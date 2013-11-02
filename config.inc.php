<?php

// http://docs.phpmyadmin.net/en/latest/config.html

/* Servers configuration */
$i = 0;

/* Server: localhost [1] */
$i++;
$cfg['Servers'][$i]['verbose'] = '';
$cfg['Servers'][$i]['host'] = 'localhost';
$cfg['Servers'][$i]['port'] = '';
$cfg['Servers'][$i]['socket'] = '';
$cfg['Servers'][$i]['connect_type'] = 'tcp';
$cfg['Servers'][$i]['extension'] = 'mysqli';
$cfg['Servers'][$i]['auth_type'] = 'config';
$cfg['Servers'][$i]['user'] = 'root';
$cfg['Servers'][$i]['password'] = 'password';

$cfg['Servers'][$i]['pmadb'] = 'phpmyadmin';
$cfg['Servers'][$i]['controluser'] = 'root';
$cfg['Servers'][$i]['controlpass'] = 'password';

$cfg['Servers'][$i]['bookmarktable'] = "pma__bookmark";
$cfg['Servers'][$i]['relation'] = "pma__relation";
$cfg['Servers'][$i]['table_info'] = "pma__table_info";
$cfg['Servers'][$i]['table_coords'] = "pma__table_coords";
$cfg['Servers'][$i]['pdf_pages'] = "pma__pdf_pages";
$cfg['Servers'][$i]['column_info'] = "pma__column_info";
$cfg['Servers'][$i]['history'] = "pma__history";
$cfg['Servers'][$i]['designer_coords'] = "pma__designer_coords";
$cfg['Servers'][$i]['MaxTableUiprefs'] = 1000;

$cfg['Servers'][$i]['hide_db'] = 'performance_schema|information_schema|mysql';
$cfg['MaxRows'] = 100;

/* End of servers configuration */

$cfg['RetainQueryBox'] = 1;
$cfg['NavigationDisplayLogo'] = 0;
$cfg['NavigationDisplayServers'] = 0;
$cfg['DefaultLang'] = 'en';
$cfg['ServerDefault'] = 1;
$cfg['blowfish_secret'] = '52740cdac68991.93232951';
$cfg['UploadDir'] = '';
$cfg['SaveDir'] = '';
$cfg['AllowArbitraryServer'] = true;
$cfg['LoginCookieStore'] = 2147483647;
$cfg['LoginCookieValidity'] = 2147483647;
$cfg['LoginCookieDeleteAll'] = false;
$cfg['PersistentConnections'] = true;
$cfg['ExecTimeLimit'] = 900;
$cfg['QueryHistoryDB'] = true;
$cfg['QueryHistoryMax'] = 500;
$cfg['MaxCharactersInDisplayedSQL'] = 10000;
$cfg['RetainQueryBox'] = true;
$cfg['SQLQuery']['ShowAsPHP'] = false;
$cfg['NavigationTreeDbSeparator'] = '';
?>
