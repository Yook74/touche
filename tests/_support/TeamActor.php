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
class TeamActor extends AcceptanceTester
{

    /**
     * TeamActor constructor.
     * @param \Codeception\Scenario $scenario an opaque object that codeception will automatically pass in
     */
    function __construct(Codeception\Scenario $scenario)
    {
        parent::__construct($scenario, "teamAttr.ini");
    }

    /**
     * Requests a general clarification
     * @param string $clariText string the text to put into the clarification
     */
    public function requestClari(string $clariText)
    {
        $I = $this;
        $I->amOnMyPage("clarifications.php");
        $I->click('Request Clarification');
        $I->fillField("question", $clariText);
        $I->click("submit");
    }
}
