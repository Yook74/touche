<?php

class JudgeUpdateCest
{
    public static $incorrectSolutionPath = "example_submissions/compile_error/src.c";

    public static $teamName = "dunk masters";
    public static $teamUsername = "donsly";
    public static $teamPassword = "dunkey";

    /**
     * Delete the default team and re-add it so that the default team can
     * update to first place after submitting a correct solution.
     */
    public function addNewTeam(AdminActor $I)
    {
        $I->wantTo("Add a new team for testing");
        $I->addSimpleTeam(self::$teamName, self::$teamUsername, self::$teamPassword);
        $I->deleteTeam();
        $I->addDefaultTeam();
    }

    public function submitFirstSolution(TeamActor $I)
    {
        $I->wantTo("Submit an incorrect solution as a team");
        $I->submitSolution(self::$incorrectSolutionPath);
    }

    public function judgeSolution(JudgeActor $I)
    {
        $I->wantTo("Judge a solution");
        $I->waitForAutoJudging();
        $I->judgeSubmission();
    }

    public function seeRejectedSubmission(TeamActor $I)
    {
        $I->wantTo("See that the team's submission has been rejected");
        $I->amOnMyPage("submissions.php");
        $I->see("Error (Reason Unknown)");
    }

    public function reviewJudgment(JudgeActor $I)
    {
        $I->wantTo("Review and accept the previous submission");
        $I->judgeSubmission("Accepted", "review.php");
    }

    public function seeUpdatedStandingsJudge(JudgeActor $I)
    {
        $I->wantTo("See the updated standings");
        $I->amOnMyPage("standings.php");
        $firstTeamName = parse_ini_file("tests/_support/teamAttr.ini")["name"];
        /* Check in the source code for "1 $firstTeamName" which indicates that $firstTeamName has moved from second to
            first. I'm sorry, it was the only way */
        $I->seeInSource('1</font> </td> <td> <font face="Arial" size="3"> '.$firstTeamName);
    }

    public function seeAcceptedSubmission(TeamActor $I)
    {
        $I->wantTo("See that the team's submission has been accepted");
        $I->amOnMyPage("submissions.php");
        $I->see("Accepted");
    }

    public function seeUpdatedStandingsTeam(TeamActor $I)
    {
        $I->wantTo("See the updated standings");
        $I->amOnMyPage("standings.php");
        $firstTeamName = $I->attr["name"];
        $I->seeInSource('1</font> </td> <td> <font face="Arial" size="3"> '.$firstTeamName);
    }

    public function cleanup(AdminActor $I)
    {
        $I->wantTo("Reset the teams back to default");
        $I->deleteTeam();
    }
}
