<?php

// Copyright (c) 2017 Institut fuer Lern-Innovation, Friedrich-Alexander-Universitaet Erlangen-Nuernberg, GPLv3, see LICENSE

/**
 * Global Configuration for the Test Archive Creator
 */
class ilTestArchiveCreatorConfig
{
    public const ALLOW_ANY = 'any';
    public const ALLOW_PLANNED = 'planned';
    public const ALLOW_NONE = 'none';

    public const ENGINE_NONE = '';
    public const ENGINE_LOCAL = 'browsershot';
    public const ENGINE_SERVER = 'server';


    /** @var string actions allowed for a standard user with write permissions on a test */
    public string $user_allow;

    /** @var string engine to be used for pdf generation */
    public string $pdf_engine;

    /** @var float zoom factor for pdf generation */
    public float $zoom_factor;

    /** @var string  paper orientation of the generated pdf */
    public string $orientation;

    /** @var  string  selection of the test passes to include in the archive */
    public string $pass_selection;

    /** @var  string  selection of the random questions to include in the archive */
    public string $random_questions;

    /** @var  bool include the user login in the pdf */
    public bool $with_login;

    /** @var  bool include the user matriculation number in the archive */
    public bool $with_matriculation;

    /** @var  bool include an export of test results as .csv und .xslx files */
    public bool $with_results;

    /** @var  bool hide the standard test archive in the export menu */
    public bool $hide_standard_archive;

    /** @var  bool keep the creation directory on the server after delivery */
    public bool $keep_creation_directory;

    /** @var  bool keep the jobfile on the server after delivery */
    public bool $keep_jobfile;

    /** @var bool embed the asset files in the archive */
    public bool $embed_assets;

    /** @var bool ignore ssl errors at pdf generation (phantomjs and browsershot) */
    public bool $ignore_ssl_errors;

    /** @var string path to node_modules for browsershot */
    public string $bs_node_module_path;

    /** @var string path to chrome binary for browsershot  */
    public string $bs_chrome_path;

    /** @var string path to node binary for browsershot */
    public string $bs_node_path;

    /** @var string path to npm binary for browsershot */
    public string $bs_npm_path;

    /** @var bool include the test log of ilias in the archive */
    public bool $include_test_log;

    /** @var bool include the examination protocol (plugin) in the archive */
    public bool $include_examination_protocol;

    /** @var bool include separate print views of the questions in the archive */
    public bool $include_questions;

    /** @var bool include the participant's answers in the archive */
    public bool $include_answers;

    /** @var bool add the best solution to the print view of questions */
    public bool $questions_with_best_solution;

    /** @var bool add the best solution to the participant's answers */
    public bool $answers_with_best_solution;

    /** @var string url of a server for pdf generation */
    public string $server_url;


    /** @var ilTestArchiveCreatorPlugin $plugin */
    protected ilTestArchiveCreatorPlugin $plugin;

    /** @var ilSetting  */
    protected ilSetting $settings;

