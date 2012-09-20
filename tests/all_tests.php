<?php
require_once('simpletest/autorun.php');

$path = dirname(dirname(__FILE__)) . '/tests/';
set_include_path(get_include_path() . PATH_SEPARATOR . $path);

class AllTests extends TestSuite {
    function AllTests() {
        $this->TestSuite('All tests');
        $this->addFile('form_test.php');
        $this->addFile('validation_test.php');
    }
}
?>
