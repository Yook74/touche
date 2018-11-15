<?php


/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = NULL)
 *
 * @SuppressWarnings(PHPMD)
*/
class CreatorActor extends AcceptanceTester
{
    /**
     * CreatorActor constructor.
     * @param \Codeception\Scenario $scenario an opaque object that codeception will automatically pass in
     */
    function __construct(Codeception\Scenario $scenario)
    {
        parent::__construct($scenario, "creatorAttr.ini");
    }
}