    /**
     * Constructor
     * Initializes the configuration values
     *
     * @param ilTestArchiveCreatorPlugin $plugin
     */
    public function __construct($plugin)
    {
        $this->plugin = $plugin;

        $this->settings = new ilSetting('ilTestArchiveCreator');

        $this->embed_assets = (bool) $this->settings->get('embed_assets', false);
        $this->user_allow = (string) $this->settings->get('user_allow', self::ALLOW_ANY);
        $this->pdf_engine = (string) $this->settings->get('pdf_engine', self::ENGINE_NONE);

        $this->hide_standard_archive = (bool) $this->settings->get('hide_standard_archive', true);
        $this->keep_creation_directory = (bool) $this->settings->get('keep_creation_directory', false);
        $this->keep_jobfile = (bool) $this->settings->get('keep_jobfile', false);
        $this->ignore_ssl_errors = (bool) $this->settings->get('ignore_ssl_errors', false);

        $this->bs_node_module_path = (string) $this->settings->get('bs_node_module_path', '/home/www-data/node_modules/');
        $this->bs_chrome_path = (string) $this->settings->get('bs_chrome_path', '/home/www-data/.cache/puppeteer/chrome/linux-1108766/chrome-linux/chrome');
        $this->bs_node_path = (string) $this->settings->get('bs_node_path', '/usr/bin/node');
        $this->bs_npm_path = (string) $this->settings->get('bs_npm_path', '/usr/bin/npm');

        $this->server_url = (string) $this->settings->get('server_url', 'http://localhost:3000');

        $this->with_login = (bool) $this->settings->get('with_login', true);
        $this->with_matriculation = (bool) $this->settings->get('with_matriculation', true);
        $this->with_results = (bool) $this->settings->get('with_results', true);
        $this->include_test_log = (bool) $this->settings->get('include_test_log', true);
        $this->include_examination_protocol = (bool) $this->settings->get('include_examination_protocol', true);

        $this->include_questions = (bool) $this->settings->get('include_questions', true);
        $this->include_answers = (bool) $this->settings->get('include_answers', true);
        $this->questions_with_best_solution = (bool) $this->settings->get('questions_with_best_solution', true);
        $this->answers_with_best_solution = (bool) $this->settings->get('answers_with_best_solution', true);

        $this->pass_selection = (string) $this->settings->get('pass_selection', ilTestArchiveCreatorPlugin::PASS_SCORED);
        $this->random_questions = (string) $this->settings->get('random_questions', ilTestArchiveCreatorPlugin::RANDOM_USED);

        $this->zoom_factor = (float) $this->settings->get('zoom_factor', '1.0');
        $this->orientation = (string) $this->settings->get('orientation', ilTestArchiveCreatorPlugin::ORIENTATION_PORTRAIT);
    }


    /**
     * Save the configuration
     */
    public function save()
    {
        $this->settings->set('user_allow', (string) $this->user_allow);
        $this->settings->set('embed_assets', (bool) $this->embed_assets ? '1' : '0');
        $this->settings->set('pdf_engine', (string) $this->pdf_engine);
        $this->settings->set('hide_standard_archive', (bool) $this->hide_standard_archive ? '1' : '0');
        $this->settings->set('keep_creation_directory', (bool) $this->keep_creation_directory ? '1' : '0');
        $this->settings->set('keep_jobfile', (bool) $this->keep_jobfile ? '1' : '0');
        $this->settings->set('ignore_ssl_errors', (bool) $this->ignore_ssl_errors ? '1' : '0');

        $this->settings->set('bs_node_module_path', (string) $this->bs_node_module_path);
        $this->settings->set('bs_chrome_path', (string) $this->bs_chrome_path);
        $this->settings->set('bs_node_path', (string) $this->bs_node_path);
        $this->settings->set('bs_npm_path', (string) $this->bs_npm_path);

        $this->settings->set('server_url', (string) $this->server_url);

        $this->settings->set('with_login', (bool) $this->with_login ? '1' : '0');
        $this->settings->set('with_matriculation', (bool) $this->with_matriculation ? '1' : '0');
        $this->settings->set('with_results', (bool) $this->with_results ? '1' : '0');
        $this->settings->set('include_test_log', (bool) $this->include_test_log ? '1' : '0');
        $this->settings->set('include_examination_protocol', (bool) $this->include_examination_protocol ? '1' : '0');

        $this->settings->set('include_questions', (bool) $this->include_questions ? '1' : '0');
        $this->settings->set('include_answers', (bool) $this->include_answers ? '1' : '0');
        $this->settings->set('questions_with_best_solution', (bool) $this->questions_with_best_solution ? '1' : '0');
        $this->settings->set('answers_with_best_solution', (bool) $this->answers_with_best_solution ? '1' : '0');

        $this->settings->set('pass_selection', (string) $this->pass_selection);
        $this->settings->set('random_questions', (string) $this->random_questions);

        $this->settings->set('zoom_factor', (string) $this->zoom_factor);
        $this->settings->set('orientation', (string) $this->orientation);
    }


    /**
     * Is the planned creation of archives allowed or the current user
     * @return bool
     */
    public function isPlannedCreationAllowed()
    {
        if ($this->plugin->hasAdminAccess()) {
            return true;
        }

        return ($this->user_allow == self::ALLOW_ANY || $this->user_allow == self::ALLOW_PLANNED);
    }

    /**
     * Is the instant creation of archives allowed or the current user
     * @return bool
     */
    public function isInstantCreationAllowed()
    {
        if ($this->plugin->hasAdminAccess()) {
            return true;
        }

        return ($this->user_allow == self::ALLOW_ANY);
    }

}
