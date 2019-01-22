<?php


/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = NULL)
 *
 * @SuppressWarnings(PHPMD)
*/
class AdminActor extends AcceptanceTester
{
    private $helper; // This object has access to all the acceptance helper methods

    /**
     * AdminActor constructor.
     * @param \Codeception\Scenario $scenario an opaque object that codeception will automatically pass in
     * @param Helper\Acceptance $helper codeception will make this for us and we need it to connect to the DB
     * @throws \Codeception\Exception\ModuleException
     */
    public function __construct(\Codeception\Scenario $scenario, Helper\Acceptance $helper)
    {
        parent::__construct($scenario, "adminAttr.ini");
        $helper->connectToDatabase(CreatorActor::getContestName());
        $this->helper = $helper;
    }

/* Team Actions */
    /**
     * Adds the team described in teamAttr.ini
     * Some of these fields are hardcoded because I don't think they're used for anything
     */
    public function addDefaultTeam()
    {
        $I = $this;
        $teamAttr = parse_ini_file("teamAttr.ini");

        $I->amOnMyPage("setup_teams.php");
        $I->fillField('team_name', $teamAttr['name']);
        $I->fillField('organization', 'Organization');
        $I->fillField('username', $teamAttr['username']);
        $I->fillField('password', $teamAttr['password']);
        # Could fill site dropdown here
        $I->fillField('contestant_1_name', "Contestant One");
        $I->fillField('contestant_2_name', "Contestant Two");
        $I->fillField('contestant_3_name', "Contestant Three");
        $I->fillField('alternate_name', "Alternate");
        $I->fillField('email', "email@example.com");
        $I->fillField('coach_name', "Dr. Coach");
        $I->click('submit');
    }

    /**
     * Adds a team with the given parameters
     * Some of the fields are left blank
     */
    public function addSimpleTeam($name, $username, $password)
    {
        $I = $this;
        $I->amOnMyPage("setup_teams.php");
        $I->fillField('team_name', $name);
        $I->fillField('username', $username);
        $I->fillField('password', $password);
        $I->click('submit');
    }

    /**
     * Deletes the first team it sees because it is stupid
     */
    public function deleteTeam()
    {
        $I = $this;
        $I->amOnMyPage("setup_teams.php");
        $I->click("Delete");
    }

    /**
     * Change the default team's password
     */
    public function changeTeamPassword($newPassword)
    {
        $I = $this;
        $I->click("Teams");
        $I->click("Edit");
        $I->fillField("password",$newPassword);
        $I->click("Submit");
    }

    /**
     * Set the default team's password back
     */
    public function resetTeamPassword()
    {
        $I = $this;
        $teamAttr = parse_ini_file('teamAttr.ini');

        $I->click("Teams");
        $I->click("Edit");
        $I->fillField("password",$teamAttr['password']);
        $I->click("Submit");
    }

    /**
     * Edit a team's details
     */
    public function editTeam()
    {
        $I = $this;
        $teamAttr = parse_ini_file('teamAttr.ini');

        $I->click("Teams");
        $I->click("Edit");
        $I->fillField("team_name",$teamAttr["name"]);
        $I->fillField("password",$teamAttr["password"]);
        $I->fillField("username",$teamAttr["username"]);
        $I->fillField("organization","");
        $I->click("Submit");
    }

    /**
     * Start to edit, stop, and then add a new team
     */
    public function teamEditCancel()
    {
        $I = $this;
        $I->click("Teams");
        $I->click("Edit");
        $I->click("Teams");
        $I->addSimpleTeam("Jeff Bezos Fan Club","jeff","Amazon","jeffPass7!");
    }

/* Judge Actions */
    /**
     * Change the default judge's password
     */
    public function changeJudgePassword($newPassword)
    {
        $I = $this;
        $I->click("Edit contest details");
        $I->fillField("password",$newPassword);
        $I->click("Submit");
    }

    /**
     * Set the default judge's password back
     */
    public function resetJudgePassword()
    {
        $I = $this;
        $judgeAttr = parse_ini_file('judgeAttr.ini');

        $I->click("Edit contest details");
        $I->fillField("password",$judgeAttr['password']);
        $I->click("Submit");
    }

