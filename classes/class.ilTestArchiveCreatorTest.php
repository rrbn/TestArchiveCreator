<?php

namespace classes;

use ilObjTest;
use ilTestParticipantList;

class ilTestArchiveCreatorTest extends ilObjTest
{
    /**
     * Overridden to prevent access handling error with org units
     */
    public function buildStatisticsAccessFilteredParticipantList(): ilTestParticipantList
    {
        $list = new ilTestParticipantList($this);
        $list->initializeFromDbRows($this->getTestParticipants());

        return $list;
    }
}