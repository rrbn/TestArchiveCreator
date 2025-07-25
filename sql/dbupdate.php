<#1>
<?php
    /**
     * Copyright (c) 2017 Institut fuer Lern-Innovation, Friedrich-Alexander-Universitaet Erlangen-Nuernberg
     * GPLv3, see docs/LICENSE
     */

    /**
     * Test Archive Creator Plugin: database update script
     *
     * @author Fred Neumann <fred.neumann@fau.de>
     */
?>
<#2>
<?php
    $fields = array(
        'obj_id' => array(
            'type' => 'integer',
            'length' => 4,
            'notnull' => true
        ),
        'status' => array(
            'type' => 'text',
            'length' => 10,
            'notnull' => true,
            'default' => 'inactive'
        ),
        'schedule' => array(
            'type' => 'timestamp',
            'notnull' => false
        ),
        'pass_selection' => array(
            'type' => 'text',
            'length' => 10,
            'notnull' => true,
            'default' => 'scored'
        )
    );

    $ilDB->createTable('tarc_ui_settings', $fields);
    $ilDB->addPrimaryKey('tarc_ui_settings', array('obj_id'));
?>
<#3>
<?php
    if (!$ilDB->tableColumnExists('tarc_ui_settings', 'zoom_factor')) {
		$ilDB->addTableColumn('tarc_ui_settings', 'zoom_factor', array(
		        'type' => 'float',
                'notnull' => true,
                'default' => 1
		));
	}
?>
<#4>
<?php
    if (!$ilDB->tableColumnExists('tarc_ui_settings', 'orientation')) {
        $ilDB->addTableColumn('tarc_ui_settings', 'orientation', array(
            'type' => 'text',
            'length' => 10,
            'notnull' => true,
            'default' => 'landscape'
        ));
    }
?>
<#5>
<?php
if (!$ilDB->tableColumnExists('tarc_ui_settings', 'random_questions')) {
	$ilDB->addTableColumn('tarc_ui_settings', 'random_questions', array(
		'type' => 'text',
		'length' => 10,
		'notnull' => true,
		'default' => 'used'
	));
}
?>
<#6>
<?php
if (!$ilDB->tableColumnExists('tarc_ui_settings', 'include_questions')) {
    $ilDB->addTableColumn('tarc_ui_settings', 'include_questions', array(
        'type' => 'integer',
        'notnull' => true,
        'default' => 1
    ));
}
?>
<#7>
<?php
if (!$ilDB->tableColumnExists('tarc_ui_settings', 'questions_with_best_solution')) {
    $ilDB->addTableColumn('tarc_ui_settings', 'questions_with_best_solution', array(
        'type' => 'integer',
        'notnull' => true,
        'default' => 1
    ));
}
?>
<#8>
<?php
if (!$ilDB->tableColumnExists('tarc_ui_settings', 'include_answers')) {
    $ilDB->addTableColumn('tarc_ui_settings', 'include_answers', array(
        'type' => 'integer',
        'notnull' => true,
        'default' => 1
    ));
}
?>
<#9>
<?php
if (!$ilDB->tableColumnExists('tarc_ui_settings', 'answers_with_best_solution')) {
    $ilDB->addTableColumn('tarc_ui_settings', 'answers_with_best_solution', array(
        'type' => 'integer',
        'notnull' => true,
        'default' => 1
    ));
}
?>
<#10>
<?php
if (!$ilDB->tableColumnExists('tarc_ui_settings', 'min_rendering_wait')) {
    $ilDB->addTableColumn('tarc_ui_settings', 'min_rendering_wait', array(
        'type' => 'integer',
        'notnull' => true,
        'default' => 200
    ));
}
?>
<#11>
<?php
if (!$ilDB->tableColumnExists('tarc_ui_settings', 'max_rendering_wait')) {
    $ilDB->addTableColumn('tarc_ui_settings', 'max_rendering_wait', array(
        'type' => 'integer',
        'notnull' => true,
        'default' => 2000
    ));
}
?>
<#12>
<?php
if ($ilDB->tableColumnExists('tarc_ui_settings', 'min_rendering_wait')) {
    $ilDB->dropTableColumn('tarc_ui_settings', 'min_rendering_wait');
}
?>
<#13>
<?php
if ($ilDB->tableColumnExists('tarc_ui_settings', 'max_rendering_wait')) {
    $ilDB->dropTableColumn('tarc_ui_settings', 'max_rendering_wait');
}
?>