    /**
     * Set the judge's credentials to those described in judgeAttr.ini
     */
    public function setJudgeCredentials()
    {
        $I = $this;
        $judgeAttr = parse_ini_file('judgeAttr.ini');

        $I->amOnMyPage("setup_contest.php");
        $I->fillField("username", $judgeAttr['username']);
        $I->fillField("password", $judgeAttr['password']);
        $I->click("B1");
    }

/* Problem Actions */
    /**
     * Adds a problem with the given parameters or takes them from adminAttr if not supplied
     */
    public function createProblem($name = null, $loc  = null)
    {
        $I = $this;
        if ($name == null){
            $name = $this->attr["problem_name"];
        }
        if ($loc == null){
            $loc = $this->attr["problem_location"];
        }

        $I->amOnMyPage("setup_problems.php");
        $I->fillField('problem_name', $name);
        $I->fillField('problem_loc', $loc);
        $I->click("Submit");
    }

    /**
     * Edit a problem
     */
    public function editProblem()
    {
        $I = $this;
        $I->amOnMyPage("setup_problems.php");
        $I->click("Edit");
        $I->fillField("problem_note", "Hello, tester.");
        $I->click("Submit");
    }

    /**
     * Start to edit a problem and then navigate away
     */
    public function problemEditCancel()
    {
        $I = $this;
        $I->amOnMyPage("setup_problems.php");
        $I->click("Edit");
        $I->fillField("problem_note", "Hello, tester.");
        $I->click("Problems");
    }

    /**
     * Delete a problem
     */
    public function deleteProblem($deleteID = null)
    {
        $I = $this;
        $I->amOnMyPage("setup_problems.php");
        if($deleteID == null)
            $I->click("Delete");
        else
            $I->click("[name=\"delete$deleteID\"]");
    }

    /**
     * Upload a PDF for a problem
     */
    public function uploadPDF()
    {
        $I = $this;
        $I->amOnMyPage("setup_problems.php");
        $I->click("Edit");
        $I->attachFile("pdf_file", $this->attr["pdf_data"]);
        $I->click("Submit");
    }

    /**
     * Upload a PDF for a problem
     */
    public function uploadHTML()
    {
        $I = $this;
        $I->amOnMyPage("setup_problems.php");
        $I->click("Edit");
        $I->attachFile("html_file", $this->attr["html_data"]);
        $I->click("Submit");
    }

    /**
     * Create a problem with no location
     */
    public function createProblemNoLocation()
    {
        $I = $this;
        $I->amOnMyPage("setup_problems.php");
        $I->fillField("problem_name", "FailureIsAnOption");
        $I->click("Submit");
    }

    /**
     * Create a problem with no name
     */
    public function createProblemNoName()
    {
        $I = $this;
        $I->amOnMyPage("setup_problems.php");
        $I->fillField("problem_loc", "Upland");
        $I->click("Submit");
    }

    /**
     * Adds two datasets to the default problem
     */
    public function addDatasets()
    {
        $I = $this;
        $I->amOnMyPage("setup_data_sets.php");
        $I->click("Add new data set"); //If more than one problem exists this may cause issues
        $I->attachFile("data_set_in", $this->attr["data_in_path1"]);
        $I->attachFile("data_set_out", $this->attr["data_out_path1"]);
        $I->click("Submit");
        $I->attachFile("data_set_in", $this->attr["data_in_path2"]);
        $I->attachFile("data_set_out", $this->attr["data_out_path2"]);
        $I->click("Submit");
    }

    /**
     * Delete the first dataset
     */
    public function deleteDataset()
    {
        $I = $this;
        $I->amOnMyPage("setup_data_sets.php");
        $I->click("Delete");
    }

/* Site Actions */
    /**
     * Create a site described in adminAtr.ini
     */
    public function addSite()
    {
        $I = $this;
        $I->amOnMyPage('setup_site.php');
        $I->fillFieldWithAttr('site_name','site_name');
        $I->click('submit');
    }

/* Duration Actions */
    /**
     * Edit the length of a contest
     */
    public function editContestLength($hours, $minutes, $seconds)
    {
        $I = $this;
        $I->amOnMyPage('setup_contest.php');
        $I->fillField('end_hour', $hours);
        $I->fillField('end_minute', $minutes);
        $I->fillField('end_second', $seconds);
        $I->click("Submit");
    }

