{**
 * templates/submission/form/step1.tpl
 *
 * Copyright (c) 2014-2018 Simon Fraser University
 * Copyright (c) 2003-2018 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * Step 1 of author monograph submission.
 *}
{if $sectionOptions|@count == 1}
	 {translate key="author.submit.notAccepting"}
{else}
	{capture assign="additionalFormContent2"}
		{if $sectionOptions|@count == 2}
			{* There is only one section; choose it invisibly *}
			{fbvElement type="hidden" id="sectionId" value=$sectionOptions|@array_keys|@array_pop}
		{else}
			{include file="submission/form/section.tpl"}
		{/if}
	{/capture}

	{include file="core:submission/form/step1.tpl"}
{/if}
