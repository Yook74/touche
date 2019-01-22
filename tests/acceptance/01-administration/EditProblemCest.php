<?php
use Codeception\Util\Locator;

class EditProblemCest
{
    public static $newProblem = "MatchNums";
    public static $problemLoc = "Missouri";

    public function addNewProblem(AdminActor $I)
    {
        $I->wantTo("Create a new problem");
        $I->createProblem(self::$newProblem, self::$problemLoc);
        $I->see("Successful: New problem created");
    }

    public function editProblem(AdminActor $I)
    {
        $I->wantTo("Edit an existing problem");
        $I->editProblem();
        $I->see("Problem changed successfully");
    }

    public function cancelEdit(AdminActor $I)
    {
        $I->wantTo("Edit a problem, cancel editing, and then add a new problem");
        $I->problemEditCancel();
        $I->createProblem(self::$newProblem, self::$problemLoc);
        $I->see("Successful: New problem created");
        $I->see($I->attr["problem_name"]);
    }

    public function deleteProblem(AdminActor $I)
    {
        $I->wantTo("Delete a problem");
        $I->deleteProblem();
        $I->see("Problem deleted successfully");
    }

    public function uploadPDF(AdminActor $I)
    {
        $I->wantTo("Upload a PDF for a problem");
        $I->uploadPDF();
        $I->see("PDF uploaded successfully");
    }

    /**
     * @skip
     * Uploading HTML is a deprecated feature
     */
    public function uploadHTML(AdminActor $I)
    {
        $I->wantTo("Upload an HTML file for a problem");
        $I->uploadHTML();
        $I->see("HTML uploaded successfully");
    }

    public function judgeView(JudgeActor $I)
    {
        $I->wantTo("See the new problem as a Judge");
        $I->click("Problems");
        $I->see(self::$newProblem);
    }

    public function teamView(TeamActor $I)
    {
        $I->wantTo("See the new problem as a Team");
        $I->click("Problems");
        $I->see(self::$newProblem);
    }

    public function judgePDFView(JudgeActor $I)
    {
        $I->wantTo("View the PDF writeup as a Judge");
//        $I->amOnMyPage("problems.php");
//        $I->click("HTML");
//        $I->dontSee("Not Found");
        $I->amOnMyPage("problems.php");
        $I->click("PDF");
        $I->dontSee("Not Found");
    }

    public function teamPDFView(TeamActor $I)
    {
        $I->wantTo("View the PDF writeup as a Team");
//        $I->amOnMyPage("problems.php");
//        $I->click("HTML");
//        $I->dontSee("Not Found");
        $I->amOnMyPage("problems.php");
        $I->click("PDF");
        $I->dontSee("Not Found");
    }

    public function noLocationCreation(AdminActor $I)
    {
        $I->wantTo("Get rejected when the user tries to create a problem without a location");
        $I->createProblemNoLocation();
        $I->see("You forgot to set the problem location");
    }

    public function noNameCreation(AdminActor $I)
    {
        $I->wantTo("Get rejected when the user tries to create a problem without a name");
        $I->createProblemNoName();
        $I->see("You forgot to set the problem name");
    }

    public function cleanup(AdminActor $I)
    {
        $I->wantTo("Cleanup testing data");
        $I->deleteProblem();
        $I->deleteProblem();
        $I->createProblem();
        $I->addDatasets();
        $I->amOnMyPage("setup_problems.php");
        $I->see("AddTwoNumbers");
    }
}