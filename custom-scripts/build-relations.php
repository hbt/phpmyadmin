<?php

/**
 * build the phpmyadmin relations based on innodb template
 * designed for frameworks where the sql is generated through a schema file and myisam / innodb can be turned on/off
 */

/**
 *" php build-relations.php databaseName directory \n\n
    e.g \n\
    php build-relations.php ssi_ctms  /home/hassen/workspace/ssi_ctms/data/sql ";
 */

include(dirname(__FILE__) . '/../config.inc.php');
$config = $cfg;

$dbName = $argv[1];
$dir    = $argv[2];
main($dbName, $dir);


/**
 * @param $dbName myisam database name with an already loaded schema
 * @param $dir    directory where all the sql files are generated from shema
 */
function main($dbName, $dir)
{
    if(!file_exists($dir) || strlen($dbName) === 0)
    {
        showUsage();
        die("invalid arguments");
    }


    // -- get all sql from schema
    $sql = getSQL($dir);

    // -- validate
    validateSQL($sql);

    // replace myisam by innodb
    $sql = str_replace('MyISAM', 'InnoDB', $sql);


    buildRelations($sql, $dbName);
}


function validateSQL($sql)
{
    // -- check generated sql uses myisam
    if(strtolower(stripos($sql, 'myisam') === false))
    {
        die('must use MyISAM as sql engine');
    }
}


function getSQL($dir)
{
    if(!file_exists($dir))
    {
        die('cannot access dir ' . $dir);
    }


    // -- read all sql files from directory

    $ret = '';

    // change dir + fix dir name
    chdir($dir);
    $dir = getcwd();

    // get all sql files
    $files = glob('*.sql');

    if(count($files) === 0)
    {
        die('no sql files found in ' . $dir);
    }

    // build str
    foreach($files as $file)
    {
        $filepath = $dir . DIRECTORY_SEPARATOR . $file;
        $ret .= "\n\n";
        $ret .= file_get_contents($filepath);
    }


    return $ret;
}

function execSQL($sql)
{
    global $config;

    return shell_exec('mysql --user=' . $config['Servers'][1]['user'] . ' --password=' . $config['Servers'][1]['password'] . ' -e "' . $sql . '"');
}

function buildRelations($sql, $dbName)
{
    global $config;

    // -- create dummy database for innodb sql

    $dummydb = $dbName . '_innodb_' . time();
    execSQL('create database ' . $dummydb . ';');


    // -- load sql

    $sqlfile = '/tmp/' . $dummydb . '.sql';
    file_put_contents($sqlfile, $sql);
    shell_exec('mysql --user=' . $config['Servers'][1]['user'] . ' --password=' . $config['Servers'][1]['password'] . '  ' . $dummydb . ' < ' . $sqlfile);
    unlink($sqlfile);


    // -- build relations using information_schema

    // connect
    $con = mysqli_connect("localhost", $config['Servers'][1]['user'], $config['Servers'][1]['password'], 'information_schema');
    if(mysqli_connect_errno())
    {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }

    // get foreign keys
    $q      = "select TABLE_NAME,COLUMN_NAME,
REFERENCED_TABLE_NAME,REFERENCED_COLUMN_NAME from information_schema.KEY_COLUMN_USAGE where
REFERENCED_TABLE_NAME is not null AND CONSTRAINT_SCHEMA = '" . $dummydb . "';";
    $result = mysqli_query($con, $q);

    // insert into phpmyadmin relations table
    while($row = mysqli_fetch_array($result))
    {
        $refTable = $row['TABLE_NAME'];
        $refCol   = $row['COLUMN_NAME'];
        $fkTable  = $row['REFERENCED_TABLE_NAME'];
        $fkCol    = $row['REFERENCED_COLUMN_NAME'];
        $insert   = "INSERT INTO phpmyadmin.pma__relation values ('$dbName', '$refTable', '$refCol', '$dbName', '$fkTable', '$fkCol');";

        execSQL($insert);
    }

    mysqli_close($con);


    // -- drop dummy database

    execSQL('drop database ' . $dummydb . ';');
}

function showUsage()
{
    echo " php build-relations.php databaseName directory \n\n
    e.g \n\
    php build-relations.php ssi_ctms  /home/hassen/workspace/ssi_ctms/data/sql ";
}


?>