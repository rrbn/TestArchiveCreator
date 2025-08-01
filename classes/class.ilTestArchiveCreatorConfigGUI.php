<?php

// Copyright (c) 2017 Institut fuer Lern-Innovation, Friedrich-Alexander-Universitaet Erlangen-Nuernberg, GPLv3, see LICENSE


/**
 * Test archive creator configuration user interface class
 *
 * @author Fred Neumann <fred.neumann@fau.de>
 * @author Jesus Copado <jesus.copado@fau.de>
 *
 *  @ilCtrl_IsCalledBy ilTestArchiveCreatorConfigGUI: ilObjComponentSettingsGUI
 */
class ilTestArchiveCreatorConfigGUI extends ilPluginConfigGUI
{
    protected ilAccessHandler $access;
    protected ilCtrl $ctrl;
    protected ilLanguage $lng;
    protected ilTabsGUI $tabs;
    protected ilToolbarGUI $toolbar;
    protected ilGlobalTemplateInterface $tpl;
    /** @var ilTestArchiveCreatorPlugin */
    protected ilPlugin $plugin;
    protected ilTestArchiveCreatorConfig $config;

    /**
     * Constructor.
     */
    public function __construct()
    {
        global $DIC;

        $this->access = $DIC->access();
        $this->ctrl = $DIC->ctrl();
        $this->lng = $DIC->language();
        $this->tabs = $DIC->tabs();
        $this->toolbar = $DIC->toolbar();
        $this->tpl = $DIC->ui()->mainTemplate();

        $this->lng->loadLanguageModule('assessment');
    }


    /**
     * Handles all commands, default is "configure"
     */
    public function performCommand(string $cmd): void
    {
        $this->plugin = $this->getPluginObject();
        $this->config = $this->plugin->getConfig();

        switch ($cmd) {
            case "saveConfiguration":
                $this->saveConfiguration();
                break;

            case "configure":
            default:
                $this->editConfiguration();
                break;
        }
    }

    /**
     * Edit the configuration
     */
    protected function editConfiguration(): void
    {
        $form = $this->initConfigForm();
        $this->tpl->setContent($form->getHTML());
    }

    /**
     * Save the edited configuration
     */
    protected function saveConfiguration(): void
    {
        $form = $this->initConfigForm();
        if (!$form->checkInput()) {
            $form->setValuesByPost();
            $this->tpl->setContent($form->getHTML());
            return;
        }

        $this->config->pdf_engine = $form->getInput('pdf_engine');
        $this->config->embed_assets = $form->getInput('embed_assets');

        $this->config->hide_standard_archive = $form->getInput('hide_standard_archive');
        $this->config->keep_creation_directory = $form->getInput('keep_creation_directory');
        $this->config->keep_jobfile = $form->getInput('keep_jobfile');
        $this->config->ignore_ssl_errors = $form->getInput('ignore_ssl_errors');

        $this->config->bs_node_module_path = $form->getInput('bs_node_module_path');
        $this->config->bs_chrome_path = $form->getInput('bs_chrome_path');
        $this->config->bs_node_path = $form->getInput('bs_node_path');

        $this->config->server_url = $form->getInput('server_url');

        $this->config->with_login = $form->getInput('with_login');
        $this->config->with_matriculation = $form->getInput('with_matriculation');
        $this->config->with_results = $form->getInput('with_results');
        if ($this->plugin->isTestLogActive()) {
            $this->config->include_test_log = $form->getInput('include_test_log');
        }
        if ($this->plugin->isExaminationProtocolPluginActive()) {
            $this->config->include_examination_protocol = $form->getInput('include_examination_protocol');
        }

        $this->config->include_questions = $form->getInput('include_questions');
        $this->config->include_answers = $form->getInput('include_answers');
        $this->config->questions_with_best_solution = $form->getInput('questions_with_best_solution');
        $this->config->answers_with_best_solution = $form->getInput('answers_with_best_solution');

        $this->config->pass_selection = $form->getInput('pass_selection');
        $this->config->random_questions = $form->getInput('random_questions');

        $this->config->zoom_factor = $form->getInput('zoom_factor') / 100;
        $this->config->orientation = $form->getInput('orientation');

        $this->config->user_allow = $form->getInput('user_allow');

        $this->config->save();

        $this->tpl->setOnScreenMessage('success', $this->lng->txt("settings_saved"), true);
        $this->ctrl->redirect($this, 'editConfiguration');
    }

