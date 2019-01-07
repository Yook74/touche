<?php

class ExtendCest
{
    private $judgeEndHour;
    public static $extendHours = 1;
    public function getJudgeTimeTest(JudgeActor $I)
    {
        $I->wantTo("Get the original ending hour");
        $this->judgeEndHour = $I->getEndHour();
    }

    public function extendContest(AdminActor $I)
    {
        $I->wantTo("Extend the contest");
        $I->extendContest(self::$extendHours,0,0);
        $I->see("Contest Extended Successfully");
    }

    public function checkContestExtension(JudgeActor $I)
    {
        $I->wantTo("See if the contest has been extended as a Judge");

        $this->judgeEndHour += self::$extendHours;
        if (strlen($this->judgeEndHour) == 1){
            $this->judgeEndHour = "0$this->judgeEndHour";
        }
        $I->see("Time Till Contest End: $this->judgeEndHour");
    }

    public function duplicateExtendContest(AdminActor $I)
    {
        $this->extendContest($I);
    }

    public function duplicateCheckContestExtension(JudgeActor $I)
    {
        $this->checkContestExtension($I);
    }

    public function editAndExtendContest(AdminActor $I){
        $I->wantTo("Edit a contest and then extend its length");
        $I->editContestLength(0,0,02);
        $I->extendContest(4,0,0);
        $I->see("Contest Extended Successfully");
    }

    /**
     * @depends editAndExtendContest
     */
    public function teamContestExtension(TeamActor $I)
    {
        $I->wantTo("Check to see that a contest can be participated in after extension");
        //$I->wait(10);
        $I->dontSee("Contest Is Over");
    }

    public function resetContestTimer(AdminActor $I)
    {
        $I->wantTo("Reset the contest timer");
        $I->resetContestTime();
        $I->see("Contest Sucessfully Edited");
    }
}