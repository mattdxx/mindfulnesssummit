<?php
/**
 * Model Factory Class
 * @author Flipper Code <hello@flippercode.com>
 * @package Core
 */

if ( ! class_exists( 'FactoryModelWPGMP' ) ) {

	/**
	 * Model Factory Class
	 * @author Flipper Code <hello@flippercode.com>
	 * @version 3.0.0
	 * @package Core
	 */
	class FactoryModelWPGMP extends AbstractFactgoryWPGMP {
		/**
		 * FactoryModel constructer.
		 */
		public function __construct() {

		}
		/**
		 * Create model object by passing object type.
		 * @param  string $objectType Object Type.
		 * @return object         Return class object.
		 */
		public function create_object($objectType) {
			switch ( $objectType ) {

				default:
					require_once( WPGMP_MODEL.$objectType.'/model.'.$objectType.'.php' );
					$object = 'WPGMP_Model_'.$objectType;

				return new $object();
				break;
			}

		}

	}
}
