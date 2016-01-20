<?php
/*

mysqlibackwards.php

Simple method to convert existing PHP scripts that use mysql_ functions 
to support the new mysqli_ functions

By Seltice Systems LLC
Version 0.2 (BETA)

##############################################################################
##############################################################################

Copyright (c) 2016, Ben McGaughey of Seltice Systems LLC
All rights reserved.

Redistribution and use in source and binary forms, with or without
modification, are permitted provided that the following conditions are met: 

1. Redistributions of source code must retain the above copyright notice, this
   list of conditions and the following disclaimer. 
2. Redistributions in binary form must reproduce the above copyright notice,
   this list of conditions and the following disclaimer in the documentation
   and/or other materials provided with the distribution. 

THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR
ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
(INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
(INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.

##############################################################################
##############################################################################

IMPORTANT:

Vrify that all SQL strings are properly sanitized to prevent SQL injection
attacks depending on your web server's PHP, MySQL, and other server-erspecific
settings.

##############################################################################
##############################################################################

// Basic Usage Example:

require_once("mysqlibackwards.php");

$user = "your_username";
$password = "your_password";
$dbase = "your_database";
$host = "your_db_host";

$db = mysqlii_connect($host, $user, $password);
mysqlii_select_db($dbase);

$sql = "SELECT * FROM my_table WHERE id='".mysqlii_real_escape_string(($_POST['id']*1))."'";
$res = mysqlii_query($sql);
if (mysqlii_num_rows($res) > 0) {
	while ($row = mysqlii_fetch_object($res)) {
		print $row->sName . "<br>";
	}
	mysqlii_free_result($res);
}

print "<br><hr size=1><br>";

$sql = "SELECT * FROM my_table WHERE id='".mysqlii_real_escape_string(($_POST['id']*1))."'";
$res = mysqlii_query($sql);
if (mysqlii_num_rows($res) > 0) {
	while ($row = mysqlii_fetch_array($res)) {
		print $row['sName'] . "<br>";
	}
	mysqlii_free_result($res);
}

mysqlii_close($global_mysqlibw_db);

*/

$global_mysqlibw_dbase = "";
$global_mysqlibw_user = "";
$global_mysqlibw_password = "";
$global_mysqlibw_host = "";
$global_mysqlibw_db = null;

function mysqlii_connect($host,$user,$password) {
	global $global_mysqlibw_dbase, $global_mysqlibw_user, $global_mysqlibw_password, $global_mysqlibw_host;
	$global_mysqlibw_host = $host;
	$global_mysqlibw_user = $user;
	$global_mysqlibw_password = $password;	
	return null;
}

function mysqlii_select_db($global_mysqlibw_dbname) {
	global $global_mysqlibw_dbase, $global_mysqlibw_user, $global_mysqlibw_password, $global_mysqlibw_host, $global_mysqlibw_db;
	$global_mysqlibw_dbase = $global_mysqlibw_dbname;
	$global_mysqlibw_db = new mysqli($global_mysqlibw_host, $global_mysqlibw_user, $global_mysqlibw_password, $global_mysqlibw_dbase);
	if($global_mysqlibw_db->connect_errno > 0){
		die('Unable to connect to database [' . $global_mysqlibw_db->connect_error . ']');
	}
}

function mysqlii_close() {
	global $global_mysqlibw_db;
	$global_mysqlibw_db->close();
}

function mysqlii_query($sql) {
	global $global_mysqlibw_res, $global_mysqlibw_whichres, $global_mysqlibw_db;
	return $global_mysqlibw_db->query($sql);
}


function mysqlii_num_rows($wres) {
	global $global_mysqlibw_db;
	return $wres->num_rows;
}


function mysqlii_fetch_object($wres) {
	global $global_mysqlibw_db;
	return $wres->fetch_object();
}


function mysqlii_fetch_array($wres) {
	global $global_mysqlibw_db;
	return $wres->fetch_assoc();
}

function mysqlii_free_result($wres) {
	global $global_mysqlibw_db;
	mysqli_free_result($wres);
}

function mysqlii_real_escape_string($string) {
	global $global_mysqlibw_db;
	return mysqli_real_escape_string($global_mysqlibw_db,$string);
}

function mysqlii_list_fields($global_mysqlibw_dbname, $table_name, $global_mysqlibw_db) {

	global $global_mysqlibw_db;
	$returnarray = null;
	$result = mysqlii_query("SHOW COLUMNS FROM " . $table_name);
	if (!$result) {
		return null;
	} elseif (mysqlii_num_rows($result) > 0) {
		$i = 0;
		while ($row = mysqlii_fetch_array($result)) {
			$arrayfields = null;
			$returnarray[$i][0] = trim($row['Field']);
			list($ftype,$fsize) = explode("(",$row['Type']);
			$fsize = str_replace(")","",$fsize);
			$returnarray[$i][1] = $ftype;
			if ($returnarray[$i][1] == "varchar") {
				$returnarray[$i][1] = "string";
			}
			if ($returnarray[$i][1] == "text") {
				$returnarray[$i][1] = "blob";
			}
			$returnarray[$i][2] = $fsize;
			if (!$fsize>0) {
				$fsize = 1;
			}
			$i++;
		}
	}
	return $returnarray;
}


function mysqlii_num_fields($fields)
{
	return count($fields);
}

function mysqlii_field_len($fields, $i)
{
	return $fields[$i][2];
}

function mysqlii_field_type($fields, $i)
{
	return $fields[$i][1];
}

function mysqlii_field_name($fields, $i)
{
	return $fields[$i][0];
}

function mysqlii_insert_id()
{
	global $global_mysqlibw_db;
	return mysqli_insert_id($global_mysqlibw_db);
}


?>
