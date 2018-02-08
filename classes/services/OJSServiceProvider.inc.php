<?php

/**
 * @file classes/services/OJSServiceProvider.php
 *
 * Copyright (c) 2014-2018 Simon Fraser University
 * Copyright (c) 2000-2018 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @class OJSServiceProvider
 * @ingroup services
 *
 * @brief Utility class to package all OJS services
 */

namespace OJS\Services;

use \Pimple\Container;
use \PKP\Services\AuthorService;
use \PKP\Services\UserService;
use \OJS\Services\SubmissionService;
use \OJS\Services\SectionService;
use \OJS\Services\NavigationMenuService;
use \OJS\Services\IssueService;
use \OJS\Services\GalleyService;

class OJSServiceProvider implements \Pimple\ServiceProviderInterface {

	/**
	 * Registers services
	 * @param Pimple\Container $pimple
	 */
	public function register(Container $pimple) {

		// Author service
		$pimple['author'] = function() {
			return new AuthorService();
		};

		// Submission service
		$pimple['submission'] = function() {
			return new SubmissionService();
		};

		// Issue service
		$pimple['issue'] = function() {
			return new IssueService();
		};

		// Section service
		$pimple['section'] = function() {
			return new SectionService();
		};

		// NavigationMenus service
		$pimple['navigationMenu'] = function() {
			return new NavigationMenuService();
		};

		// Galley service
		$pimple['galley'] = function() {
			return new GalleyService();
		};

		// User service
		$pimple['user'] = function() {
			return new UserService();
		};
	}
}
