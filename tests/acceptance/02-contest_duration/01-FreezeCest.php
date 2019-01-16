<?php

class FreezeCest
{
    public static $newTeamName ="The Frozone Gang";
    public static $newTeamUser ="whereismysupersuit";
    public static $newTeamPass ="frozen";
    public function freeze(AdminActor $I)
    {
         $I->wantTo("Set the freeze time to a low amount in order to test the freeze");
         $I->editFreezeTime(0,0,0);
         $I->see("Contest Edited Successfully");
    }

    public function teamFreezeCheck(TeamActor $I)
    {
        $I->wantTo("Check if a team can view standings after the freeze");
        $I->amOnMyPage("standings.php");
        $I->see("Standings - Frozen");
    }

    public function judgeFreezeCheck(JudgeActor $I)
    {
        $I->wantTo("View the standings after the freeze as a judge");
        $I->amOnMyPage("standings.php");
        $I->dontSee("Standings - Frozen");
    }

    public function submitSolution(TeamActor $I)
    {
        $I->wantTo("Submit a right answer in order to update standings");
        $I->submitSolution("example_submissions/accepted/src.c");
        $I->see("Judging pending");
    }

    public function judgeAcceptFrozenSubmission(JudgeActor $I)
    {
        $I->wantTo("Accept a submission after the freeze");
        $I->waitForAutoJudging();
        $I->judgeSubmission("Accepted");
    }

    public function viewUpdatedStandings(JudgeActor $I)
    {
        $I->wantTo("View updated standings after the freeze as a judge");
        $I->amOnMyPage("standings.php");
        $I->dontSee("--");
    }

    public function addTeamFreeze(AdminActor $I)
    {
        $I->wantTo("Add a team after the freeze");
        $I->addSimpleTeam(self::$newTeamName, self::$newTeamUser, self::$newTeamPass);
        $I->see("Successful: New team created");
    }

    public function viewUpdatedFrozenStandings(TeamActor $I)
    {
        $I->wantTo("View updated standings after the freeze as a different team");
        $I->login(self::$newTeamUser, self::$newTeamPass);
        $I->amOnMyPage("standings.php");
        $I->dontSee("/1");
    }

    public function resetFreeze(AdminActor $I)
    {
        $I->wantTo("Cleanup Freeze Cest Actions");
        $I->resetFreezeTime();
        $I->see("Contest Edited Successfully");
        $I->deleteTeam();
        $I->deleteTeam();
        $I->addDefaultTeam();
    }
}