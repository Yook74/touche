<?php

class ContestEndCest
{
    public function endContest(AdminActor $I)
    {
        $I->wantTo("Make sure the contest ends when it should");
        $I->endContest();
        $I->see("Contest successfully edited");
    }

    public function noContestTeam(TeamActor $I)
    {
        $I->wantTo("See if the contest has ended as a team");
        $I->amOnMyPage("main.php");
        $I->see("Contest is over");
    }

    public function submitWithNoContest(TeamActor $I)
    {
        $I->wantTo("Make sure a team cannot submit if the contest is over");
        $I->amOnMyPage("submissions.php");
        $I->dontSee("Submit a Solution");
    }

    public function noContestJudge(JudgeActor $I)
    {
        $I->wantTo("See if the contest has ended as a judge");
        $I->amOnMyPage("main.php");
        $I->dontSee("Time Till Contest End");
    }

    public function resetTime(AdminActor $I)
    {
        $I->wantTo("Reset the contest information back to normal");
        $I->resetContestTime();
        $I->see("Contest edited successfully");
    }
}