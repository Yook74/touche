<?php
use Codeception\Util\Locator;

class CreateProblemCest
{
    public function createProblem(AdminActor $I)
    {
        $I->wantTo("Create a new problem");
        $I->createProblem();
        $I->see($I->attr["problem_name"]);
        $I->addDatasets();
        $I->see("Delete");
    }

    /**
     * @depends createProblem
     */
    public function checkJudgeForProblem(JudgeActor $I)
    {
        $I->wantTo("Make sure the problem shows up properly for the judge");
        $I->click("Problems");
        $problemName = parse_ini_file('tests/_support/adminAttr.ini')["problem_name"];
        $I->see($problemName);
    }

    /**
     * @depends createProblem
     */
    public function checkTeamForProblem(TeamActor $I)
    {
        $I->wantTo("Make sure the problem shows up properly for the team");
        $I->click("Problems");
        $problemName = parse_ini_file('tests/_support/adminAttr.ini')["problem_name"];
        $I->see($problemName);
    }
}