    /**
     * Clean up ContestEndCest by resetting time
     */
    public function resetContestTime()
    {
        $I = $this;
        $I->amOnMyPage('setup_contest.php');
        $I->fillField('end_hour', $I->attr["default_start_hour"]);
        $I->fillField('end_minute', $I->attr["default_start_minute"]);
        $I->fillField('end_second', $I->attr["default_start_second"]);
        $I->click("Submit");
    }

    /**
     * Extend the contest
     */
    public function extendContest($hours,$minutes,$seconds)
    {
        $I = $this;
        $I->amOnMyPage("misc.php");
        $I->fillField("ext_hour", $hours);
        $I->fillField("ext_minute", $minutes);
        $I->fillField("ext_second", $seconds);
        $I->click("Extend Contest");
    }

    /**
     * Set the time until the freeze
     */
    public function editFreezeTime($hours, $minutes, $seconds)
    {
        $I = $this;
        $I->amOnMyPage('setup_contest.php');
        $I->fillField('freeze_hour', $hours);
        $I->fillField('freeze_minute', $minutes);
        $I->fillField('freeze_second', $seconds);
        $I->click("Submit");
    }

    /**
     * Reset the freeze time
     */
    public function resetFreezeTime()
    {
        $I = $this;
        $I->amOnMyPage('setup_contest.php');
        $I->fillField('freeze_hour', $I->attr["default_freeze_hour"]);
        $I->fillField('freeze_minute', $I->attr["default_freeze_minute"]);
        $I->fillField('freeze_second', $I->attr["default_freeze_minute"]);
        $I->click("Submit");
    }

/* Category Actions */

    /**
     * Creates a new category
     */
    public function createCategory($catName)
    {
        $I = $this;
        $I->amOnMyPage("setup_categories.php");
        $I->fillField('category_name', $catName);
        $I->click("Submit");
    }

    /**
     *  Edits the first category
     */
    public function editCategory($catName)
    {
        $I = $this;
        $I->amOnMyPage("setup_categories.php");
        $I->click("Edit");
        $I->fillField('category_name', $catName);
        $I->click("Submit");
    }

    /**
     * Deletes the first category
     */
    public function deleteCategory()
    {
        $I = $this;
        $I->amOnMyPage("setup_categories.php");
        $I->click("Delete");
    }

    /**
     * Start to edit a problem and then navigate away
     */
    public function categoryEditCancel()
    {
        $I = $this;
        $I->amOnMyPage("setup_categories.php");
        $I->click("Edit");
        $I->fillField("category_name", "Its nice to see you again.");
        $I->click("Problems");
    }

    private function getCheckBoxName($catName)
    {
        $I = $this;
        $category_id_array = $I->grabFromDatabase('CATEGORIES', 'CATEGORY_ID',
            array('CATEGORY_NAME' => $catName));
        $team_id_array = $I->grabFromDatabase('TEAMS','TEAM_ID',array());
        return "$team_id_array[0]|$category_id_array[0]";
    }
    /**
     * Check the first team's first category box
     */
    public function checkTeamCategoryBox($catName)
    {
        $I = $this;
        $I->amOnMyPage("setup_team_category.php");
        $checkBox = $I->getCheckBoxName($catName);
        $I->checkOption($checkBox);
        $I->click("Make Changes");
        return $checkBox;
    }

    /**
     * Unheck the first team's first category box
     */
    public function uncheckTeamCategoryBox($catName)
    {
        $I = $this;
        $I->amOnMyPage("setup_team_category.php");
        $checkBox = $I->getCheckBoxName($catName);
        $I->uncheckOption($checkBox);
        $I->click("Make Changes");
        return $checkBox;
    }

/* Header and Forbidden Actions */

    /**
     * Add a $headerFile to $language's headers
     */
    public function addHeader($language, $headerFile)
    {
        $I = $this;
        $I->amOnMyPage("setup_headers");
        $I->click(['name' => $language."Edit"]);
        $I->appendField("edit_headers", $headerFile);
        $I->click("Submit");
    }

    /**
     * Delete a $headerFile from $language's headers
     */
    public function deleteHeader($language, $headerFile)
    {
        $I = $this;
        $I->amOnMyPage("setup_headers");
        $I->click(['name' => $language."Edit"]);
        $I->removeFromField("textarea", $headerFile);
        $I->click("Submit");
    }

