<?php
/**
 * Factory Classes
 * @author Flipper Code <hello@flippercode.com>
 * @package Core
 */

if ( ! class_exists( 'AbstractFactoryWPGMP' ) ) {

	/**
	 * Factory Class Abstract
	 * @author Flipper Code <hello@flippercode.com>
	 * @version 3.0.0
	 * @package Core
	 */
	abstract class AbstractFactgoryWPGMP {
		/**
		 * Abstrct create object
		 * @param  string $object Object Type.
		 * @return object         Return class object.
		 */
		abstract public function create_object($object);
	}
}
