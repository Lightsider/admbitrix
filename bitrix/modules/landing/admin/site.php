<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_admin_before.php');

use \Bitrix\Main\Application;
use \Bitrix\Main\Loader;
use \Bitrix\Main\Localization\Loc;
use \Bitrix\Main\SiteTable;
use \Bitrix\Main\ModuleManager;
use \Bitrix\Main\Page\Asset;
use \Bitrix\Landing\Domain;
use \Bitrix\Landing\Site;
use \Bitrix\Landing\Manager;

Loc::loadMessages(__FILE__);
Loader::includeModule('landing');
define('ADMIN_MODULE_NAME', 'landing');

// vars
$request = Application::getInstance()->getContext()->getRequest();
$server = Application::getInstance()->getContext()->getServer();
$application = Manager::getApplication();
$siteTemplate = Manager::getOption('site_template_id');
$site = $request->get('site');
$siteId = $request->get('siteId');
$landing = $request->get('id');
$cmp = $request->get('cmp');
$isFrame = $request->get('IFRAME') == 'Y';
$isAjax = $request->get('IS_AJAX') == 'Y';
$actionFolder = 'folderId';
define('SMN_SITE_ID', $site);

// refresh block repo
\Bitrix\Landing\Block::getRepository();

// check rights
if ($application->getGroupRight('landing') < 'W')
{
	$application->authForm(Loc::getMessage('ACCESS_DENIED'));
}

// detect Site Id
$type = 'SMN';
if (!$siteId)
{
	$res = Site::getList(array(
		 'select' => array(
		 	'ID'
		 ),
		 'filter' => array(
		 	'=SMN_SITE_ID' => $site,
			'=TYPE' => $type
		 )
	 ));
	if ($row = $res->fetch())
	{
		$siteId = $row['ID'];
	}
	else
	{
		if (
			$site &&
			($siteRow = SiteTable::getById($site)->fetch())
		)
		{
			// create site if not exist
			$res = Site::add(array(
				 'TITLE' => $siteRow['NAME'],
				 'SMN_SITE_ID' => $site,
				 'TYPE' => $type,
				 'DOMAIN_ID' => !Manager::isB24()
								? Domain::getCurrentId()
								: ' ',
				 'CODE' => strtolower(\randString(10))
			 ));
			if ($res->isSuccess())
			{
				$siteId = $res->getId();
			}
			else
			{
				require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_admin_after.php');
				foreach ($res->getErrors() as $error)
				{
					\showError($error->getMessage());
				}
				require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/epilog_admin.php');
				die();
			}
		}
		else
		{
			require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_admin_after.php');
			\showError(Loc::getMessage('LANDING_ADMIN_SITE_NOT_FOUND'));
			require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/epilog_admin.php');
		}

	}
}

// paths
$landingsPage = 'landing_site.php?lang=' . LANGUAGE_ID . '&site=' . $site;
$editPage = $landingsPage . '&cmp=landing_edit&id=#landing_edit#';
$editSite = $landingsPage . '&cmp=site_edit' . '&site=' . $site;
$viewPage ='landing_view.php?lang=' . LANGUAGE_ID . '&id=#landing_edit#&site=' . $site . '&template=' . $siteTemplate;

if ($isFrame)
{
	Asset::getInstance()->addCSS(
		'/bitrix/components/bitrix/landing.start/templates/.default/style.css'
	);
	Asset::getInstance()->addCSS(
		'/bitrix/components/bitrix/landing.filter/templates/.default/style.css'
	);
	Asset::getInstance()->addJS(
		'/bitrix/components/bitrix/landing.start/templates/.default/script.js'
	);
	include $server->getDocumentRoot() .
			'/bitrix/modules/landing/install/components/bitrix/landing.start/templates/.default/slider_header.php';
}
else if (!$isAjax)
{
	require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_admin_after.php');
	// css/js
	$application->showHeadStrings();
	$application->showHeadScripts();
	$application->showCSS();
}

// content area
echo '<div class="landing-content-title-admin">';