    /**
     * Fill the configuration form
     */
    protected function initConfigForm(): ilPropertyFormGUI
    {
        $form = new ilPropertyFormGUI();
        $form->setFormAction($this->ctrl->getFormAction($this, 'editConfiguration'));
        $form->setTitle($this->plugin->txt('plugin_configuration'));


        $hide = new ilCheckboxInputGUI($this->plugin->txt('hide_standard_archive'), 'hide_standard_archive');
        $hide->setInfo($this->plugin->txt('hide_standard_archive_info'));
        $hide->setChecked($this->config->hide_standard_archive);
        $form->addItem($hide);

        $keep = new ilCheckboxInputGUI($this->plugin->txt('keep_creation_directory'), 'keep_creation_directory');
        $keep->setInfo($this->plugin->txt('keep_creation_directory_info'));
        $keep->setChecked($this->config->keep_creation_directory);
        $form->addItem($keep);

        $job = new ilCheckboxInputGUI($this->plugin->txt('keep_jobfile'), 'keep_jobfile');
        $job->setInfo($this->plugin->txt('keep_jobfile_info'));
        $job->setChecked($this->config->keep_jobfile);
        $keep->addSubItem($job);

        $header = new ilFormSectionHeaderGUI();
        $header->setTitle($this->plugin->txt('generation_settings'));
        $form->addItem($header);

        $assets = new ilCheckboxInputGUI($this->plugin->txt('embed_assets'), 'embed_assets');
        $assets->setInfo($this->plugin->txt('embed_assets_info'));
        $assets->setChecked($this->config->embed_assets);
        $form->addItem($assets);

        $engine = new ilRadioGroupInputGUI($this->plugin->txt('pdf_engine'), 'pdf_engine');
        $engine->setValue($this->config->pdf_engine);
        $form->addItem($engine);

        $none = new ilRadioOption($this->plugin->txt('pdf_engine_none'), ilTestArchiveCreatorConfig::ENGINE_NONE);
        $none->setInfo($this->plugin->txt('pdf_engine_none_info'));
        $engine->addOption($none);

        // Local Puppeteer

        $local = new ilRadioOption($this->plugin->txt('pdf_engine_browsershot'), ilTestArchiveCreatorConfig::ENGINE_LOCAL);
        $local->setInfo($this->plugin->txt('pdf_engine_local_info'));
        $engine->addOption($local);

        $path = new ilTextInputGUI($this->plugin->txt('bs_node_module_path'), 'bs_node_module_path');
        $path->setInfo($this->plugin->txt('bs_node_module_path_info'));
        $path->setValue($this->config->bs_node_module_path);
        $local->addSubItem($path);

        $path = new ilTextInputGUI($this->plugin->txt('bs_chrome_path'), 'bs_chrome_path');
        $path->setInfo($this->plugin->txt('bs_chrome_path_info'));
        $path->setValue($this->config->bs_chrome_path);
        $local->addSubItem($path);

        $path = new ilTextInputGUI($this->plugin->txt('bs_node_path'), 'bs_node_path');
        $path->setInfo($this->plugin->txt('bs_node_path_info'));
        $path->setValue($this->config->bs_node_path);
        $local->addSubItem($path);

        // Remote Puppeteer Server

        $server = new ilRadioOption($this->plugin->txt('pdf_engine_server'), ilTestArchiveCreatorConfig::ENGINE_SERVER);
        $server->setInfo($this->plugin->txt('pdf_engine_server_info'));
        $engine->addOption($server);

        $url = new ilTextInputGUI($this->plugin->txt('server_url'), 'server_url');
        $url->setInfo($this->plugin->txt('server_url_info'));
        $url->setValue($this->config->server_url);
        $server->addSubItem($url);


        $errors = new ilCheckboxInputGUI($this->plugin->txt('ignore_ssl_errors'), 'ignore_ssl_errors');
        $errors->setInfo($this->plugin->txt('ignore_ssl_errors_info'));
        $errors->setChecked($this->config->ignore_ssl_errors);
        $form->addItem($errors);

        // Object Defaults

        $header = new ilFormSectionHeaderGUI();
        $header->setTitle($this->plugin->txt('object_defaults'));
        $form->addItem($header);

        $questions = new ilCheckboxInputGUI($this->plugin->txt('include_questions'), 'include_questions');
        $questions->setInfo($this->plugin->txt('include_questions_info'));
        $questions->setChecked($this->config->include_questions);
        $form->addItem($questions);

        $random_questions = new ilSelectInputGUI($this->plugin->txt('random_questions'), 'random_questions');
        $random_questions->setOptions(array(
            ilTestArchiveCreatorPlugin::RANDOM_ALL => $this->plugin->txt('random_questions_all'),
            ilTestArchiveCreatorPlugin::RANDOM_USED => $this->plugin->txt('random_questions_used'),
        ));
        $random_questions->setValue($this->config->random_questions);
        $questions->addSubItem($random_questions);

        $qbest = new ilCheckboxInputGUI($this->plugin->txt('questions_with_best_solution'), 'questions_with_best_solution');
        $qbest->setInfo($this->plugin->txt('questions_with_best_solution_info'));
        $qbest->setChecked($this->config->questions_with_best_solution);
        $questions->addSubItem($qbest);


        $answers = new ilCheckboxInputGUI($this->plugin->txt('include_answers'), 'include_answers');
        $answers->setInfo($this->plugin->txt('include_answers_info'));
        $answers->setChecked($this->config->include_answers);
        $form->addItem($answers);

        $pass_selection = new ilSelectInputGUI($this->plugin->txt('pass_selection'), 'pass_selection');
        $pass_selection->setOptions(array(
            ilTestArchiveCreatorPlugin::PASS_SCORED => $this->plugin->txt('pass_scored'),
            ilTestArchiveCreatorPlugin::PASS_ALL => $this->plugin->txt('pass_all'),
        ));
        $pass_selection->setValue($this->config->pass_selection);
        $answers->addSubItem($pass_selection);

        $abest = new ilCheckboxInputGUI($this->plugin->txt('answers_with_best_solution'), 'answers_with_best_solution');
        $abest->setInfo($this->plugin->txt('answers_with_best_solution_info'));
        $abest->setChecked($this->config->answers_with_best_solution);
        $answers->addSubItem($abest);


        $orientation = new ilSelectInputGUI($this->plugin->txt('orientation'), 'orientation');
        $orientation->setOptions(array(
            ilTestArchiveCreatorPlugin::ORIENTATION_PORTRAIT => $this->plugin->txt('orientation_portrait'),
            ilTestArchiveCreatorPlugin::ORIENTATION_LANDSCAPE => $this->plugin->txt('orientation_landscape'),
        ));
        $orientation->setValue($this->config->orientation);
        $form->addItem($orientation);

        $zoom_factor = new ilNumberInputGUI($this->plugin->txt('zoom_factor'), 'zoom_factor');
        $zoom_factor->setSize(5);
        $zoom_factor->allowDecimals(false);
        $zoom_factor->setValue($this->config->zoom_factor * 100);
        $form->addItem($zoom_factor);

        // Privacy settings

        $header = new ilFormSectionHeaderGUI();
        $header->setTitle($this->plugin->txt('privacy_settings'));
        $form->addItem($header);

        $with_login = new ilCheckboxInputGUI($this->plugin->txt('with_login'), 'with_login');
        $with_login->setInfo($this->plugin->txt('with_login_info'));
        $with_login->setChecked($this->config->with_login);
        $form->addItem($with_login);

        $with_matriculation = new ilCheckboxInputGUI($this->plugin->txt('with_matriculation'), 'with_matriculation');
        $with_matriculation->setInfo($this->plugin->txt('with_matriculation_info'));
        $with_matriculation->setChecked($this->config->with_matriculation);
        $form->addItem($with_matriculation);

        $with_results = new ilCheckboxInputGUI($this->plugin->txt('with_results'), 'with_results');
        $with_results->setInfo($this->plugin->txt('with_results_info'));
        $with_results->setChecked($this->config->with_results);
        $form->addItem($with_results);

        $include_test_log = new ilCheckboxInputGUI($this->plugin->txt('include_test_log'), 'include_test_log');
        $include_test_log->setInfo($this->plugin->txt('include_test_log_info'));
        $include_test_log->setChecked($this->plugin->isTestLogActive() && $this->config->include_test_log);
        $include_test_log->setDisabled(!$this->plugin->isTestLogActive());
        $form->addItem($include_test_log);

        $include_examination_protocol = new ilCheckboxInputGUI($this->plugin->txt('include_examination_protocol'), 'include_examination_protocol');
        $include_examination_protocol->setInfo($this->plugin->txt('include_examination_protocol_info'));
        $include_examination_protocol->setChecked($this->plugin->isExaminationProtocolPluginActive() && $this->config->include_examination_protocol);
        $include_examination_protocol->setDisabled(!$this->plugin->isExaminationProtocolPluginActive());
        $form->addItem($include_examination_protocol);

        $header = new ilFormSectionHeaderGUI();
        $header->setTitle($this->plugin->txt('permissions'));
        $header->setInfo($this->plugin->txt('permissions_info'));
        $form->addItem($header);

        $access = new ilRadioGroupInputGUI($this->plugin->txt('allow'), 'user_allow');
        $option = new ilRadioOption($this->plugin->txt('allow_any'), ilTestArchiveCreatorConfig::ALLOW_ANY);
        $option->setInfo($this->plugin->txt('allow_any_info'));
        $access->addOption($option);
        $option = new ilRadioOption($this->plugin->txt('allow_planned'), ilTestArchiveCreatorConfig::ALLOW_PLANNED);
        $option->setInfo($this->plugin->txt('allow_planned_info'));
        $access->addOption($option);
        $option = new ilRadioOption($this->plugin->txt('allow_none'), ilTestArchiveCreatorConfig::ALLOW_NONE);
        $option->setInfo($this->plugin->txt('allow_none_info'));
        $access->addOption($option);
        $access->setValue($this->config->user_allow);
        $form->addItem($access);

        $form->addCommandButton('saveConfiguration', $this->lng->txt('save'));

        return $form;
    }
}
