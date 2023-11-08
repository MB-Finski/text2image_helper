<?php

// SPDX-FileCopyrightText: Sami Finnilä <sami.finnila@nextcloud.com>
// SPDX-License-Identifier: AGPL-3.0-or-later

namespace OCA\Text2ImageHelper\Listener;

use OCA\Text2ImageHelper\AppInfo\Application;
use OCP\AppFramework\Services\IInitialState;
use OCP\Collaboration\Reference\RenderReferenceEvent;
use OCP\EventDispatcher\Event;
use OCP\EventDispatcher\IEventListener;
use OCP\IConfig;
use OCP\IGroupManager;
use OCP\Util;

#DEBUG: import logger
use Psr\Log\LoggerInterface;

class Text2ImageHelperReferenceListener implements IEventListener {
	public function __construct(
		private IConfig $config,
		private IInitialState $initialState,
		private ?string $userId,
		private LoggerInterface $logger,
		private IGroupManager $iGroupManager,
	) {
	}

	public function handle(Event $event): void {

		if (!$event instanceof RenderReferenceEvent) {
			return;
		}

		$pickerEnabled = $this->config->getAppValue(Application::APP_ID, 'picker_enabled', '1') === '1';

		if ($this->userId === null) {
			$isAdmin = false;
		} else {
			$isAdmin = $this->iGroupManager->isAdmin($this->userId);
		}

		$features = [
			'picker_enabled' => $pickerEnabled,
			'is_admin' => $isAdmin,
		];

		$this->initialState->provideInitialState('features', $features);
		Util::addScript(Application::APP_ID, Application::APP_ID . '-reference');
	}
}
