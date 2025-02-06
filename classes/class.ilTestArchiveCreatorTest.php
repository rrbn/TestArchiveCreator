<?php

namespace classes;

use ilObjTest;
use ilTestParticipantList;

class ilTestArchiveCreatorTest extends ilObjTest
{
    /**
     * Don't get selected columns from ilEvaluationAllTableGUI
     * This would fail in cron job and undermine the privacy settings of the plugin
     */
    public function getEvaluationAdditionalFields(): array
    {
        return [];
    }
}
