<?php

class ClearContestCest
{
    public function submitSolution(TeamActor $I)
    {
        $I->wantTo("Submit a solution to test the clear contest functionality");
        $I->submitSolution("example_submissions/accepted/src.c");
        $I->see("Judging pending");
    }

    public function clearContest(AdminActor $I)
    {
        $I->wantTo("Clear a contest");
        $I->clearContest();
        $I->see("Contest Cleared Successfully! ");
    }

    public function noSubmissions(JudgeActor $I)
    {
        $I->wantTo("Make sure there are no submissions");
        $I->amOnMyPage("judge.php");
        $I->see("Queued Submissions: 0");
        $I->see("there are no submissions for this problem");
    }

    public function contestNotStarted(TeamActor $I)
    {
        $I->wantTo("Make sure that the contest has not started");
        $I->amOnMyPage("main.php");
        $I->see("Contest has not yet started");
    }

    public function checkDefaultState(AdminActor $I)
    {
        $I->wantTo("Check for the default information");
        $I->amOnMyPage("setup_teams.php");
        $I->see(parse_ini_file('tests/_support/teamAttr.ini')["name"]);
        $I->amOnMyPage("setup_problems.php");
        $I->see($I->attr["problem_name"]);
        $I->amOnMyPage("setup_site.php");
        $I->see($I->attr["site_name"]);
        $I->amOnMyPage("setup_data_sets.php");
        $I->see($I->attr["problem_name"]);
    }

    public function startContest(JudgeActor $I)
    {
        $I->wantTo("Start the contest again");
        $I->startAll();
    }
}