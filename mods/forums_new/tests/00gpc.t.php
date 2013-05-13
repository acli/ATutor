<?php
/* vi: set ai sm: */
/* vim: set filetype=php: */

require_once('simpletest/autorun.php');

class GpcBaseTestCase extends UnitTestCase {

	function skip() {
		$det = get_magic_quotes_gpc();
		$this->skipIf(
			$det === false,
			"Your PHP version is too new for magic_quotes_gpc"
		);
	}

	function test__get_magic_quotes_gpc_has_sane_return_values() {
		$det = get_magic_quotes_gpc();
		$this->assertTrue($det == 0 || $det == 1 || $det === false);
	}

}

?>
