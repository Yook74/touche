<?php
use Codeception\Util\Locator;

class JudgingCest
{
    public static $outputFile1 = "_input1.out";
    public static $outputFile2 = "_input2.out";

    # This describes all the source file directories and the results their code should give
    private static $dir_judgement = array(
        "accepted" => "Accepted",
        "accepted_libraries" => "Accepted",
        "compile_error" => "Compile Error",
        "exceeds_time_limit" => "Exceeds Time Limit",
        "format_error" => "Format Error",
        "incorrect_output" => "Incorrect Output",
        "runtime_error" => "Runtime Error",
        "forbidden_word" => "Forbidden Word in Source",
        "undefined_file_type" => "Undefined File Type"
    );

    # Valid source file extensions. If the contest supports python
    private static $src_extensions = array("C" => ".c", "Java" => ".java", "C++" => ".cpp");

    # Invalid file extensions. These exist in the forbidden_word directory
    private static $bogus_extensions = array(".txt", ".o", ".bin");

    # These languages are checked for forbidden words
    private static $forbidden_word = array("C", "C++");

    # All of the possible statuses and judgements
    private static $judgements = array("Accepted", "Compile Error", "Exceeds Time Limit", "Forbidden Word in Source",
        "Format Error", "Incorrect Output", "Runtime Error", "Undefined File Type", "Error (Reason Unknown)", "pending");

    private static $top_dir = "example_submissions";

    # A list of teams that will be submitting problems. Populated in createTeams
    private $teams = array();

    /**
     * Uses several of the constants declared above to populate the list of teams with TeamActors
     * @param AdminActor $admin will be used to create the team in the contest
     * @param \Codeception\Scenario $scenario Needed to construct TeamActors
     */
    private function createTeams(AdminActor $admin, \Codeception\Scenario $scenario){
        $sat_counter = 0;
        foreach (self::$src_extensions as $lang_name => $extension){
            $admin->attrLogin();

            $teamName = "$lang_name team";
            $username = $lang_name;
            $password = "password";
            $admin->addSimpleTeam($teamName, $username, $password);

            $team = new TeamActor($scenario);
            $team->attr["name"] = $teamName;
            $team->attr["username"] = $username;
            $team->attr["password"] = $password;

            $team->attr["extension"] = $extension; # The file extension this team submits
            $team->attr["bogus_extension"] = self::$bogus_extensions[$sat_counter];
            $team->attr["forbidden_word"] = in_array($lang_name, self::$forbidden_word);
            # ^ True if this language is checked for forbidden words

            array_push($this->teams, $team);

            if ($sat_counter + 1 < count(self::$bogus_extensions))
                $sat_counter++;
        }
    }

    /**
     * Deletes all the teams and re-creates the default team.
     * @param AdminActor $admin some admin actor
     */
    private function deleteTeams(AdminActor $admin){
        $admin->attrLogin();
        foreach ($this->teams as $_){
            $admin->deleteTeam();
        }
        $admin->deleteTeam();
        $admin->addDefaultTeam();

        $this->teams = array();
    }

    /**
     * Wrapper around TeamActor->submitSolution
     * @param TeamActor $I one of the elements of the $teams array
     * @param string $sub_dir a subdirectory listed in dirs_results
     * @param bool $use_bogus true if the bogus_extension should be used
     */
    private function submitSolution(TeamActor $I, $sub_dir, $use_bogus=false){
        $I->attrLogin();
        if($use_bogus)
            $extension = $I->attr["bogus_extension"];
        else
            $extension = $I->attr['extension'];

        if ($sub_dir == "accepted" && $extension == '.java')
            $path = self::$top_dir . "/$sub_dir/Main$extension"; # This one file is special because it's a public class
        else
            $path = self::$top_dir . "/$sub_dir/src$extension";

        $I->submitSolution($path);
        $I->see("Queued for judging");
    }

    /**
     * Checks to see if the desired judgment is the only visible auto judgement
     * @param JudgeActor $I must be logged in
     * @param string $desired_judgement something like "Incorrect Output"
     */
    private function assertJudgmentsMatch(JudgeActor $I, $desired_judgement){
        $I->amOnMyPage("judge.php");
        foreach (self::$judgements as $judgement) {
            if ($judgement == $desired_judgement)
                $I->see($judgement);
            else
                $I->dontSee($judgement);
        }
    }

    /**
     * Test the judge response page
     * @param JudgeActor $I must be logged in
     * @param string $judgment is the error to test
     */
    private function testJudgeResponse(JudgeActor $I, $judgment){
        $I->amOnMyPage("judge.php");
        $I->click("judge submission");
        $problemNumber = $I->getProblemNumber();
        $file1 = $problemNumber . self::$outputFile1;
        $file2 = $problemNumber . self::$outputFile2;
        switch($judgment){
            case "Forbidden Word In Source":
            case "Compile Error":
                $I->see($judgment);
                $fullField = $I->grabTextFrom("[name=error_field]");
                $I->assertNotEquals("", $fullField);
                break;
            case "Format Error":
                $I->see("No-whitespace diff Succeeded");
            case "Incorrect Output":
                $I->see($file1);
                $I->click("[name=\"$file1\"]");
                $I->switchToNextTab();
                $I->see("Comparing Output Files");
                $I->closeTab();
                $I->see($file2);
                $I->click("[name=\"$file2\"]");
                $I->switchToNextTab();
                $I->see("Comparing Output Files");
                $I->closeTab();
                break;
            default:
                $I->see($judgment);
                break;
        }
    }

    public function testAutoJudging(AdminActor $admin, JudgeActor $judge, \Codeception\Scenario $scenario)
    {
        $judge -> wantTo("Check that the auto judging software makes correct judgements (takes a while)");

        $this->createTeams($admin, $scenario);

        $run_length_submitted = false; # we will only submit one run_length_exceeded file because it takes a long time
        foreach (self::$dir_judgement as $dir => $judgement){
            $numTeams = count($this->teams);
            $num_submissions = 0;
            $wait_per_submission = 8.5; # How long each submission takes to be judged
            foreach ($this->teams as $team){
                switch ($judgement){

                    case "Exceeds Time Limit":
                        $wait_per_submission = 80;

                        if ($run_length_submitted)
                            break;
                        $run_length_submitted = true;

                        $this->submitSolution($team, $dir);
                        $num_submissions++;
                        $numTeams = 1;
                        break;

                    case "Undefined File Type":
                        $this->submitSolution($team, $dir, true);
                        $num_submissions++;
                        break;

                    case "Forbidden Word in Source":
                        $numTeams = count(self::$forbidden_word);
                        if(!$team->attr["forbidden_word"])
                            break;

                    default:
                        $this->submitSolution($team, $dir);
                        $num_submissions++;
                        break;
                }
            }
            $judge->attrLogin();
            $judge->waitForAutoJudging($wait_per_submission * $num_submissions, $numTeams);

            $this->assertJudgmentsMatch($judge, $judgement);
            $this->testJudgeResponse($judge, $judgement);
            for (; $num_submissions > 0; $num_submissions--){
                $judge->judgeSubmission();
            }
        }

        $this->deleteTeams($admin);
    }
}
