<?php
/**
 * Controller Factory Class
 * @author Flipper Code <hello@flippercode.com>
 * @package Core
 */

if ( ! class_exists( 'FactoryControllerWPGMP' ) ) {

	/**
	 * Controller Factory Class
	 * @author Flipper Code <hello@flippercode.com>
	 * @version 3.0.0
	 * @package Core
	 */
	class FactoryControllerWPGMP extends AbstractFactgoryWPGMP {
		/**
		 * FactoryController constructer.
		 */
		public function __construct() {
		}
		/**
		 * Create controller object by passing object type.
		 * @param  string $objectType Object Type.
		 * @return object         Return class object.
		 */
		public function create_object($objectType) {

			switch ( $objectType ) {

				default : if ( file_exists( WPGMP_CORE_CONTROLLER_CLASS ) ) {
						  require_once( WPGMP_CORE_CONTROLLER_CLASS ); }
				if ( class_exists( 'WPGMP_Core_Controller' ) ) {
					return new WPGMP_Core_Controller( $objectType ); }
						  break;

			}

		}

	}
}