if (!$cmp)
{
	if (ModuleManager::isModuleInstalled('sale'))
	{
		$buttons = array(
			array(
				'LINK' => '#',
				'TITLE' => Loc::getMessage('LANDING_ADMIN_ACTION_ADD')
			),
			array(
				'LINK' => str_replace('#landing_edit#', 0, $editPage) . '&type=PAGE',
				'TITLE' => Loc::getMessage('LANDING_ADMIN_ACTION_ADD_PAGE')
			),
			array(
				'LINK' => str_replace('#landing_edit#', 0, $editPage) . '&type=STORE',
				'TITLE' => Loc::getMessage('LANDING_ADMIN_ACTION_ADD_STORE')
			)
		);
	}
	else
	{
		$buttons = array(
			array(
				'LINK' => str_replace('#landing_edit#', 0, $editPage) . '&type=PAGE',
				'TITLE' => Loc::getMessage('LANDING_ADMIN_ACTION_ADD_PAGE')
			)
		);
	}
	$APPLICATION->IncludeComponent(
		'bitrix:landing.filter',
		'.default',
		array(
			'FILTER_TYPE' => 'LANDING',
			'TYPE' => $type,
			'SETTING_LINK' => $editSite,
			'BUTTONS' => $buttons
		),
		false
	);
}

echo '</div>';

if ($isAjax)
{
	\Bitrix\Landing\Manager::getApplication()->restartBuffer();
}

echo '<div id="workarea-content" class="landing-content-admin">';

if ($cmp == 'landing_edit')
{
	if ($landing > 0)
	{
		$APPLICATION->IncludeComponent(
			'bitrix:landing.landing_edit',
			'.default',
			array(
				'SITE_ID' => $siteId,
				'LANDING_ID' => $landing,
				'PAGE_URL_LANDINGS' => $landingsPage,
				'PAGE_URL_LANDING_VIEW' => $viewPage
			),
			$component
		);
	}
	else
	{
		$createType = $request->get('type');
		if (!$createType)
		{
			$createType = 'PAGE';
		}
		if ($template = $request->get('tpl'))
		{
			$APPLICATION->IncludeComponent(
				'bitrix:landing.demo_preview',
				'.default',
				array(
					'TYPE' => $createType,
					'CODE' => $template,
					'SITE_ID' => $siteId,
					'PAGE_URL_BACK' => $landingsPage,
					'SITE_WORK_MODE' => 'Y'
				),
				$component
			);
		}
		else
		{
			$APPLICATION->IncludeComponent(
				'bitrix:landing.demo',
				'.default',
				array(
					'TYPE' => $createType,
					'ACTION_FOLDER' => $actionFolder,
					'SITE_ID' => $siteId,
					'PAGE_URL_SITES' => $landingsPage,
					'PAGE_URL_LANDING_VIEW' => $viewPage,
					'SITE_WORK_MODE' => 'Y'
				),
				$component
			);
		}
	}
}
elseif ($cmp == 'site_edit')
{
	$template = $request->get('tpl');
	$APPLICATION->IncludeComponent(
		'bitrix:landing.site_edit',
		'.default',
		array(
			'TYPE' => $type,
			'SITE_ID' => $siteId,
			'PAGE_URL_SITES' => '',
			'PAGE_URL_LANDING_VIEW' => $viewPage,
			'TEMPLATE' => $template
		),
		$component
	);
}
else
{
	$APPLICATION->IncludeComponent(
		'bitrix:landing.landings',
		'.default',
		array(
			'TYPE' => $type,
			'SITE_ID' => $siteId,
			'ACTION_FOLDER' => $actionFolder,
			'PAGE_URL_LANDING_EDIT' => $editPage,
			'PAGE_URL_LANDING_VIEW' => $viewPage
		),
		false
	);
}

echo '</div>';

if ($isFrame)
{
	include $server->getDocumentRoot() .
			'/bitrix/modules/landing/install/components/bitrix/landing.start/templates/.default/slider_footer.php';
}
else if (!$isAjax)
{
	require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/epilog_admin_before.php');
}

require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/epilog_admin_after.php');