<?php
use Codeception\Util\Locator;

class JudgingCest
{
    private static $dirs_results = array(
        "accepted" => "Accepted",
        "accepted_libraries" => "Accepted",
        "compile_error" => "Compile Error",
        "exceeds_time_limit" => "Exceeds Time Limit",
        "forbidden_word" => "Forbidden Word in Source",
        "format_error" => "Format Error",
        "incorrect_output" => "Incorrect Output",
        "runtime_error" => "Runtime Error"
    );
    private static $src_extensions = array(".c", ".java", ".cpp");
    private static $top_dir = "example_submissions";

    /**
     * Wrapper around teamActor that checks for correct feedback text for the user
     * @param TeamActor $I this is parameterized because I don't want to construct a new TeamActor for every test. Note that the actor does not have to be logged in
     * @param string $path see TeamActor.submitSolution()
     */
    private function submitSolution(TeamActor $I, string $path)
    {
        $I->login($I->attr["username"], $I->attr["password"]);
        $I->submitSolution($path);
        $I->see("Queued for judging");
    }

    /**
     * Checks to see if one of the submissions listed in the judge_response page has the given Auto Response
     * It also responds to the first submission with "error (reason unknown):
     * @param JudgeActor $I this is parameterized because I don't want to construct a new JudgeActor for every test. Note that the actor does not have to be logged in
     * @param string $judgement the test passes if this param matches an auto response
     */
    private function checkAutoResponse(JudgeActor $I, string $judgement)
    {
        $I->amOnMyPage("judge.php");
        $I->see($judgement);
        $I->rejectSubmission();
    }

    public function testAutoJudging(TeamActor $team, JudgeActor $judge)
    {
        $judge -> wantTo("Check that the auto judging software makes correct judgements");
        foreach (self::$dirs_results as $dir => $judgement){
            foreach (self::$src_extensions as $extension){
                $path = self::$top_dir . "/" . $dir . "/src" . $extension;
                $this->submitSolution($team, $path);
                $this->checkAutoResponse($judge, $judgement);
            }
        }

        foreach (array(".bin", ".o", ".txt") as $extension){
            $path = self::$top_dir . "/undefined_file_type/src" . $extension;
            $this->submitSolution($team, $path);
            $this->checkAutoResponse($judge, "Undefined File Type");
        }
    }
}
