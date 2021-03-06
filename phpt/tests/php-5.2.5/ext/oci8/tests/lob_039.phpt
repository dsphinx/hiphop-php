--TEST--
Test CLOB->write() for multiple inserts
--SKIPIF--
<?php if (!extension_loaded('oci8')) die("skip no oci8 extension"); ?>
--FILE--
<?php

require dirname(__FILE__).'/connect.inc';
require dirname(__FILE__).'/create_table.inc';

echo "Test 1: CLOB\n";

$ora_sql = "INSERT INTO
                       ".$schema.$table_name." (clob)
                      VALUES (empty_clob())
                      RETURNING
                               clob
                      INTO :v_clob ";

$s = oci_parse($c,$ora_sql);
$clob = oci_new_descriptor($c,OCI_DTYPE_LOB);


oci_bind_by_name($s,":v_clob", $clob,-1,OCI_B_CLOB);

oci_execute($s, OCI_DEFAULT);
var_dump($clob->write("clob test 1"));

oci_execute($s, OCI_DEFAULT);
var_dump($clob->write("clob test 2"));

oci_execute($s, OCI_DEFAULT);
var_dump($clob->write("clob test 3"));

$s = oci_parse($c,"select clob from ".$schema.$table_name);
var_dump(oci_execute($s));

oci_fetch_all($s, $res);

var_dump($res);


require dirname(__FILE__).'/drop_table.inc';

echo "Done\n";

?>
--EXPECT--
Test 1: CLOB
int(11)
int(11)
int(11)
bool(true)
array(1) {
  ["CLOB"]=>
  array(3) {
    [0]=>
    string(11) "clob test 1"
    [1]=>
    string(11) "clob test 2"
    [2]=>
    string(11) "clob test 3"
  }
}
Done
