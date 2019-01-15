<?php

class recalculateResponsesCest
{
    # Multiplies instead of adding
    public static $solutionPath = "example_submissions/incorrect_output/src.cpp";

    public function submitTestSolution(TeamActor $I)
    {
        $I->wantTo("Submit a solution for testing");
        $I->submitSolution(self::$solutionPath);
    }

    public function judgeTestSolution(JudgeActor $I)
    {
        $I->wantTo("Judge the test solution");
        $I->waitForAutoJudging();
        $I->judgeSubmission(); # rejects by default
    }

    public function deleteDataSet(AdminActor $I)
    {
        $I->wantTo("Change the Data Set to make the submitted solution count as correct");
        $I->deleteDataset();
        $I->see("Data set deleted successfully");
    }

    public function startRecalculating(AdminActor $I)
    {
        $I->wantTo("Recalculate responses as an admin");
        $I->recalculateResponses();
        $I->see("processing submissions");
        $I->dontSee("Error");
        //TODO Do I need a wait()?
    }

    public function seeRecalculatedResponses(JudgeActor $I)
    {
        $I->wantTo("See accepted submission in Judge Submission after recalculation [STUB]");
    }

    public function cleanup(AdminActor $I)
    {
        $I->wantTo("Reset information back to default");
        $I->deleteDataSet();
        $I->addDatasets();
    }


}