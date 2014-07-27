<?php

/**
 * @copyright Copyright (c) Metaways Infosystems GmbH, 2014
 * @license LGPLv3, http://www.arcavias.com/en/license
 * @package Client
 * @subpackage Html
 */


/**
 * Default implementation of locale select HTML client.
 *
 * @package Client
 * @subpackage Html
 */
class Client_Html_Locale_Select_Default
	extends Client_Html_Abstract
{
	/** client/html/locale/select/default/subparts
	 * List of HTML sub-clients rendered within the locale select section
	 *
	 * The output of the frontend is composed of the code generated by the HTML
	 * clients. Each HTML client can consist of serveral (or none) sub-clients
	 * that are responsible for rendering certain sub-parts of the output. The
	 * sub-clients can contain HTML clients themselves and therefore a
	 * hierarchical tree of HTML clients is composed. Each HTML client creates
	 * the output that is placed inside the container of its parent.
	 *
	 * At first, always the HTML code generated by the parent is printed, then
	 * the HTML code of its sub-clients. The order of the HTML sub-clients
	 * determines the order of the output of these sub-clients inside the parent
	 * container. If the configured list of clients is
	 *
	 *  array( "subclient1", "subclient2" )
	 *
	 * you can easily change the order of the output by reordering the subparts:
	 *
	 *  client/html/<clients>/subparts = array( "subclient1", "subclient2" )
	 *
	 * You can also remove one or more parts if they shouldn't be rendered:
	 *
	 *  client/html/<clients>/subparts = array( "subclient1" )
	 *
	 * As the clients only generates structural HTML, the layout defined via CSS
	 * should support adding, removing or reordering content by a fluid like
	 * design.
	 *
	 * @param array List of sub-client names
	 * @since 2014.09
	 * @category Developer
	 */
	private $_subPartPath = 'client/html/locale/select/default/subparts';
	private $_subPartNames = array( 'language', 'currency' );
	private $_cache;


	/**
	 * Returns the HTML code for insertion into the body.
	 *
	 * @param string $uid Unique identifier for the output if the content is placed more than once on the same page
	 * @param array &$tags Result array for the list of tags that are associated to the output
	 * @param string|null &$expire Result variable for the expiration date of the output (null for no expiry)
	 * @return string HTML code
	 */
	public function getBody( $uid = '', array &$tags = array(), &$expire = null )
	{
		$context = $this->_getContext();
		$view = $this->getView();

		try
		{
			$view = $this->_setViewParams( $view, $tags, $expire );

			$html = '';
			foreach( $this->_getSubClients() as $subclient ) {
				$html .= $subclient->setView( $view )->getBody( $uid, $tags, $expire );
			}
			$view->selectBody = $html;
		}
		catch( Client_Html_Exception $e )
		{
			$error = array( $this->_getContext()->getI18n()->dt( 'client/html', $e->getMessage() ) );
			$view->selectErrorList = $view->get( 'selectErrorList', array() ) + $error;
		}
		catch( Controller_Frontend_Exception $e )
		{
			$error = array( $this->_getContext()->getI18n()->dt( 'controller/frontend', $e->getMessage() ) );
			$view->selectErrorList = $view->get( 'selectErrorList', array() ) + $error;
		}
		catch( MShop_Exception $e )
		{
			$error = array( $this->_getContext()->getI18n()->dt( 'mshop', $e->getMessage() ) );
			$view->selectErrorList = $view->get( 'selectErrorList', array() ) + $error;
		}
		catch( Exception $e )
		{
			$context->getLogger()->log( $e->getMessage() . PHP_EOL . $e->getTraceAsString() );

			$error = array( $context->getI18n()->dt( 'client/html', 'A non-recoverable error occured' ) );
			$view->selectErrorList = $view->get( 'selectErrorList', array() ) + $error;
		}

		/** client/html/locale/select/default/template-body
		 * Relative path to the HTML body template of the locale select client.
		 *
		 * The template file contains the HTML code and processing instructions
		 * to generate the result shown in the body of the frontend. The
		 * configuration string is the path to the template file relative
		 * to the layouts directory (usually in client/html/layouts).
		 *
		 * You can overwrite the template file configuration in extensions and
		 * provide alternative templates. These alternative templates should be
		 * named like the default one but with the string "default" replaced by
		 * an unique name. You may use the name of your project for this. If
		 * you've implemented an alternative client class as well, "default"
		 * should be replaced by the name of the new class.
		 *
		 * @param string Relative path to the template creating code for the HTML page body
		 * @since 2014.09
		 * @category Developer
		 * @see client/html/locale/select/default/template-header
		 */
		$tplconf = 'client/html/locale/select/default/template-body';
		$default = 'locale/select/body-default.html';

		return $view->render( $this->_getTemplate( $tplconf, $default ) );
	}


	/**
	 * Returns the HTML string for insertion into the header.
	 *
	 * @param string $uid Unique identifier for the output if the content is placed more than once on the same page
	 * @param array &$tags Result array for the list of tags that are associated to the output
	 * @param string|null &$expire Result variable for the expiration date of the output (null for no expiry)
	 * @return string String including HTML tags for the header
	 */
	public function getHeader( $uid = '', array &$tags = array(), &$expire = null )
	{
		try
		{
			$view = $this->_setViewParams( $this->getView(), $tags, $expire );

			$html = '';
			foreach( $this->_getSubClients() as $subclient ) {
				$html .= $subclient->setView( $view )->getHeader( $uid, $tags, $expire );
			}
			$view->selectHeader = $html;

			/** client/html/locale/select/default/template-header
			 * Relative path to the HTML header template of the locale select client.
			 *
			 * The template file contains the HTML code and processing instructions
			 * to generate the HTML code that is inserted into the HTML page header
			 * of the rendered page in the frontend. The configuration string is the
			 * path to the template file relative to the layouts directory (usually
			 * in client/html/layouts).
			 *
			 * You can overwrite the template file configuration in extensions and
			 * provide alternative templates. These alternative templates should be
			 * named like the default one but with the string "default" replaced by
			 * an unique name. You may use the name of your project for this. If
			 * you've implemented an alternative client class as well, "default"
			 * should be replaced by the name of the new class.
			 *
			 * @param string Relative path to the template creating code for the HTML page head
			 * @since 2014.09
			 * @category Developer
			 * @see client/html/locale/select/default/template-body
			 */
			$tplconf = 'client/html/locale/select/default/template-header';
			$default = 'locale/select/header-default.html';

			return $view->render( $this->_getTemplate( $tplconf, $default ) );
		}
		catch( Exception $e )
		{
			$this->_getContext()->getLogger()->log( $e->getMessage() . PHP_EOL . $e->getTraceAsString() );
		}
	}


	/**
	 * Returns the sub-client given by its name.
	 *
	 * @param string $type Name of the client type
	 * @param string|null $name Name of the sub-client (Default if null)
	 * @return Client_Html_Interface Sub-client object
	 */
	public function getSubClient( $type, $name = null )
	{
		return $this->_createSubClient( 'locale/select/' . $type, $name );
	}


	/**
	 * Processes the input, e.g. store given values.
	 * A view must be available and this method doesn't generate any output
	 * besides setting view variables.
	 */
	public function process()
	{
		$view = $this->getView();
		$session = $this->_getContext()->getSession();

		if( ( $languageId = $view->param( 'loc-languageid' ) ) !== null ) {
			$session->set( 'arcavias/locale/languageid', $languageId );
		}

		if( ( $currencyId = $view->param( 'loc-currencyid' ) ) !== null ) {
			$session->set( 'arcavias/locale/currencyId', $currencyId );
		}

		parent::process();
	}


	/**
	 * Returns the list of sub-client names configured for the client.
	 *
	 * @return array List of HTML client names
	 */
	protected function _getSubClientNames()
	{
		return $this->_getContext()->getConfig()->get( $this->_subPartPath, $this->_subPartNames );
	}


	/**
	 * Sets the necessary parameter values in the view.
	 *
	 * @param MW_View_Interface $view The view object which generates the HTML output
	 * @param array &$tags Result array for the list of tags that are associated to the output
	 * @param string|null &$expire Result variable for the expiration date of the output (null for no expiry)
	 * @return MW_View_Interface Modified view object
	 */
	protected function _setViewParams( MW_View_Interface $view, array &$tags = array(), &$expire = null )
	{
		if( !isset( $this->_cache ) )
		{
			$map = array();
			$context = $this->_getContext();
			$session = $context->getSession();
			$locale = $context->getLocale();

			$manager = MShop_Factory::createManager( $context, 'locale' );

			$search = $manager->createSearch( true );
			$search->setSortations( array( $search->sort( '-', 'locale.position' ) ) );

			foreach( $manager->searchItems( $search ) as $item ) {
				$map[ $item->getLanguageId() ][ $item->getCurrencyId() ] = $item;
			}

			$view->selectItems = $map;
			$view->selectLanguageId = $locale->getLanguageId();
			$view->selectCurrencyId = $locale->getCurrencyId();
			$view->selectParams = $this->_getClientParams( $view->param(), array( 'f', 'l', 'd' ) );

			$this->_cache = $view;
		}

		return $this->_cache;
	}
}