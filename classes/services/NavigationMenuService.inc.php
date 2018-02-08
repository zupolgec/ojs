<?php

/**
 * @file classes/services/NavigationMenuService.inc.php
 *
 * Copyright (c) 2014-2018 Simon Fraser University
 * Copyright (c) 2000-2018 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @class NavigationMenuService
 * @ingroup services
 *
 * @brief Helper class that encapsulates NavigationMenu business logic
 */

namespace OJS\Services;

/** types for all ojs default navigationMenuItems */
define('NMI_TYPE_SUBSCRIPTIONS', 'NMI_TYPE_SUBSCRIPTIONS');
define('NMI_TYPE_MY_SUBSCRIPTIONS', 'NMI_TYPE_MY_SUBSCRIPTIONS');
define('NMI_TYPE_CURRENT', 'NMI_TYPE_CURRENT');
define('NMI_TYPE_ARCHIVES',	'NMI_TYPE_ARCHIVES');

class NavigationMenuService extends \PKP\Services\PKPNavigationMenuService {

	/**
	 * Initialize hooks for extending PKPSubmissionService
	 */
	public function __construct() {

		\HookRegistry::register('NavigationMenus::itemTypes', array($this, 'getMenuItemTypesCallback'));
		\HookRegistry::register('NavigationMenus::displaySettings', array($this, 'getDisplayStatusCallback'));
	}

	/**
	 * Return all default navigationMenuItemTypes.
	 * @param $hookName string
	 * @param $args array of arguments passed
	 */
	public function getMenuItemTypesCallback($hookName, $args) {
		$types =& $args[0];

		\AppLocale::requireComponents(LOCALE_COMPONENT_APP_COMMON, LOCALE_COMPONENT_PKP_USER, LOCALE_COMPONENT_APP_EDITOR);

		$ojsTypes = array(
			NMI_TYPE_CURRENT => array(
				'title' => __('editor.issues.currentIssue'),
				'description' => __('manager.navigationMenus.current.description'),
			),
			NMI_TYPE_ARCHIVES => array(
				'title' => __('navigation.archives'),
				'description' => __('manager.navigationMenus.archives.description'),
			),
			NMI_TYPE_SUBSCRIPTIONS => array(
				'title' => __('navigation.subscriptions'),
				'description' => __('manager.navigationMenus.subscriptions.description'),
			),
			NMI_TYPE_MY_SUBSCRIPTIONS => array(
				'title' => __('user.subscriptions.mySubscriptions'),
				'description' => __('manager.navigationMenus.mySubscriptions.description'),
				'conditionalWarning' => __('manager.navigationMenus.loggedOut.conditionalWarning'),
			),
		);

		$types = array_merge($types, $ojsTypes);
	}

	/**
	 * Callback for display menu item functionallity
	 * @param $hookName string
	 * @param $args array of arguments passed
	 */
	function getDisplayStatusCallback($hookName, $args) {
		$navigationMenuItem =& $args[0];

		$request = \Application::getRequest();
		$dispatcher = $request->getDispatcher();
		$templateMgr = \TemplateManager::getManager(\Application::getRequest());

		$isUserLoggedIn = \Validation::isLoggedIn();
		$isUserLoggedInAs = \Validation::isLoggedInAs();
		$context = $request->getContext();

		$this->transformNavMenuItemTitle($templateMgr, $navigationMenuItem);

		$menuItemType = $navigationMenuItem->getType();

		// Conditionally hide some items
		switch ($menuItemType) {
			case NMI_TYPE_CURRENT:
			case NMI_TYPE_ARCHIVES:
				$navigationMenuItem->setIsDisplayed($context && $context->getSetting('publishingMode') != PUBLISHING_MODE_NONE);
				break;
			case NMI_TYPE_SUBSCRIPTIONS:
			case NMI_TYPE_MY_SUBSCRIPTIONS:
				$navigationMenuItem->setIsDisplayed(\Validation::isLoggedIn());
				break;
		}

		if ($navigationMenuItem->getIsDisplayed()) {

			// Set the URL
			switch ($menuItemType) {
				case NMI_TYPE_CURRENT:
					$navigationMenuItem->setUrl($dispatcher->url(
						$request,
						ROUTE_PAGE,
						null,
						'issue',
						'current',
						null
					));
					break;
				case NMI_TYPE_ARCHIVES:
					$navigationMenuItem->setUrl($dispatcher->url(
						$request,
						ROUTE_PAGE,
						null,
						'issue',
						'archive',
						null
					));
					break;
				case NMI_TYPE_SUBSCRIPTIONS:
					$navigationMenuItem->setUrl($dispatcher->url(
						$request,
						ROUTE_PAGE,
						null,
						'about',
						'subscriptions',
						null
					));
					break;
				case NMI_TYPE_MY_SUBSCRIPTIONS:
					$navigationMenuItem->setUrl($dispatcher->url(
						$request,
						ROUTE_PAGE,
						null,
						'user',
						'subscriptions',
						null
					));
					break;
			}
		}
	}
}
