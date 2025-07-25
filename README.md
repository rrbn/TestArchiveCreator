# TestArchiveCreator

Copyright (c) 2017-2023 Institut fuer Lern-Innovation, Friedrich-Alexander-Universitaet Erlangen-Nuernberg, GPLv3, see LICENSE

**Further maintenance can be offered by [Databay AG](https://www.databay.de).**

Versions: see [Changelog](CHANGELOG.md). Plugin versions for different ILIAS releases are provided in separate branches of this repository.

This plugin for the LMS ILIAS open source allows the creation of zipped archives with PDF files for written tests.

The actual PDF rendering is done by a headless browser which has to be installed in the web server. Corrently the plugin supports two renderers:

* Puppeteer on the ILIAS server, see [Installation](./docs/install-puppeteer-local.md)
* Puppeteer on a separate Server, see this [Server Script](https://github.com/DatabayAG/tarc-pdf)

## Issues

Please use the official ILIAS bug tracker "Mantis" for bug reporting: https://mantis.ilias.de
* Select "ILIAS Plugins" as Project
* Filter by Category "TestArchiveCreator"

## Plugin installation

1. Put the content of the plugin directory in a subdirectory under your ILIAS main directory:
`Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/TestArchiveCreator`
2. Move to the base directory of your ILIAS and run `composer du` to reload the current plugin version
3. Open Administration > Extending ILIAS > Plugins
4. Install and activate the plugin
5. Open the plugin configuration
6. Edit the plugin configuration and enter at least the server paths of the chosen renderer.


## Usage

1. Mover to the tab "Export" in the test.
3. Click "Settings" in the toolbar to change some properties of the archive creation.
2. Click the button "Create" in the toolbar to create a zipped archive.

The archive containes separate PDF files for the questions in the test and the test runs of participants. Overviews are written as csv html files.

## Planned Creation

Archive creation may take a long time for large tests. For this reason the plugin allows to configure a planned creation of the archive in each test. This requires two additional setups.

You need to set up a call of the ILIAS cron jobs on your web server, see the ILIAS installation guide:
https://www.ilias.de/docu/goto_docu_pg_8240_367.html

Additionally, you need to install the cron job plugin TestArchiveCron:
https://github.com/DatabayAG/TestArchiveCron

1. Install and activate this plugin.
2. Go to Administration > General Settings > Cron Jobs
3. Activate the 'Test Archive Creation' job
4. Set a reasonable schedule for the job, e.h. hourly.

Now you can set a time in the settings of the archive creation. When the cron job is called the time is due, it
will create the archive.

## Handling Assets

Required asset files (styles, fonts and media) can now optionally be included in the archive. This is set in the plugin configuration.
* If assets are included, the HTML pages for questions and participants will use them locally. In this case you don't need to generate PDFs in the archive.
* If assets are not included, the html pages for questions and participants will use their original locations which are normally protected by the Web Access Checker (WAC) of ILIAS and can't be accessed when the archive is viewed later. In this case you should generate PDFs which have the assets embedded. 

*Since version 1.5.2 the assets are delivered by the plugin for the PDF generation, so the following advice is no longer neccessary:*

ILIAS does not sign all images for the WAC and the valid time of the signature may be too short for the rendering jobs of large archives. In this case the WAC tries to determine the access based on the user session. The plugin provides the session cookie, but the session based check of the WAC may take too long for the rendering timeout. A call from the TestArchiveCron plugin does not set the session cookie correctly.

To prevent these problems, the best solution is to deactivate the WAC for rendering calls. If the renderer is installed on the same server, requests coming from this server can bypass the WAC.

Edit `/etc/hosts` and add the hostname of your ILIAS installation to the localhost addresses This will keep all requests from the renderer on the same host.

    127.0.0.1       localhost www.my-ilias-host.de
    ::1             localhost ip6-localhost ip6-loopback www.my-ilias-host.de

Edit `.htaccess` in the ILIAS root directory (or the copied settings in your Apache configuration, if you don't allow overrides). Add two condition before the rewrite rule for the WAC, so that it is only active for foreign requests:

    RewriteCond %{REMOTE_ADDR} !=127.0.0.1
    RewriteCond %{REMOTE_ADDR} !=::1
	RewriteRule ^data/.*/.*/.*$ ./Services/WebAccessChecker/wac.php [L]


## Debugging of the PDF generation
If the PDF generation fails for some reason you may want to test it manually on the server to get additional debugging output.

1. Activate the ILIAS log with INFO level for the 'Root' component
2. Generate an archive with the config options 'Keep Directory' and 'Keep Jobfile'
3. Search in the ILIAS log for 'ilTestArchiveCreatorPDF::generateJobs'
4. Copy the whole logged command line
5. Open a shell on your server and move to the root folder of your ILIAS installation
6. Paste the command and run it
7. Look at the debugging output
