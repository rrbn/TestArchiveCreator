<?php

class ilTestArchiveCreatorLocal extends ilTestArchiveCreatorPDF
{
    /**
     * Generate the added batch files as PDF in one-step
     * PDF rendering is done at this step
     */
    public function generateJobs(): void
    {
        if (empty($this->jobs)) {
            return;
        }

        foreach ($this->jobs as $job) {
            try {
                $header = $job['headLeft'] ?? '';
                $footer = $job['footLeft'] ?? '';

                $options = json_encode([
                    'chromeExecutable' => $this->config->bs_chrome_path,
                    'ignoreHttpsErrors' => $this->config->ignore_ssl_errors,
                    'sourceFile' => $job['sourceFile'],
                    'targetFile' => $job['targetFile'],
                    'format' => 'A4',
                    'landscape' => $this->settings->orientation == ilTestArchiveCreatorPlugin::ORIENTATION_LANDSCAPE,
                    'headerTemplate' => '<p style="font-size:10px; padding-left:60px; margin-top:-5px;">'
                        . $header . '</p>',
                    'footerTemplate' => '<p style="font-size:10px; padding-left:60px;margin-top:5px;">'
                        . '<span class="pageNumber"></span> / <span class="totalPages"></span> - '
                        . $footer . '</p>'
                ]);

                $command_line = [
                    escapeshellcmd('PATH=$PATH:/usr/local/bin:/opt/homebrew/bin'),
                    escapeshellcmd('NODE_PATH=' . $this->config->bs_node_module_path),
                    escapeshellcmd($this->config->bs_node_path),
                    escapeshellarg(realpath(__DIR__ . '/../../js/call_puppeteer.cjs')),
                    escapeshellarg($options)
                ];

                shell_exec(implode(' ', $command_line));
            } catch (Exception $e) {
                $this->logger->error($e->getMessage());
                $this->failed_files[] = $job['targetFile'];
            }
        }
    }
}
