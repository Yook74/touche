<?php

class ScoringCest
{
    public static $correctSolution ="example_submissions/accepted/src.c";
    public static $incorrectSolution = "example_submissions/format_error/src.c";

    public static $newTeamName = "The Worst Team";
    public static $newTeamUser = "whatsacomputer";
    public static $newTeamPassword = "123abcP";

    public static $newProblemName = "SubtractTwoNumbers";

    public function clearContest(AdminActor $I)
    {
        $I->wantTo("Clear the contest");
        $I->clearContest();
    }

    public function startContest(JudgeActor $I)
    {
        $I->wantTo("Start the Contest again");
        $I->startAll();
    }

    public function startup(AdminActor $I)
    {
        $I->wantTo("Add new teams and a new problem for testing");
        $I->addSimpleTeam(self::$newTeamName, self::$newTeamUser, self::$newTeamPassword);
        //Do this to show movement when you check the standings
        $I->deleteTeam();
        $I->addDefaultTeam();
        $I->createProblem(self::$newProblemName);
    }

    public function submitSolutions(TeamActor $I)
    {
        $I->wantTo("Have the Best Team submit a correct solution one minute before the Worst Team");
        $I->submitSolution(self::$correctSolution);
        $I->wait(60);
        $I->login(self::$newTeamUser, self::$newTeamPassword);
        $I->submitSolution(self::$correctSolution, 2);
    }

    public function judgeSolutions(JudgeActor $I)
    {
        $I->wantTo("Judge both submissions as correct");
        $I->waitForAutoJudging();
        $I->judgeSubmission("Accepted");
        $I->waitForAutoJudging();
        $I->judgeSubmission("Accepted");
    }

    public function viewUpdatedScoresTeam(TeamActor $I)
    {
        $I->wantTo("See if the scoring has updated correctly as a team");
        $I->amOnMyPage("standings.php");
        $I->see("/1");
        $score = $I->getMyProblemScore(1);

        //If the score is "##:##/#", that's bad
        $I->dontSee("00:0$score");

        //Make sure that the score is within a range of values
        $I->assertGreaterThanOrEqual(0, $score);
        $I->assertLessThanOrEqual(5, $score);
    }

    public function viewUpdatedScoresJudge(JudgeActor $I)
    {
        $I->wantTo("See if the scoring has updated correctly as a judge");
        $I->amOnMyPage("standings.php");
        $I->see("/1");
        $score = $I->getAProblemScore();
        $I->dontSee("00:0$score");
        $I->assertGreaterThanOrEqual(0, $score);
        $I->assertLessThanOrEqual(5, $score);
    }

    public function checkStandingsMovementTeam(TeamActor $I)
    {
        $I->wantTo("Check to see if the team that submitted first is higher on the leaderboard as a team");
        $I->amOnMyPage("standings.php");
        $I->assertEquals(False, $I->isAheadOfMe(self::$newTeamName));
    }

    public function checkStandingsMovementJudge(JudgeActor $I)
    {
        $I->wantTo("Check to see if the team that submitted first is higher on the leaderboard as a judge");
        $I->amOnMyPage("standings.php");
        $firstTeam = parse_ini_file("tests/_support/teamAttr.ini")["name"];
        $I->assertEquals(True, $I->isAheadOf($firstTeam, self::$newTeamName));
    }

    public function checkCompletedTeam(TeamActor $I)
    {
        $I->wantTo("Check to see that the completed column shows the team that they have one problem solved");
        $I->amOnMyPage("standings.php");
        $I->see("1 (");
        $I->dontSee("0:0"); // checks for correctly formatted score

        $score = $I->getMyOverallScore();
        $I->assertGreaterThanOrEqual(0, $score);
        $I->assertLessThanOrEqual(5, $score);
    }

    public function checkCompletedJudge(JudgeActor $I)
    {
        $I->wantTo("Check to see that the completed column shows a judge that one problem has been solved");
        $I->amOnMyPage("standings.php");
        $I->see("1 (");
        $I->dontSee("0:0"); //checks for correctly formatted score

        $score = $I->getAnOverallScore(self::$newTeamName);
        $I->assertGreaterThanOrEqual(0, $score);
        $I->assertLessThanOrEqual(5, $score);
    }

    public function submitMoreSolutions(TeamActor $I)
    {
        $I->wantTo("Submit incorrect solutions to test the penalty system");
        $I->submitSolution(self::$incorrectSolution, 2);
        $I->login(self::$newTeamUser, self::$newTeamPassword);
        $I->submitSolution(self::$incorrectSolution);
    }

    public function judgeNewSolutions(JudgeActor $I)
    {
        $I->wantTo("Reject the newly submitted solutions");
        $I->waitForAutoJudging();
        $I->judgeSubmission();
        $I->waitForAutoJudging();
        $I->judgeSubmission();
    }

    public function submitLastCorrectSolution(TeamActor $I)
    {
        $I->wantTo("Submit a correct solution as the second team to test the scoring system");
        $I->login(self::$newTeamUser, self::$newTeamPassword);
        $I->submitSolution(self::$correctSolution);
    }

    public function judgeCorrectly(JudgeActor $I)
    {
        $I->wantTo("Judge the last solution correctly");
        $I->waitForAutoJudging();
        $I->judgeSubmission("Accepted");
    }

    public function checkNewScoresTeam(TeamActor $I)
    {
        $I->wantTo("Team: Should see /2 and /1 because one team has completed 2 while one team has only completed 1");
        $I->amOnMyPage("standings.php");
        $I->see("/2");
        $I->see("/1");

        $score = $I->getMyProblemScore(1, self::$newTeamName);
        $I->assertGreaterThanOrEqual(20, $score);
        $I->assertLessThanOrEqual(25, $score);
    }

    public function checkNewScoresJudge(JudgeActor $I)
    {
        $I->wantTo("Judge: Should see /2 and /1 because one team has completed 2 while one team has only completed 1");
        $I->amOnMyPage("standings.php");
        $I->see("/2");
        $I->see("/1");

        $score = $I->getAProblemScore(self::$newTeamName, 1);
        $I->assertGreaterThanOrEqual(20, $score);
        $I->assertLessThanOrEqual(25, $score);
    }

    public function checkNewStandingsTeam(TeamActor $I)
    {
        $I->wantTo("Check to see if the team that has two solved problems is higher on the leaderboard as a team");
        $I->amOnMyPage("standings.php");
        $I->assertEquals(True, $I->isAheadOfMe(self::$newTeamName));
    }

    public function checkNewStandingsJudge(JudgeActor $I)
    {
        $I->wantTo("Check to see if the team that has two solved problems is higher on the leaderboard as a judge");
        $I->amOnMyPage("standings.php");

        $firstTeam = parse_ini_file("tests/_support/teamAttr.ini")["name"];
        $pagePositionBool = $I->isAheadOf($firstTeam, self::$newTeamName);
        $I->assertEquals(False, $pagePositionBool);
    }

    public function cleanup(AdminActor $I)
    {
        $I->wantTo("Cleanup my mess");
        $I->deleteTeam();
        $I->deleteTeam();
        $I->addDefaultTeam();
        $I->deleteProblem();
        $I->deleteProblem();
        $I->createProblem();
        $I->addDatasets();
    }
}