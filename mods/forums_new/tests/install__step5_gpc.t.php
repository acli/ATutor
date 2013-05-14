<?php
/* vi: set ai sm: */
/* vim: set filetype=php: */

require_once('simpletest/autorun.php');
require('install/include/common.inc.php');

class GpcBaseTestCase extends UnitTestCase {

    function test__make_sure_magic_quotes_gpc_is_on() {
        $det = get_magic_quotes_gpc();
        $this->assertEqual($det, 1);
    }

    function test1() {
        $tmpfile = tempnam('/tmp', "$$");
        print "tmpfile=($tmpfile)\n";
        $password = "Testing";
        $this->assertTrue($this->get('http://localhost/~ambrose/projects/ATutor/install/install.php'));
        $result = write_config_file($tmpfile, '', $password, '', '', '', '', '', '', false, true);
        print "result=$result\n";
        $det = file_get_contents($tmpname);
    }

}

?>
