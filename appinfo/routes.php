<?php
// SPDX-FileCopyrightText: Sami Finnilä <sami.finnila@nextcloud.com>
// SPDX-License-Identifier: AGPL-3.0-or-later

/**
 * Create your routes in here. The name is the lowercase name of the controller
 * without the controller part, the stuff after the hash is the method.
 * e.g. page#index -> OCA\GptFreePrompt\Controller\PageController->index()
 *
 * The controller class has to be registered in the application.php file since
 * it's instantiated in there
 */
return [
	'routes' => [
		# Prosess a prompt (prompt is suppilied as a get param)
		['name' => 'Text2ImageHelper#processPrompt', 'url' => '/process_prompt', 'verb' => 'POST'],			
		['name' => 'Text2ImageHelper#getPromptHistory', 'url' => '/prompt_history', 'verb' => 'GET'],
		['name' => 'Text2ImageHelper#getImage', 'url' => '/i/{imageId}', 'verb' => 'GET'],
	],
];
