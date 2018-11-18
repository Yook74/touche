<?php
namespace Helper;

// here you can define custom actions
// all public methods declared in helper class will be available in $I

class Acceptance extends \Codeception\Module
{
    /**
     * The Db module will try to connect to databases when it is constructed.
     * In our use case this is undesirable. We want to be able to connect after we create the contest
     * Why is this here and not in the AcceptanceTester? Because the getModule method is a protected member of Module
     * @param $dbName string the name of the contest (not a handle, the actual name)
     * @throws \Codeception\Exception\ModuleException
     */
    public function connectToDatabase($dbName)
    {
        $db = $this->getModule('Db');
        $db->drivers['default']->executeQuery("USE $dbName", array());
    }
}
