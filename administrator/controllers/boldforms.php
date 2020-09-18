<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Dnabold
 * @author     Trey Richards <treyj45@gmail.com>
 * @copyright  2020 Trey Richards
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.controlleradmin');

use \Joomla\Utilities\ArrayHelper;
use \Joomla\CMS\Session\session;
use \Joomla\CMS\Factory;
use \Joomla\CMS\Language\Text;
/**
 * Dnabolds list controller class.
 *
 * @since  1.6
 */
class DnaboldControllerBoldForms extends \Joomla\CMS\MVC\Controller\AdminController
{

	public function submit($key = null, $urlVar = null)
	{
		$this->checkToken();
		
		$app   = JFactory::getApplication();
		$model = $this->getModel('boldforms');

		
		$form = $model->getForm($data, false);
		if (!$form)
		{
			$app->enqueueMessage($model->getError(), 'error');
			return false;
		}
		
		// name of array 'jform' must match 'control' => 'jform' line in the model code
		$data  = $this->input->post->get('jform', array(), 'array');
		
		// This is validate() from the FormModel class, not the Form class
		// FormModel::validate() calls both Form::filter() and Form::validate() methods
		$validData = $model->validate($form, $data);

		if ($validData === false)
		{
			$errors = $model->getErrors();

			foreach ($errors as $error)
			{
				if ($error instanceof \Exception)
				{
					$app->enqueueMessage($error->getMessage(), 'error');
				}
				else
				{
					$app->enqueueMessage($error, 'warning');
				}
			}

			// Save the form data in the session, using a unique identifier
			$app->setUserState('com_dnabolds.sample', $data);
			$this->setRedirect(JRoute::_('index.php?option=com_dnabold&view=boldforms', false));
			
		}

	try  {
		///Begin process to grab photos 
		$id_array= array_map('trim', explode("\n", $data["ids"]));

		$main_xls_array = array();

		$photo_array = array();

		foreach ($id_array as $key => $full_ID) {

			//see if iNat or MO, then split prefix and numbers into array fields
			$pattern = '/(?=\d)/';
			$array = preg_split($pattern, $full_ID, 2);
			//put into photo array
			$photo_array[$key] =$array;
			//refactor make associative array
			$service_name = $array[0];
			$observation_id = $array[1];
			
			//get photo url
			$photo_data = $this->getPhotos($observation_id,$service_name);
			if (!$photo_data){	
				throw new Exception("Record ". $full_ID . " failed. Make sure record exsists on $service_name");			
			}
			//populate image url in array
			$photo_array[$key][] = (strtolower($service_name) == "inat") ? $photo_data["Image File"] : $photo_array[$key][] = "https://mushroomobserver.org/images/1280/".$photo_data["Image File"];


			//change Image File name to observation ID for the export sheet
			$photo_data["Image File"] = $full_ID.".jpg";
			
			//add photo for excel creation array
			$main_xls_array[] = $photo_data; 
		}
		
		
		$this->createFileZip($photo_array, $main_xls_array);

		// Clear the form data in the session
		// $app->setUserState('com_dnabolds.sample', null);	
		}
	catch (Exception $e) {
		 $app->enqueueMessage($e->getMessage(), 'error');
		$app->setUserState('com_dnabolds.sample', $data);
		$this->setRedirect(JRoute::_('index.php?option=com_dnabold&view=boldforms', false));
	}

		// Redirect back to the form in all cases
		//$this->setRedirect(JRoute::_('index.php?option=com_dnabold&view=boldforms', false));
	}