    /**
     * Remove $text from a field specified by $field
     */
    public function removeFromField($field, $text)
    {
        $I = $this;
        $originalText = $I->grabTextFrom($field);
        $stringToBeDeletedPos = strpos($originalText, $text);
        $replacementTextBeginning = substr($originalText, 0, $stringToBeDeletedPos);
        $replacementTextEnding = substr($originalText, $stringToBeDeletedPos + strlen($text));
        $I->fillField($field, $replacementTextBeginning);
        $I->appendField($field, $replacementTextEnding);
    }

    /**
     * Add a $word to the $language's forbidden words list
     */
    public function addForbiddenWord($language, $word)
    {
        $I = $this;
        $I->amOnMyPage("setup_forbidden");
        $I->click(['name' => $language."Edit"]);
        $I->appendField("edit_forbidden_words", $word);
        $I->click("Submit");
    }

    /**
     * Delete a $word from the $language's forbidden words list
     */
    public function deleteForbiddenWord($language, $word)
    {
        $I = $this;
        $I->amOnMyPage("setup_forbidden");
        $I->click(['name' => $language."Edit"]);
        $I->removeFromField("textarea", $word);
        $I->click("Submit");
    }

    /**
     * Uncheck all the boxes in the forbidden words row
     */
    public function uncheckForbiddenWordsBoxes()
    {
       $I = $this;
       $I->amOnMyPage("setup_contest.php");
       $I->uncheckOption("forbidden_c");
       $I->uncheckOption("forbidden_cpp");
       $I->uncheckOption("forbidden_java");
       $I->uncheckOption("forbidden_python2");
       $I->uncheckOption("forbidden_python3");
       $I->click("Submit");
    }

    /**
     * Check all the boxes in the forbidden words row
     */
    public function checkForbiddenWordsBoxes()
    {
        $I = $this;
        $I->amOnMyPage("setup_contest.php");
        $I->checkOption("forbidden_c");
        $I->checkOption("forbidden_cpp");
        $I->checkOption("forbidden_java");
        $I->checkOption("forbidden_python2");
        $I->checkOption("forbidden_python3");
        $I->click("Submit");
    }

    /**
     * Uncheck all the boxes in the headers row
     */
    public function uncheckHeadersBoxes()
    {
        $I = $this;
        $I->amOnMyPage("setup_contest.php");
        $I->uncheckOption("headers_c");
        $I->uncheckOption("headers_cpp");
        $I->uncheckOption("headers_java");
        $I->uncheckOption("headers_python2");
        $I->uncheckOption("headers_python3");
        $I->click("Submit");
    }

    /**
     * Check all the boxes in the headers row
     */
    public function checkHeadersBoxes()
    {
        $I = $this;
        $I->amOnMyPage("setup_contest.php");
        $I->checkOption("headers_c");
        $I->checkOption("headers_cpp");
        $I->checkOption("headers_java");
        $I->checkOption("headers_python2");
        $I->checkOption("headers_python3");
        $I->click("Submit");
    }

/* Clearing Actions */

    /**
     * Navigate to Misc and clear a contest
     */
    public function clearContest()
    {
        $I = $this;
        $I->amOnMyPage("misc.php");
        $I->click("Clear Contest");
    }

 /* Cloning Actions */

    /**
     * Create new cloned contest with name $newContestName
     */
    public function cloneContest($newContestName)
    {
        $I = $this;
        $I->amOnMyPage("misc.php");
        $I->fillField("clone_name", $newContestName);
        $I->click("Clone Contest");
    }

/* Misc. */

    /**
     * Check the 'Ignore Standard Error?' checkbox
     */
    public function checkStdErrorCheckbox()
    {
        $I = $this;
        $I->amOnMyPage("setup_contest.php");
        $I->checkOption("stderr");
        $I->click("Submit");
    }

    /**
     * Uncheck the 'Ignore Standard Error?' checkbox
     */
    public function uncheckStdErrorCheckbox()
    {
        $I = $this;
        $I->amOnMyPage("setup_contest.php");
        $I->uncheckOption("stderr");
        $I->click("Submit");
    }

    /**
     * Click the recalculate responses button
     */
    public function recalculateResponses()
    {
        $I = $this;
        $I->amOnMyPage("misc.php");
        $I->click("recalculate responses");
        $I->acceptPopup();
    }
}
