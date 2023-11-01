<?php
// SPDX-FileCopyrightText: Sami Finnilä <sami.finnila@nextcloud.com>
// SPDX-License-Identifier: AGPL-3.0-or-later

namespace OCA\Text2ImageHelper\Command;

use Exception;
use OCA\Text2ImageHelper\AppInfo\Application;
use OCA\Text2ImageHelper\Service\CleanUpService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use OCP\IConfig;


class CleanupImageGenerations extends Command
{

	public function __construct(
		private CleanUpService $cleanUpService,
		private IConfig $config
	) {
		parent::__construct();
	}

	protected function configure()
	{
		$maxIdleTimeSetting = intval($this->config->getUserValue(
			Application::APP_ID,
			'max_generation_idle_time',
			strval(Application::DEFAULT_MAX_GENERATION_IDLE_TIME)
		) ?: Application::DEFAULT_MAX_GENERATION_IDLE_TIME);
		$this->setName('text2image_helper:cleanup')
			->setDescription('Cleanup image generation data')
			->addArgument(
				'max_age',
				InputArgument::OPTIONAL,
				'The max idle time (in seconds)',
				$maxIdleTimeSetting
			);
	}

	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$maxAge = intval($input->getArgument('max_age'));

		if ($maxAge < 1) {
			$output->writeln('Invalid value for max_age: ' . $maxAge);
			return 1;
		}

		$output->writeln('Cleanning up image generation data older than ' . $maxAge . ' seconds.');
		try {
			$cleanedUp = $this->cleanUpService->cleanupGenerationsAndFiles($maxAge);
		} catch (Exception $e) {
			$output->writeln('Error: ' . $e->getMessage());
			return 1;
		}
		
		$output->writeln('Deleted ' . $cleanedUp['deleted_generations'] .
			' idle generations and ' . $cleanedUp['deleted_files'] . ' files.');
		if ($cleanedUp['file_deletion_errors']) {
			$output->writeln('Deletion of ' . $cleanedUp['file_deletion_errors'] . ' generations failed.');
			return 1;
		}
		return 0;
	}
}