	private function getPhotos($observation_id, $service_name = false){
		$service_name_lower = strtolower($service_name);
		if($service_name_lower == "inat"){
			$get_data = $this->callAPI('GET', 'https://api.inaturalist.org/v1/observations/'.$observation_id, false);
		}
		elseif ($service_name_lower == "mo") {
			$get_data = $this->callAPI('GET', 'https://mushroomobserver.org/api/observations?id='.$observation_id.'&detail=high', false);
		}
		else {return false;}	

		if(!$get_data){
			throw new Exception("cUrl API Call to $service_name failed");	
		}
		if($service_name_lower == "mo"){
			$xml = simplexml_load_string($get_data, "SimpleXMLElement", LIBXML_NOCDATA);
			$get_data = json_encode($xml);
		}

		$response = json_decode($get_data,true);
		$download_photos = array();

		if($service_name_lower == "inat"){
			//break into array by comma, this seperates name from liscense,,, Refactor!!!
			$attribution = explode(",",$response["results"][0]["photos"][0]["attribution"]);
			//if response is empty
			if($attribution[0] == ''){
				return false;
			}

			$license_holder = str_replace("(c) ", "",$attribution[0]);
			//excel data

			//grabs photo url and ads to array
			$photo_group = $response["results"][0]["photos"][0];
			$download_photos[]= $photo_group['url'];


			//makes it large imnage
			$download_photos = str_replace("square", "large", $download_photos);


				//All required fields
			// image_file
				//strip parameter from iNat photo url
			$image_file = strtok($download_photos[0], '?');
			$original_specimen = "yes";
			$view_metadata = "sporocarp";
			$sample_id = "iNat".$observation_id;
		 	$license = trim($attribution[1]);
			$license_year= $response["results"][0]["observed_on_details"]["year"];
			//pulled from attribution array and removes (c)
			$license_holder = str_replace("(c) ", "",$attribution[0]);
			$license_contact ="https://www.inaturalist.org/people/".$response["results"][0]["user"]["login"];
		}
		elseif ($service_name_lower == "mo") {

			//if response is empty
			if(!$response["results"]["result"]){
				return false;
			}

			//get primary image id
			$image_id = $response["results"]["result"]["primary_image"]["@attributes"]["id"];

			//get high rez
			$photo_url = "https://mushroomobserver.org/images/1280/".$image_id.".jpg";

			//All required fields
			// image_file
			$image_file = $image_id.".jpg";
			$original_specimen = "yes";
			$view_metadata = "sporocarp";
			$sample_id = "MO".$observation_id;
		 	$license = $response["results"]["result"]["primary_image"]["license"]["name"];
			$license_year= substr($response["results"]["result"]["primary_image"]["created_at"],0,4);
			$license_holder = $response["results"]["result"]["primary_image"]["copyright_holder"];
			$license_contact =$response["results"]["result"]["primary_image"]["owner"]["@attributes"]["url"];
		}

			//make array for excel export
		$xls_record = array(
			"Image File" => $image_file, 
			"Original Specimen" => $original_specimen, 
			"View Metadata" => $view_metadata,
			"Caption" => "",
			"Measurement" => "",
			"Measurement Type" => "",
			"Sample Id" => $sample_id,
			"Process Id" => "",
			"License Holder" => $license_holder,
			"License" => $license,
			"License Year" => $license_year,
			"License Institution" => "",
			"License Contact" => $license_contact,
			"Photographer" => $license_holder
		);

		return $xls_record;

	}
	public function CallAPI($method, $url, $data = false)
	{
	    $curl = curl_init();

	    switch ($method)
	    {
	        case "POST":
	            curl_setopt($curl, CURLOPT_POST, 1);

	            if ($data)
	                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
	            break;
	        case "PUT":
	            curl_setopt($curl, CURLOPT_PUT, 1);
	            break;
	        default:
	            if ($data)
	                $url = sprintf("%s?%s", $url, http_build_query($data));
	    }

	    curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
	    curl_setopt($curl, CURLOPT_URL, $url);
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

	    $result = curl_exec($curl);

	    curl_close($curl);

	    return $result;
	}

	

