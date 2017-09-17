<?php 
namespace Concrete\Package\Event\Controller\SinglePage\Dashboard\Event;
use \Concrete\Core\Page\Controller\DashboardPageController;
use Concrete\Core\File\File;
use Loader;



class Add extends DashboardPageController {

	public function view() {
		$db = Loader::db();
		$st = microtime(true);
		if(isset($_POST['event_add'])){
			if(!isset($_POST['event_add']) || $_POST['event_add'] == '')
				$this->error->add('Event name not set.');
			//if(!isset($_POST['family_email']) || $_POST['family_email'] == '')
			//	$this->error->add('Event email not set.');
			//if(!isset($_POST['family_website']) || $_POST['family_website'] == '')
			//	$this->error->add('Event website not set.');
			//if(!isset($_FILES['family_avatar']['tmp_name']))
			//	$this->error->add('Event picture not provided.');
			//if(!isset($_FILES['family_pdf']['tmp_name']))
			//	$this->error->add('Event PDF not provided.');
			
			if(!$this->error->has()){
				$allowed_fields = array('event_name','family_email','family_website','family_avatar_fid','family_pdf1_fid','family_pdf2_fid','chosen','invisible','event_date','event_description');
				$data = array();
				foreach ($_POST as $key=>$value){
					if (in_array($key, $allowed_fields)){
						$data[$key] = $value;
					}
				}
				$db->insert('event',$data);
				$family_id = $db->Insert_ID();

				//Now process images:
				//Imagify first and last pages:
				

				
				$dtt = Loader::helper('form/date_time');
		
				$data = $_POST;
				$data['item_date'] = $dtt->translate('item_date');
				
				//Now merge the pdfs into one.
				$path3 = DIR_BASE.'/application/files/event/pdf_packets/'.$family_id .'_packet.pdf';
				$command = 'pdftk '. $path1 .' '. $path2 .' cat output '. $path3 .'';
				$result = shell_exec($command);
				
				$this->set('message', 'Event Added Successfully. Add Another?');
				unset($_POST);
			}else{
				$this->set('eventName',$_POST['event_name']);
				$this->set('eventDate',$_POST['event_date']);
				$this->set('eventDescription',$_POST['event_description']);
			}
		}
	}
}
