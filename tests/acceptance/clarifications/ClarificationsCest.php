<?php


class ClarificationsCest
{
    public static $unpromptedClariText = "?Welcome to my empire puny humans!";
    public static $generalResponseText = "?You will never succeed mua ha ha ha >:D";
    public static $generalRequestText = "Oh man I am not good with computer plz to halp?!%$#?";
    public static $specificRequestText = "How does add numers";

    public function createUnpromptedClari(JudgeActor $I)
    {
        $I->wantTo("Create a clarification without prompting");
        $I->createUnpromptedClari(self::$unpromptedClariText);
        $I->click("All");
        $I->see(self::$unpromptedClariText);
    }

    /**
     * @depends createUnpromptedClari
     */
    public function lookForUnpromptedClari(TeamActor $I)
    {
        $I->wantTo("Look for the unprompted clarification made by the judge");
        $I->amOnMyPage("clarifications.php");
        $I->see(self::$unpromptedClariText);
    }

    public function requestGeneralClari(TeamActor $I)
    {
        $I->wantTo("Request a general clarification");
        $I->requestClari(self::$generalRequestText);
    }

    /**
     * @depends requestGeneralClari
     */
    public function respondToGeneralRequest(JudgeActor $I)
    {
        $I->wantTo("Respond to the general request for clarification made by a team");
        $I->amOnMyPage("clarifications.php");
        $I->see(self::$generalRequestText);
        $I->respondToClariRequest(self::$generalResponseText);
        $I->click("All");
        $I->see(self::$generalResponseText);
    }

    /**
     * @depends respondToGeneralRequest
     */
    public function lookForGeneralResponse(TeamActor $I)
    {
        $I->wantTo("See the response of the judge to my general response");
        $I->amOnMyPage("/clarifications.php");
        $I->see(self::$generalResponseText);
    }

    /**
     * @depends respondToGeneralRequest
     */
    public function requestSpecificClari(TeamActor $I)
    {
        $I->wantTo("Request a specific clarification");
        $problemName = parse_ini_file("tests/_support/adminAttr.ini")["problem_name"];
        $I->requestClari(self::$specificRequestText, $problemName);
    }

    /**
     * @depends requestSpecificClari
     */
    public function lookForSpecificRequest(JudgeActor $I)
    {
        $I->wantTo("Check to make sure the specific request for clarification shows up");
        $I->amOnMyPage("clarifications.php");
        $I->see(self::$specificRequestText);
    }

    /**
     * @depends lookForSpecificRequest
     * @depends lookForUnpromptedClari
     */
    public function deleteClari(JudgeActor $I){
        $I->wantTo("Clean up (delete) the clarifications");
        $I->cleanupClari();
    }
}