	private function createFileZip($photo_array = false, $main_xls_array = false){
		//create new zip object
		$zip = new ZipArchive();

		//create tmp and open
		$tmp_file = tempnam('.', '');
		$zip->open($tmp_file, ZipArchive::CREATE);

		//loop through each photo
		foreach ($photo_array as $file) {
			
			//checks if any data is empty
			if (!$file[0] || !$file[1] || !$file[2]){
				throw new Exception("Zip failed. Check validity of data");
			}
			//download file from url
			$download_file = file_get_contents($file[2]);
			//add to zip
			//this is just re adding the split array together.... refactor
			$zip->addFromString($file[0].$file[1].'.jpg', $download_file);

		}

		$tmp_xls = $this->createImageXLS($main_xls_array);

        $zip->addFile($tmp_xls);

		$zip->close();

		//send file to browser to download
		header('Content-disposition: attachment; filename="boldimages.zip"');
		header('Content-type: application/zip');
		readfile($tmp_file);
		unlink($tmp_file);
		unlink($tmp_xls);
		exit();
	}


	private function createImageXLS($export_data,$tmp_xls = "export_data.xls"){

		if ($export_data) {
			function filterData(&$str) {
				$str = preg_replace("/\t/", "\\t", $str);
				$str = preg_replace("/\r?\n/", "\\n", $str);
				if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
			}

			$flag = false;
			$xls_data ="";
			foreach($export_data as $row) {
				if(!$flag) {
					// display column names as first row
					$xls_data= implode("\t", array_keys($row)) . "\n";
					$flag = true;
				}
				// filter data
				array_walk($row, 'filterData');
				$xls_data .= implode("\t", array_values($row)) . "\n";
			}
			$success = file_put_contents($tmp_xls, $xls_data);
			if (!$success){
				throw new Exception("XLS file creation failed. Check validity of data");
			}
			return $tmp_xls;		
		}
	}













































	/**
	 * Method to clone existing Dnabolds
	 *
	 * @return void
     *
     * @throws Exception
	 */
	public function duplicate()
	{
		// Check for request forgeries
		session::checkToken() or jexit(Text::_('JINVALID_TOKEN'));

		// Get id(s)
		$pks = $this->input->post->get('cid', array(), 'array');

		try
		{
			if (empty($pks))
			{
				throw new Exception(Text::_('COM_DNABOLD_NO_ELEMENT_SELECTED'));
			}

			ArrayHelper::toInteger($pks);
			$model = $this->getModel();
			$model->duplicate($pks);
			$this->setMessage(Text::_('COM_DNABOLD_ITEMS_SUCCESS_DUPLICATED'));
		}
		catch (Exception $e)
		{
			Factory::getApplication()->enqueueMessage($e->getMessage(), 'warning');
		}

		$this->setRedirect('index.php?option=com_dnabold&view=dnabolds');
	}

	/**
	 * Proxy for getModel.
	 *
	 * @param   string  $name    Optional. Model name
	 * @param   string  $prefix  Optional. Class prefix
	 * @param   array   $config  Optional. Configuration array for model
	 *
	 * @return  object	The Model
	 *
	 * @since    1.6
	 */
	public function getModel($name = 'dnabold', $prefix = 'DnaboldModel', $config = array())
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));

		return $model;
	}

	/**
	 * Method to save the submitted ordering values for records via AJAX.
	 *
	 * @return  void
	 *
	 * @since   3.0
     *
     * @throws Exception
     */
	public function saveOrderAjax()
	{
		// Get the input
		$input = Factory::getApplication()->input;
		$pks   = $input->post->get('cid', array(), 'array');
		$order = $input->post->get('order', array(), 'array');

		// Sanitize the input
		ArrayHelper::toInteger($pks);
		ArrayHelper::toInteger($order);

		// Get the model
		$model = $this->getModel();

		// Save the ordering
		$return = $model->saveorder($pks, $order);

		if ($return)
		{
			echo "1";
		}

		// Close the application
		Factory::getApplication()->close();
	}
}
