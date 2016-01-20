# mysqlibackwards

Simple script to help convert existing PHP scripts that use mysql_ functions to support the new mysqli_ functions.

By Seltice Systems LLC

Version 0.2 (BETA)

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
