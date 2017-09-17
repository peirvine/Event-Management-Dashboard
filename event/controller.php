<?php       

namespace Concrete\Package\Event;
use Package;
use SinglePage;
//use PageTheme;

defined('C5_EXECUTE') or die(_("Access Denied."));

class Controller extends Package
{

	protected $pkgHandle = 'event';
	protected $appVersionRequired = '5.7.1';
	protected $pkgVersion = '1.0.7';
	
	
	
	public function getPackageDescription()
	{
		return t("Event storage/access system");
	}

	public function getPackageName()
	{
		return t("Event");
	}
	
	public function install()
	{
		$pkg = parent::install();
		//PageTheme::add('choose_a_family', $pkg);
		SinglePage::add('/dashboard/event/', $pkg);
		SinglePage::add('/dashboard/event/add', $pkg);
	
	}
	public function update(){
		
	}
}
?>