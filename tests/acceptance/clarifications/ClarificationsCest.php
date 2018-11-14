<?php


class ClarificationsCest
{
    public static $generalClariText = "General clarification words";
    public static $specificClariText = "Specific clarification words";
    public static $clariRequestText = "Oh man I am not good with computer plz to halp";

    public function createGeneralClari(JudgeActor $I)
    {
        $I->wantTo("Create a general clarification without prompting");
        $I->createGeneralClari(self::$generalClariText);
        $I->click("All");
        $I->see(self::$generalClariText);
    }

    /**
     * @depends createGeneralClari
     */
    public function lookForGeneralClari(TeamActor $I)
    {
        $I->wantTo("Look for the general clarification made by the judge");
        $I->amOnPage("/clarifications.php");
        $I->see(self::$generalClariText);
    }

    public function requestClari(TeamActor $I)
    {
        $I->wantTo("Request a clarification");
        $I->requestClari(self::$clariRequestText);
    }

    /**
     * @depends requestClari
     */
    public function respondToClariRequest(JudgeActor $I)
    {
        $I->wantTo("Respond to the request for clarification made by a team");
        $I->amOnPage("/judge/clarifications.php");
        $I->see(self::$clariRequestText);
        $I->respondToClariRequest(self::$specificClariText);
        $I->click("All");
        $I->see(self::$specificClariText);
    }

    /**
     * @depends respondToClariRequest
     */
    public function lookForClariResponse(TeamActor $I)
    {
        $I->wantTo("See the response of the judge to my response");
        $I->amOnPage("/clarifications.php");
        $I->see(self::$specificClariText);
    }
}
