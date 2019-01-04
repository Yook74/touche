<?php
use Codeception\Util\Locator;

class TeamVisibilityCest
{
    private $teamName;

    public function submitAnonProblem(TeamActor $I){
        $this->teamName = $I->attr["name"];

        $I->wantTo("Submit a problem anonymously");
        $I->submitSolution("example_submissions/accepted/src.c");
        $I->see("Queued for judging");
    }

    /**
     * @depends submitAnonProblem
     */
    public function dontSeeTeams(JudgeActor $I){
        $I->wantTo("Not see the team's name");
        $I->wait(70);
        $I->click("Judge Submissions");
        $I->dontSee("Team Name");
        $I->dontSee($this->teamName);
    }

    /**
     * @depends dontSeeTeams
     */
    public function enableTeamVisibility(AdminActor $I){
        $I->wantTo("Show team names to judges");
        $I->checkOption("team_show");
        $I->click("Submit");
        $I->seeCheckboxIsChecked("team_show");
    }

    /**
     * @depends enableTeamVisibility
     */
    public function seeTeams(JudgeActor $I){
        $I->wantTo("See the team's name");
        $I->click("Judge Submissions");
        $I->see("Team Name");
        $I->see($this->teamName);
    }

    /**
     * @depends seeTeams
     */
    public function disableTeamVisibility(AdminActor $I){
        $I->wantTo("Turn off team visibility");
        $I->uncheckOption("team_show");
        $I->click("Submit");
        $I->dontSeeCheckboxIsChecked("team_show");
    }

    /**
     * @depends disableTeamVisibility
     */
    public function teamsStillInvisible(JudgeActor $I){
        $this->dontSeeTeams($I);
        $I->rejectSubmission();
    }
}