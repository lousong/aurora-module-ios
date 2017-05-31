<?php
/**
 * @copyright Copyright (c) 2017, Afterlogic Corp.
 * @license AGPL-3.0 or AfterLogic Software License
 *
 * This code is licensed under AGPLv3 license or AfterLogic Software License
 * if commercial version of the product was purchased.
 * For full statements of the licenses see LICENSE-AFTERLOGIC and LICENSE-AGPL3 files.
 */

namespace Aurora\Modules\Ios;

/**
 * @package Modules
 */
class Module extends \Aurora\System\Module\AbstractModule
{
	/***** private functions *****/
	/**
	 * Initializes IOS Module.
	 * 
	 * @ignore
	 */
	public function init() 
	{
		parent::init();
		
		$this->AddEntries(array(
				'ios' => 'EntryIos',
				'profile' => 'EntryProfile'
			)
		);
	}
	/***** private functions *****/
	
	/***** public functions *****/
	/**
	 * @ignore
	 * @return string
	 */
	public function EntryIos()
	{
		$sResult = \file_get_contents($this->GetPath().'/templates/Ios.html');
		
		$oApiIntegrator = new \Aurora\Modules\Core\Managers\Integrator();
		$iUserId = \Aurora\System\Api::getAuthenticatedUserId();
		if (0 < $iUserId)
		{
			$oAccount = $oApiIntegrator->getAuthenticatedDefaultAccount();

			@\setcookie('skip_ios', '1', \time() + 3600 * 3600, '/', null, null, true);
			
			$sResult = strtr($sResult, array(
				'{{IOS/HELLO}}' => \Aurora\System\Api::ClientI18N('IOS/HELLO', $oAccount),
				'{{IOS/DESC_P1}}' => \Aurora\System\Api::ClientI18N('IOS/DESC_P1', $oAccount),
				'{{IOS/DESC_P2}}' => \Aurora\System\Api::ClientI18N('IOS/DESC_P2', $oAccount),
				'{{IOS/DESC_P3}}' => \Aurora\System\Api::ClientI18N('IOS/DESC_P3', $oAccount),
				'{{IOS/DESC_P4}}' => \Aurora\System\Api::ClientI18N('IOS/DESC_P4', $oAccount),
				'{{IOS/DESC_P5}}' => \Aurora\System\Api::ClientI18N('IOS/DESC_P5', $oAccount),
				'{{IOS/DESC_P6}}' => \Aurora\System\Api::ClientI18N('IOS/DESC_P6', $oAccount),
				'{{IOS/DESC_P7}}' => \Aurora\System\Api::ClientI18N('IOS/DESC_P7', $oAccount),
				'{{IOS/DESC_BUTTON_YES}}' => \Aurora\System\Api::ClientI18N('IOS/DESC_BUTTON_YES', $oAccount),
				'{{IOS/DESC_BUTTON_SKIP}}' => \Aurora\System\Api::ClientI18N('IOS/DESC_BUTTON_SKIP', $oAccount),
				'{{IOS/DESC_BUTTON_OPEN}}' => \Aurora\System\Api::ClientI18N('IOS/DESC_BUTTON_OPEN', $oAccount),
				'{{AppVersion}}' => AURORA_APP_VERSION,
				'{{IntegratorLinks}}' => $oApiIntegrator->buildHeadersLink()
			));
		}
		else
		{
			\Aurora\System\Api::Location('./');
		}
		
		return $sResult;
	}
	
	/**
	 * @ignore
	 */
	public function EntryProfile()
	{
		$oIosManager = new Managers\Ios();
		
		$oApiIntegrator = new \Aurora\Modules\Core\Managers\Integrator();
		$oAccount = $oApiIntegrator->getAuthenticatedDefaultAccount();
		
		$mResultProfile = $oIosManager && $oAccount ? $oIosManager->generateXMLProfile($oAccount) : false;
		
		if (!$mResultProfile)
		{
			\Aurora\System\Api::Location('./?IOS/Error');
		}
		else
		{
			\header('Content-type: application/x-apple-aspen-config; chatset=utf-8');
			\header('Content-Disposition: attachment; filename="afterlogic.mobileconfig"');
			echo $mResultProfile;
		}
	}
	/***** public functions *****/
}
