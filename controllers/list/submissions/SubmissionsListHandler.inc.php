<?php
/**
 * @file controllers/list/submissions/SubmissionsListHandler.inc.php
 *
 * Copyright (c) 2014-2018 Simon Fraser University
 * Copyright (c) 2000-2018 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @class SubmissionsListHandler
 * @ingroup controllers_list
 *
 * @brief Instantiates and manages a UI component to list submissions.
 */
import('lib.pkp.controllers.list.submissions.PKPSubmissionsListHandler');
import('lib.pkp.classes.db.DBResultRange');
import('lib.pkp.classes.submission.Submission');

class SubmissionsListHandler extends PKPSubmissionsListHandler {

	/**
	 * @copydoc PKPSubmissionsListHandler::getConfig()
	 */
	public function getConfig() {
		$config = parent::getConfig();

		$request = Application::getRequest();
		if ($request->getContext()) {
			if (!isset($config['filters'])) {
				$config['filters'] = array();
			}
			$config['filters']['sectionIds'] = array(
				'heading' => __('section.sections'),
				'filters' => $this->getSectionFilters(),
			);
		}

		return $config;
	}

	/**
	 * @copydoc PKPSubmissionsListHandler::getWorkflowStages()
	 */
	public function getWorkflowStages() {
		return array(
			array(
				'param' => 'stageIds',
				'val' => WORKFLOW_STAGE_ID_SUBMISSION,
				'title' => __('manager.publication.submissionStage'),
			),
			array(
				'param' => 'stageIds',
				'val' => WORKFLOW_STAGE_ID_EXTERNAL_REVIEW,
				'title' => __('manager.publication.reviewStage'),
			),
			array(
				'param' => 'stageIds',
				'val' => WORKFLOW_STAGE_ID_EDITING,
				'title' => __('submission.copyediting'),
			),
			array(
				'param' => 'stageIds',
				'val' => WORKFLOW_STAGE_ID_PRODUCTION,
				'title' => __('manager.publication.productionStage'),
			),
		);
	}

	/**
	 * Compile the sections for passing as filters
	 *
	 * @return array
	 */
	public function getSectionFilters() {
		$request = Application::getRequest();
		$context = $request->getContext();

		if (!$context) {
			return array();
		}

		import('classes.core.ServicesContainer');
		$sections = ServicesContainer::instance()
				->get('section')
				->getSectionList($context->getId());

		return array_map(function($section) {
			return array(
				'param' => 'sectionIds',
				'val' => $section['id'],
				'title' => $section['title'],
			);
		}, $sections);
	}
}
