<?php

class IgnoreStdErrCest
{
    public static $stdErrorFilePath = "example_submissions/standard_error/src.c";

    public function submitStdErrorSolution(TeamActor $I)
    {
        $I->wantTo("Submit a solution that writes to standard error for testing");
        $I->submitSolution(self::$stdErrorFilePath);
    }

    public function failedSubmission(JudgeActor $I)
    {
        $I->wantTo("See that the judge fails the submission");
        $I->waitForAutoJudging();
        $I->see("Incorrect Output");
        $I->judgeSubmission();
    }

    public function ignoreStandardError(AdminActor $I)
    {
        $I->wantTo("Check the ignore standard error box");
        $I->checkStdErrorCheckbox();
        $I->see("Contest Edited Successfully");
    }

    public function submitSolutionAgain(TeamActor $I)
    {
        $I->wantTo("Submit the same solution again");
        $I->submitSolution(self::$stdErrorFilePath);
    }

    public function successfulSubmission(JudgeActor $I)
    {
        $I->wantTo("See that the judge accepts the submission");
        $I->waitForAutoJudging();
        $I->see("Accepted");
        $I->judgeSubmission();
    }

    public function cleanup(AdminActor $I)
    {
        $I->wantTo("Uncheck the 'Ignore Standard Error' box");
        $I->uncheckStdErrorCheckbox();
        $I->see("Contest Edited Successfully");
    }
}