<?php
use Codeception\Util\Locator;

class JudgingCest
{

    private static $simple_dirs = array(
        "accepted" => "Accepted",
        "accepted_libraries" => "Accepted",
        "compile_error" => "Compile Error",
        "exceeds_time_limit" => "Exceeds Time Limit",
        "format_error" => "Format Error",
        "incorrect_output" => "Incorrect Output",
        "runtime_error" => "Runtime Error"
    ); # These directories can all be handled the same. The undefined_file_type and forbudden_word dirs need special logic.

    private static $src_extensions = array(".c", ".java", ".cpp"); # Valid source file extensions. Python support is WIP

    private static $judgements = array("Accepted", "Compile Error", "Exceeds Time Limit", "Forbidden Word in Source",
        "Format Error", "Incorrect Output", "Runtime Error", "Undefined File Type", "Error (Reason Unknown)", "pending");

    private static $top_dir = "example_submissions";

    /**
     * Submits an array of paths all at once, waits, and then checks to see if they were all judged with the given judgement
     * @param JudgeActor $judge similar to $team. Note that neither actor needs to be logged in
     * @param TeamActor $team this is parameterized because I don't want to construct a new TeamActor for every test.
     * @param array $paths the file paths to submit
     * @param string $desired_judgement the judgment they should all receive
     */
    private function submitAndCheck(TeamActor $team, JudgeActor $judge, $paths, $desired_judgement){
        $team->login($team->attr["username"], $team->attr["password"]);
        foreach($paths as $path){
            $team->submitSolution($path);
            $team->see("Queued for judging");
        }

        $judge->login($judge->attr["username"], $judge->attr["password"]);
        $judge->wait(90);

        $judge->amOnMyPage("judge.php");
        foreach (self::$judgements as $judgement) {
            if ($judgement == $desired_judgement)
                $judge->see($judgement);
            else
                $judge->dontSee($judgement);
        }

        foreach ($paths as $_)
            $judge->rejectSubmission();

    }

    public function testAutoJudging(TeamActor $team, JudgeActor $judge)
    {
        $judge -> wantTo("Check that the auto judging software makes correct judgements");

        foreach (self::$simple_dirs as $dir => $judgement){
            $paths = array();
            foreach (self::$src_extensions as $extension){
                $path = self::$top_dir . "/" . $dir . "/src" . $extension;
                array_push($paths, $path);
            }
            $this->submitAndCheck($team, $judge, $paths, $judgement);
        }

        $paths = array();
        foreach (array(".c", ".cpp") as $extension){
            $path = self::$top_dir . "/forbidden_word/src" . $extension;
            array_push($paths, $path);
        }
        $this->submitAndCheck($team, $judge, $paths, "Forbidden Word in Source");

        $paths = array();
        foreach (array(".bin", ".o", ".txt") as $extension) {
            $path = self::$top_dir . "/undefined_file_type/src" . $extension;
            array_push($paths, $path);
        }
        $this->submitAndCheck($team, $judge, $paths, "Undefined File Type");
    }
}
