<?php
use Codeception\Util\Locator;

class CreateProblemCest
{
    public function createProblem(AdminActor $I)
    {
        $I->wantTo("Create a new problem [STUB]");
        $I->createProblem();
    }

    /**
     * @depends createProblem
     */
    public function checkJudgeForProblem(JudgeActor $I)
    {
        $I->wantTo("Make sure the problem shows up properly for the judge [STUB]");
    }

    /**
     * @depends createProblem
     */
    public function checkTeamForProblem(TeamActor $I)
    {
        $I->wantTo("Make sure the problem shows up properly for the team [STUB]");

    }
}
