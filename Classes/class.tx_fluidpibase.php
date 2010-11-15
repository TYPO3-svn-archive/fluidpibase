<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2010 Helge Funk <helge.funk@e-netconsulting.com>
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/


class tx_fluidpibase extends tslib_pibase {

	/**
	 * @var Tx_Extbase_Configuration_AbstractConfigurationManager
	 */
	protected static $configurationManager;

	/**
	 * The configuration for the Extbase framework
	 * @var array
	 */
	public static $extbaseFrameworkConfiguration;


	/**
	 * The configuration for the Extbase framework
	 * @return void
	 */
	public function pi_initFluid() {
		$this->initializeConfigurationManagerAndFrameworkConfiguration($this->conf);
	}


	/**
	 * Set fluid view
	 * @param string
	 *
	 * @return void
	 */
	public function pi_setFluidView($template) {
		$this->template = $template;
		$this->initView();
	}


	/**
	 * Initializes the configuration manager and the Extbase settings
	 *
	 * @param $configuration The current incoming configuration
	 * @return void
	 */
	protected function initializeConfigurationManagerAndFrameworkConfiguration($configuration) {
		if (TYPO3_MODE === 'FE') {
			self::$configurationManager = t3lib_div::makeInstance('Tx_Extbase_Configuration_FrontendConfigurationManager');
			self::$configurationManager->setContentObject($this->cObj);
		} else {
			self::$configurationManager = t3lib_div::makeInstance('Tx_Extbase_Configuration_BackendConfigurationManager');
		}
		self::$extbaseFrameworkConfiguration = self::$configurationManager->getFrameworkConfiguration($configuration);
	}


	/**
	 * @return	void
	 */
	protected function initView() {
		$requestBuilder = t3lib_div::makeInstance('Tx_Extbase_MVC_Web_RequestBuilder');
		$request = $requestBuilder->initialize(self::$extbaseFrameworkConfiguration);
		$request = $requestBuilder->build();
		$request->setControllerExtensionName($this->extKey);

		$uriBuilder = t3lib_div::makeInstance('Tx_Extbase_MVC_Web_Routing_UriBuilder');
		$uriBuilder->setRequest($request);

		$controllerContext = t3lib_div::makeInstance('Tx_Extbase_MVC_Controller_ControllerContext');
		$controllerContext->setRequest($request);
		$controllerContext->setUriBuilder($uriBuilder);

		$this->view = t3lib_div::makeInstance('Tx_Fluid_View_TemplateView');
		$this->view->setControllerContext($controllerContext);

		$templatePath = t3lib_div::getFileAbsFileName($this->template);
		$conf = Tx_Extbase_Utility_TypoScript::convertTypoScriptArrayToPlainArray($this->conf);
		$this->view->assign('settings', $conf);
		$this->view->setTemplatePathAndFilename($templatePath);
	}
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fluidpibase/Classes/FluidPiBase.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fluidpibase/Classes/FluidPiBase.php']);
}

?>