<?php

/**
 * @copyright Copyright (c) Metaways Infosystems GmbH, 2011
 * @license LGPLv3, http://www.gnu.org/licenses/lgpl.html
 * @package MW
 * @subpackage DB
 */


namespace Aimeos\MW\DB\Result;


/**
 * Base class with required constants for result objects
 *
 * @package MW
 * @subpackage DB
 */
abstract class Base
{
	/**
	 * Fetch mode returning numerically indexed record arrays
	 */
	const FETCH_NUM = 0;

	/**
	 * Fetch mode returning associative indexed record arrays
	 */
	const FETCH_ASSOC = 1;
}