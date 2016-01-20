# mysqlibackwards

Simple script to help convert existing PHP scripts that use mysql_ functions to support the new mysqli_ functions.

By Ben McGaughey of Seltice Systems LLC

Version 0.2 (BETA)

**Why?**

First, mysql_ functions are being deprecated, so it's a necessity to change to mysqli_ functions.

Second, rewriting entire projects may not be an option for many of us.  Possibly due to cost or time.  But having a script that can be included at the top of your exiting script (mysqlibackwards.php), then rename mysql_ functions to mysqlii_ with a find-and-replace tool is a quick solution to the problem.

**It is very important that you properly sanitize your SQL strings and add slashes where appropriate based on your web server's PHP, MySQL, and other server-specific settings to prevent SQL injection, etc.**

**Always make backups of your DATABASE AND SCRIPTS before replacing function names in your existing scripts or testing on a live database!!!**

Currently supported mysql override functions and additional functions:

- mysqlii_connect
- mysqlii_select_db
- mysqlii_close
- mysqlii_query
- mysqlii_num_rows
- mysqlii_fetch_object
- mysqlii_fetch_array
- mysqlii_free_result
- mysqlii_real_escape_string
- mysqlii_list_fields
- mysqlii_num_fields
- mysqlii_field_len
- mysqlii_field_type
- mysqlii_field_name
- mysqlii_insert_id

##Example of how I use this script:

*This would be the existing script on one of my old websites:*
```php
<?php

$user = "my_username";
$password = "my_password";
$dbase = "my_database";
$host = "my_hostname";
$db = mysql_connect($host, $user, $password);
mysql_select_db($dbase);
$sql = "SELECT * FROM my_table WHERE IsActive=1";
$res = mysql_query($sql);
if (mysql_num_rows($res) > 0) {
	while ($row = mysql_fetch_object($res)) {
		print $row->fieldName . "<br>";
	}
	mysql_free_result($res);
}

```

*This would by my method to convert the script above to use mysqlibackwards.  Note that all I had to do was change the function names to have mysqlii_ instead of mysql_ and include mysqlibackwards.php:*

```php
<?php

require_once("mysqlibackwards.php");

$user = "my_username";
$password = "my_password";
$dbase = "my_database";
$host = "my_hostname";
$db = mysqlii_connect($host, $user, $password);
mysqlii_select_db($dbase);
$sql = "SELECT * FROM my_table WHERE IsActive=1";
$res = mysqlii_query($sql);
if (mysqlii_num_rows($res) > 0) {
	while ($row = mysqlii_fetch_object($res)) {
		print $row->fieldName . "<br>";
	}
	mysqlii_free_result($res);
}

```
