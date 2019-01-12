<?php

class JudgeSubmissionsCest
{
    public static $error_path = "example_submissions/incorrect_output/src.c";

    public static $teamName = "Super Team";
    public static $teamUsername = "thesupers";
    public static $teamPassword = "superpass";
    public $teamID;

    public static $newProblemName = "SubtractNoNumbers";

    public function submitErroneousSolution(TeamActor $I)
    {
        $I->wantTo("Submit an erroneous file to test");
        $I->submitSolution(self::$error_path);
    }

    public function createNewTeamAndProblem(AdminActor $I)
    {
        $I->wantTo("Add a new team and problem for testing");
        $I->addSimpleTeam(self::$teamName, self::$teamUsername, self::$teamPassword);
        $this->teamID = $I->grabFromDatabase('TEAMS', 'TEAM_ID',
            array('TEAM_NAME' => self::$teamName));
        $I->createProblem(self::$newProblemName);
    }

    public function filterByProblem(JudgeActor $I)
    {
        $I->wantTo("View submissions for one problem");
        $I->filterProblem(self::$newProblemName);
        $I->see("there are no submissions for this problem");
    }

    public function filterByTeam(JudgeActor $I)
    {
        $I->wantTo("View submissions from one team");
        $I->filterByTeam($this->teamID[0]);
        $I->see("there are no submissions for this problem");
    }

    public function cleanup(AdminActor $I)
    {
        $I->wantTo("Reset contest information back to default");
        $I->deleteTeam();
        $I->deleteTeam();
        $I->addDefaultTeam();
        $I->deleteProblem();
        $I->deleteProblem();
        $I->createProblem();
    }

}