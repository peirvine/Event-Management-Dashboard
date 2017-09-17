<?php
namespace Concrete\Package\Event\Controller\SinglePage\Dashboard;
use \Concrete\Core\Page\Controller\DashboardPageController;
use Concrete\Core\File\File;
use Loader;
use \Imagick;

class Event extends DashboardPageController {

	
	public function view() {
		$this->_db = Loader::db();	

		if (isset($_GET['delete'])){
			$v = array(intval($_GET['delete']));
			$q = 'DELETE FROM `event` WHERE event_id = ?';
			$r = $this->_db->query($q, $v);
			
			header("Location: /dashboard/event/");
			exit;
		}
		
	
		
		$query = 'SELECT * FROM `event` WHERE `event_date` >= CURDATE() ORDER BY `event_date` ASC '; 
		$r = $this->_db->query($query);	
		$this->set('r', $r);	
		$this->set('show','list');
		
	}
	
	public function edit($id){

		$this->_db = Loader::db();
		
		$this->set('editID', $id);
		
		if(!isset($_POST['edit'])){
			
			$v = array(intval($id));
			$query = 'SELECT * FROM event WHERE event_id = ? '; 
			$r = $this->_db->query($query, $v);
			
			if ($r->rowCount() != 1){
				$this->error->add('No Such Event ID');
				return;
				
			}

			$this->set('show','form');
			$data = $r->fetch();
			
			$this->set('data', $data);
		} else{
		

		
			if(!isset($_POST['event_name']) || $_POST['event_name'] == '')
				$this->error->add('Event name not set.');
			//if(!isset($_POST['family_email']) || $_POST['family_email'] == '')
			//	$this->error->add('Event email not set.');
			//if(!isset($_POST['family_website']) || $_POST['family_website'] == '')
			//	$this->error->add('Event website not set.');
			if(!isset($_POST['id']) || $_POST['id'] == '')
				$this->error->add('ID value is incorrect');
			/*
			if(!isset($_FILES['family_avatar']['tmp_name']))
				$this->error->add('Event picture not provided.');
			if(!isset($_FILES['family_pdf']['tmp_name']))
				$this->error->add('Event PDF not provided.');
			*/
		
			$message = '';

			
			//Process error
			if(isset($this->error) && $this->error->has()){		
				$this->set('show','form');
			} else {
				$allowed_fields = array('event_name','family_email','family_website','family_avatar_fid','family_pdf1_fid','family_pdf2_fid','chosen','invisible','event_date','event_description');

				$data = array();
				foreach ($_POST as $key=>$value){
					if (in_array($key, $allowed_fields)){
						$data[$key] = $value;
					}
				}

				
				$q = 'UPDATE `event` SET ';
				foreach($data as $col=>$val){
					$q .= '`'.$col.'`=?,';
					$dropins[] = $val;
				}
				$q = substr($q, 0, -1);
				$q .= ' WHERE `event_id`=?';
				$dropins[] = $_POST['id'];
				
				//$message .= '$query = ' . $q ."\n";

				
				$this->_db->Execute($q, $dropins);
				
				$updatepdf = false;
				//$page = new Imagick();
				//$page->setResolution(144,144);
			
				if (isset( $_POST['family_pdf1_fid'])){
					$f1 = File::getByID( $_POST['family_pdf1_fid']);
					$path1 = DIR_BASE.$f1->getRelativePath();
					if (isset($_GET['redo_thumbs']) || (isset($_POST['family_pdf1_fid']) && $_POST['family_pdf1_fid'] != 0 && $_POST['family_pdf1_fid'] != $_POST['old_family_pdf1_fid'])){
						$output2 = DIR_BASE.'/application/files/event/pdf_images/'. $_POST['family_pdf1_fid'] .'_1.ps';
						$output = DIR_BASE.'/application/files/event/pdf_images/'. $_POST['family_pdf1_fid'] .'_1.jpg';
						exec('pdftops -paper letter -expand '. $path1 .' '. $output2);
						exec('convert -density 300 '. $output2 .' '. $output);
						$updatepdf = true;
					}
				}
					//-define pdf:use-cropbox=true 
					//exec('gs  -o '. DIR_BASE.'/application/files/event/pdf_images/'. $_POST['family_pdf1_fid'] .'_3.jpg -sDEVICE=jpeg -r144 '. $path1);
					//$page->readimage($path1);
					//$page->trimImage(0);
					//$page->setImageFormat('jpg');
					//$page->writeImage(DIR_BASE.'/application/files/event/pdf_images/'. $_POST['family_pdf1_fid'] .'_1.jpg');
				
	
				if (isset( $_POST['family_pdf2_fid'])){
					$f2 = File::getByID( $_POST['family_pdf2_fid']);
					$path2 = DIR_BASE.$f2->getRelativePath();
					if (isset($_GET['redo_thumbs']) || (isset( $_POST['family_pdf2_fid']) &&  $_POST['family_pdf2_fid'] != 0 && $_POST['family_pdf2_fid'] != $_POST['old_family_pdf2_fid'])){
						$output2 = DIR_BASE.'/application/files/event/pdf_images/'. $_POST['family_pdf2_fid'] .'_2.ps';
						$output = DIR_BASE.'/application/files/event/pdf_images/'. $_POST['family_pdf2_fid'] .'_2.jpg';
						exec('pdftops -paper letter -expand '. $path2 .' '. $output2);
						exec('convert -density 300 '. $output2 .' '. $output);
						$updatepdf = true;
					}
				}
					//exec('gs  -o '. DIR_BASE.'/application/files/event/pdf_images/'. $_POST['family_pdf2_fid'] .'_4.jpg -sDEVICE=jpeg -r144 '. $path2);
					//$page->readimage($path2);
					//$page->trimImage(0);
					//$page->setImageFormat('jpg');
					//$page->writeImage(DIR_BASE.'/application/files/event/pdf_images/'. $_POST['family_pdf2_fid'] .'_2.jpg');
				
				
				//$page->clear();
				//$page->destroy();
				
				if ($updatepdf){
					//Now merge the pdfs into one.
					$path3 = DIR_BASE.'/application/files/event/pdf_packets/'.$event_id .'_packet.pdf';
					$command = 'pdftk '. $path1 .' '. $path2 .' cat output '. $path3 .'';
					$result = shell_exec($command);
				}

				$message = 'Event Edited Successfully.';
				
				$this->set('message', $message);
				$this->view();
			}
		}
	}
}
