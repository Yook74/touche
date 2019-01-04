<?php
use Codeception\Util\Locator;

class StartContestCest
{
    public function startContest(JudgeActor $I)
    {
        $I->wantTo("Start the contest");
        $I->startAll();
    }

    /**
     * @depends startContest
     */
    public function checkTeamForStart(TeamActor $I)
    {
        $I->wantTo("Check if the contest has started as a team");
        $I->dontSee("Contest has not yet started");
        $I->dontSee("Contest is over");
    }
}
