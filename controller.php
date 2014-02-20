<?php 
defined('C5_EXECUTE') or die(_("Access Denied."));
/**
 * Concrete5 Package "Two Factor Authentication"
 * @author Stefan Fodor (stefan@unserialized.dk) for Hammerti.me
 */
class TwoFactorAuthenticationPackage extends Package {

	//C5 required vars
	protected $pkgHandle 			= 'two_factor_authentication';
	protected $appVersionRequired	= '5.6.2';
	protected $pkgVersion 			= '1.0';

	/**
	* C5 required functions
	*/
	public function getPackageDescription() {
		return t("Enables 2 factor authentication for Concrete5 sites");
	}

	public function getPackageName() {
		return t("Two Factor Authentication");
	}
	
	public function getPackageHandle(){
		return 'two_factor_authentication';
	}
	
	/**
	* Install the package
	*/
	public function install() {
	
		//callback to parent
		$pkg = parent::install();

		//override the login page with the custom one
		$this->takeOverSinglePage( $pkg, '/login' );
	}
	
	/**
	* Uninstall the package
	*/
	public function uninstall() {
	
		//callback to parrent
		parent::uninstall();
		
		//restore login to original format
		$this->freeSinglePage( '/login' );
	}
	
	/**
	* The proper way of overriding core single pages in C5
	* http://www.concrete5.org/community/forums/customizing_c5/override-core-single-page-within-a-package/#515919
	*/
	private function takeOverSinglePage(Package $pkg, $page_path) {
	   $db = Loader::db();   
	   $args = array(
						$pkg->getPackageID(), //package ID
						Page::getByPath($page_path)->getCollectionID() //collection ID of the page to override
	   );
	   $db->query("update Pages set pkgID = ? where cID = ?", $args );
	}
	
	/**
	* Restore a core singlepage to pkID 0
	*/
	private function freeSinglePage( $page_path ) {
	   $db = Loader::db();   
	   $args = array(
						0, //package ID
						Page::getByPath($page_path)->getCollectionID() //collection ID of the page to override
	   );
	   $db->query("update Pages set pkgID = ? where cID = ?", $args );
	}
	
}