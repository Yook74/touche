<?php
use Codeception\Util\Locator;

class DeleteCest
{
    public function deleteContest(CreatorActor $I, \Helper\Acceptance $helper)
    {
        $I->wantTo("Delete the contest");
        $I->deleteContest($helper);
    }
}
