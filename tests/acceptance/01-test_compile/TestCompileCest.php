<?php

class TestCompileCest
{
    private static $exampleSolution = "example_submissions/accepted/src.c";

    private static $responses = array(
        "accepted",
        "compile_error",
        "forbidden_word",
        "undefined_file_type"
    );

    public function testCompile(TeamActor $I)
    {
        $I->wantTo("Test the functionality of test compile");
        foreach(self::$responses as $response){
            switch($response){
                case "accepted":
                    $I->submitToTestCompile($response);
                    $I->see("No compile errors");
                    break;
                case "undefined_file_type":
                    $I->submitToTestCompile($response, "txt");
                    $I->see("Invalid file type");
                    break;
                case "compile_error":
                    $I->submitToTestCompile($response);
                    $I->see("Compile Errors");
                    $I->see("Error: expected");
                    break;
                case "forbidden_word":
                    $I->submitToTestCompile($response);
                    $I->see("Forbidden word in source");
                    break;
            }
        }
    }

    public function testCompileCancel(TeamActor $I)
    {
        $I->wantTo("Test that a user can cancel a test compile submission");
        $I->amOnMyPage("testcompile.php");
        $I->attachFile("source_file", self::$exampleSolution);
        $I->dontSee("No file chosen");
        $I->click("[type=reset]");
        $I->click("Submit");
        $I->see("No file selected for submission!");
    }
}