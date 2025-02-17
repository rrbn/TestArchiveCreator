<?php

declare(strict_types=1);

/**
 * Tweaked ilTestEvaluation factory to deal with deleted user accounts
 */
class ilTestArchiveCreatorTestEvaluationFactory extends ilTestEvaluationFactory
{
    protected ilTestArchiveCreatorPlugin $plugin;

    public function __construct(
        protected ilDBInterface $db,
        protected ilObjTest $test_obj,
        protected ?int $default_user_id = null
    ) {
        global $DIC;
        $this->plugin = $DIC["component.factory"]->getPlugin('tarc_ui');
        $this->default_user_id = $this->default_user_id;
    }


    protected function queryEvaluationData(array $active_ids): array
    {
        $rows = [];
        foreach(parent::queryEvaluationData($active_ids) as $row) {
            if (!isset($row['usr_id'])) {
                $row['usr_id'] = $this->default_user_id;
                $row['firstname'] = '';
                $row['lastname'] = $this->plugin->txt('deleted_account');
                $row['title'] = '';
            }
            $rows[] = $row;
        }
        return $rows;
    }
}