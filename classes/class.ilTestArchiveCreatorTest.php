<?php

declare(strict_types=1);

class ilTestArchiveCreatorTest extends ilObjTest
{

    public function __construct(
        int $id = 0,
        bool $a_call_by_reference = true,
        protected ?int $default_user_id = null
    ) {
        parent::__construct($id, $a_call_by_reference);
    }

    /**
     * Don't get selected columns from ilEvaluationAllTableGUI
     * This would fail in cron job and undermine the privacy settings of the plugin
     */
    public function getEvaluationAdditionalFields(): array
    {
        return [];
    }

    /**
     * Use tweaked TestEvaluationFactory to deal with deleted user accounts
     */
    public function getUnfilteredEvaluationData(): ilTestEvaluationData
    {
        return (new ilTestArchiveCreatorTestEvaluationFactory($this->db, $this, $this->default_user_id))
            ->getEvaluationData();
    }
}
