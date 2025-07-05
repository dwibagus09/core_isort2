<?php

require_once('defaultClass.php');
require_once('dbClass.php');

class imageClass extends defaultClass
{
	function __construct()
	{
		parent::__construct();
	}

	//This function inserts an image into the database. Note that it is the caller's
	//responsibility to correctly code the image_source_id, source_id, image_type_id and image_class_id
	//and to ensure that the filename exists
	function insert_image($siteid, $image_source_id,$source_id,$filename,$image_type_id,$image_class_id, $smugmugInfo = array())
	{
		/*$startmem = memory_get_usage();

		//Get the image from the file, we don't care what it is
		$image = file_get_contents($filename);
		
		$mem = memory_get_usage();

		if ($image)
		{
			$imagesTable = new images(array('db' => 'db')); //use db object from registry
			
			//See if this is an insert or an update
			if($image_id=$this->getImageIdbySourceSystemId($image_source_id,$source_id))
			{
				$data = array(
					'site_id'			=> $siteid,
					'image_source_id'	=> $image_source_id,
					'source_id'			=> $source_id,
					'image_type_id'		=> $image_type_id,
					'image_class_id'	=> $image_class_id,
					'image'      		=> '' //$image
				);
				if(!empty($smugmugInfo['id'])) $data['smugmug_id'] = $smugmugInfo['id'];
				if(!empty($smugmugInfo['Key'])) $data['smugmug_key'] = $smugmugInfo['Key'];
				
				$where = $imagesTable->getAdapter()->quoteInto('image_id = ?', $image_id);
				$imagesTable->update($data, $where);
			}
			else
			{
				$data = array(
					'site_id'			=> $siteid,
					'image_source_id'	=> $image_source_id,
					'source_id'			=> $source_id,
					'image_type_id'		=> $image_type_id,
					'image_class_id'	=> $image_class_id,
					'image'      		=> '' //$image
				);
				if(!empty($smugmugInfo['id'])) $data['smugmug_id'] = $smugmugInfo['id'];
				if(!empty($smugmugInfo['Key'])) $data['smugmug_key'] = $smugmugInfo['Key'];
				
				$imagesTable->insert($data);
			}
		}
		else
		{
			if ($this->ctl->logger) $this->ctl->logger->err('Unable to read image file: "'.$filename.'" The image table record was not created.');
			return false;
		}
		$mem = memory_get_usage();*/
		
		
		$imagesTable = new images(array('db' => 'db')); //use db object from registry
			
		//See if this is an insert or an update
		if($image_id=$this->getImageIdbySourceSystemId($image_source_id,$source_id))
		{
			$data = array(
				'site_id'			=> $siteid,
				'image_source_id'	=> $image_source_id,
				'source_id'			=> $source_id,
				'image_type_id'		=> $image_type_id,
				'image_class_id'	=> $image_class_id,
				'image'      		=> '' 
			);
			if(!empty($smugmugInfo['id'])) $data['smugmug_id'] = $smugmugInfo['id'];
			if(!empty($smugmugInfo['Key'])) $data['smugmug_key'] = $smugmugInfo['Key'];
			if(!empty($smugmugInfo['video_tag'])) $data['video_tag'] = $smugmugInfo['video_tag'];
			
			$where = $imagesTable->getAdapter()->quoteInto('image_id = ?', $image_id);
			$imagesTable->update($data, $where);
		}
		else
		{
			$data = array(
				'site_id'			=> $siteid,
				'image_source_id'	=> $image_source_id,
				'source_id'			=> $source_id,
				'image_type_id'		=> $image_type_id,
				'image_class_id'	=> $image_class_id,
				'image'      		=> '' 
			);
			if(!empty($smugmugInfo['id'])) $data['smugmug_id'] = $smugmugInfo['id'];
			if(!empty($smugmugInfo['Key'])) $data['smugmug_key'] = $smugmugInfo['Key'];
			if(!empty($smugmugInfo['video_tag'])) $data['video_tag'] = $smugmugInfo['video_tag'];
			
			$imagesTable->insert($data);
		}

		return true;
	}
	
	//Not site specific
	function getImageIdbySourceSystemId($image_source_id,$source_id)
	{
		$imagesTable = new images(array('db' => 'db')); //use db object from registry
		$select = $imagesTable->select()
				->where('source_id = ?', $source_id)
				->where('image_source_id = ?', $image_source_id);
		$result = $this->db->fetchRow($select);

		
		return $result['image_id'];
	}
	
	//This function returns a valid URL for any image in the image table
	//If the image has not been cached it calls other functions to create
	//and cache the image at the specified size. Optionally
	//it will delete and recreate an existing image
	function getImageURL($siteid, $image_id,$image_size_name,$predelete=false)
	{

		//Get the URL
		$imageURL = $this->makeImageURL($siteid, $image_id,$image_size_name,false);
		
		//Build and place the file in the cache
		switch($this->getImageFileType($image_id))
		{
			case 'jpg':
				$image_size_name_array = array($image_size_name);
				$this->makeFilesForJPGImage($siteid, $image_id,$image_size_name_array,$predelete);
				$success = true;
				break;
			case 'flv':
				$success = $this->makeFilesForMediaImage($siteid, $image_id,$predelete);
				break;
			case 'mp3':
				$success = $this->makeFilesForLocalImage($siteid, $image_id,$predelete);
				break;
			default:
				$success = false;
		}

		if ($success)
		{
			return $imageURL;
		}
		else {
			return null;
		}
	}
	
	function makeImageURL($siteid, $image_id,$image_size_name=null,$full=true)
	{
		switch($this->getImageService($image_id))
		{
			case 'Local':
				$imageURL = (($full)?$this->config->paths->html:'') . '/images/image_cache/' .$siteid.'_'.$image_id.'_'.$image_size_name.'.'.$this->getImageFileType($image_id);
				break;
			case 'EdgeCast':
				$imageURL = 'ftps://john.reed@advpubtech.com:12341234@ftp.edgecastcdn.net/marietta/'.$siteid.'_'.$image_id.'.'.$this->getImageFileType($image_id);
				break;
			default:
				$imageURL = null;
		}

		return $imageURL;

	}
	
	//Not site specific
	function getImageService($image_id)
	{
		$select = $this->db->select()->from('vw_image_class_service', array('image_service'))
					->where('image_id = ?', $image_id);
		
		$result = $this->db->fetchRow($select);

		if (count($result))
		{
			return $result['image_service'];
		}
		return null;
	}
	
	//Not site specific
	function getImageFileType($image_id)
	{
		$select = $this->db->select()->from('vw_image_class_service', array('file_type'))
		->where('image_id = ?', $image_id);
		$result = $this->db->fetchAll($select);

		if (count($result))
		{
			return $result[0]['file_type'];
		}

		return null;
	}
	
	//This function creates the required JPG files to be diplayed at various
	//sizes. It can optionally delete all the files in the disk cache in case
	//of an upload
	function makeFilesForJPGImage($siteid, $image_id,$image_size_name_array,$predelete=false)
	{
		if (!$image_id) return false;

		//Extract the original image file so we can work on it
		foreach($image_size_name_array as $image_size_name)
		{
			$newimagefile = $this->makeImageURL($siteid,$image_id,$image_size_name);
			$origimagefile = $this->config->paths->html . '/images/image_cache/' .$siteid.'_'.$image_id.'_.'.$this->getImageFileType($image_id);
			
			
			//Pre-delete the files if required. This is used when reloading
			//a file that previously existed in the db
			if ($predelete)
			{
				@ unlink($newimagefile);
				@ unlink($origimagefile);
			}

			if (!file_exists($newimagefile))
			{
				//Extract the image file from the db
				if (!file_exists($origimagefile))
				{
					$this->writeImageToFile($siteid,$image_id,$origimagefile);
				}

				//See if this is portrait
				list($origwidth, $origheight) = getimagesize($origimagefile);
				$portrait = ($origheight>$origwidth)?true:false;


				$dims=array($this->getImageWidth($image_size_name,$portrait),$this->getImageHeight($image_size_name,$portrait));
				$this->resizeJPGImageFile($origimagefile,$newimagefile,$dims,($image_size_name=='slide_image')?true:false);
			}
		}

		//delete the original file
		if (file_exists($origimagefile)) unlink($origimagefile);

		return true;
	}
	
	function writeImageToFile($siteid,$image_id,$filename)
	{
		$select = $this->db->select()->from('images', array('image'))
		->where('image_id = ?', $image_id)
		->where('site_id = ?', $siteid);

		$result = $this->db->fetchAll($select);

		if (count($result))
		{
			$n = file_put_contents($filename,$result[0]['image']);
			return $n;
		}
		return false;
	}
	
	//Image size fuctions, $imagetype are constants defined in contentIncludes.php
	function getImageWidth($imagesize,$portrait=false)
	{
		if ($portrait)
		{
			switch ($imagesize)
			{
				case small_image:
					return 145;
					break;
				case medium_image:
					return 330;
					break;
				case large_image:
					return 640;
					break;
				case slide_image:
					return 330;
					break;
				default:
					echo '$imagetype constant not defined in function getImageWidth in contentClass';
			}
			return 640;
		}
		else {
			switch ($imagesize)
			{
				case small_image:
					return 145;
					break;
				case medium_image:
					return 330;
					break;
				case large_image:
					return 640;
					break;
				case slide_image:
					return 330;
					break;
				default:
					echo '$imagetype constant not defined in function getImageWidth in contentClass';
			}
			return 640;
		}
	}
	
	function getImageHeight($imagesize,$portrait=false)
	{
		if ($portrait)
		{
			switch ($imagesize)
			{
				case small_image:
					return 0;
					break;
				case medium_image:
					return 200;
					break;
				case large_image:
					return 0;
					break;
				case slide_image:
					return 250;
					break;

				default:
					echo '$imagetype constant not defined in function getImageWidth in contentClass';
			}
			return 0;

		} else {
			switch ($imagesize)
			{
				case small_image:
					return 0;
					break;
				case medium_image:
					return 0;
					break;
				case large_image:
					return 0;
					break;
				case slide_image:
					return 250;
					break;

				default:
					echo '$imagetype constant not defined in function getImageWidth in contentClass';
			}
			return 0;
		}
	}
	
	function resizeJPGImageFile($origimagefile,$newimagefile,$dims,$center=false)
	{
		//Get the dimensions of the old file and new file
		list($origwidth, $origheight) = getimagesize($origimagefile);
		//echo $origwidth.', '.$origheight.'<br>';
		if (!$center)
		{
			$newdims = $this->calcImageScale($origwidth, $origheight, $dims[0], $dims[1]);
		}
		else
		{
			$newdims = $dims;
		}
		//print_r($newdims);

		exec('jhead -purejpg '.$origimagefile);

		//Get the image from the jpg file
		$origimage = imagecreatefromjpeg($origimagefile);
		//print_r($dims);

		//Resize it
		$newimage = $this->resizeJPGImage($origimage,$newdims,$center);

		//Save it as the new file
		// INI NEH BIANG KEROKNYA
		imagejpeg($newimage, $newimagefile);

		//Delete the memory images
		imagedestroy($origimage);
		imagedestroy($newimage);

		return true;
	}
	
	function calcImageScale($origWidth, $origHeight, $maxWidth, $maxHeight, $stretch=FALSE)
	{
		if (!$maxWidth && $maxHeight)
		{
			// Width is unlimited, scale by width
			$newh = $maxHeight;

			if ($origHeight < $maxHeight && !$stretch) { $newh = $origHeight; }
			else { $newh = $maxHeight; }

			$neww = ($origWidth * $newh / $origHeight);
		}
		elseif (!$maxHeight && $maxWidth)
		{
			// Scale by height
			if ($origWidth < $maxWidth && !$stretch) { $neww = $origWidth; }
			else { $neww = $maxWidth; }

			$newh = ($origHeight * $neww / $origWidth);
		}
		elseif (!$maxWidth && !$maxHeight)
		{
			return array($origWidth,$origHeight);
		}
		else
		{
			if ($origWidth / $maxWidth > $origHeight / $maxHeight)
			{
				// Scale by height
				if ($origWidth < $maxWidth && !$stretch) { $neww = $origWidth; }
				else { $neww = $maxWidth; }

				$newh = ($origHeight * $neww / $origWidth);
			}
			elseif ($origWidth / $maxWidth <= $origHeight / $maxHeight)
			{
				// Scale by width

				if ($origHeight < $maxHeight && !$stretch) { $newh = $origHeight; }
				else { $newh = $maxHeight; }

				$neww = ($origWidth * $newh / $origHeight);
			}
		}
		return array(round($neww),round($newh));
	}
	
	function resizeJPGImage($origimage,$dims,$center=false)
	{
		$origwidth = imagesx($origimage);
		$origheight = imagesy($origimage);

		if ($center)
		{
			$newdims = $this->calcImageScale($origwidth,$origheight,0,$dims[1],true);
		}
		else
		{
			$newdims = $dims;
		}

		//list($origwidth, $origheight, $type) = getimagesize($origimage);
		$newimage = imagecreatetruecolor($dims[0], $dims[1]);

		//Color the background white
		$color = imagecolorallocate($newimage,255,255,255);
		imagefilledrectangle($newimage,0,0,$dims[0],$dims[1],$color);

		imagecopyresampled($newimage, $origimage, ($center)?($dims[0]-$newdims[0])/2:0, 0, 0, 0, $newdims[0], $newdims[1], $origwidth, $origheight);
		return $newimage;
	}

	function getImageTypeId($filetype)
	{		
		$imageTypeTable = new image_type(array('db' => 'db'));
		$select = $imageTypeTable->select()
				->where('file_type = ?', $filetype);
		$result = $this->db->fetchRow($select);
		
		return $result['image_type_id'];
	}
	
	function getPopularImagesVideos($siteid, $type, $limit)
	{
		$contentImagesTable = new content_images(array('db' => 'db'));
		$result = $contentImagesTable->getAdapter()->fetchAll("
			SELECT ci.*, i.*, cg.views
			FROM content_images ci
			LEFT JOIN content_gallery_images cgi ON cgi.content_images_id=ci.content_images_id AND cgi.site_id={$siteid}
			LEFT JOIN content_gallery cg ON cg.content_gallery_id=cgi.content_gallery_id AND cg.site_id={$siteid}		
			LEFT JOIN images i ON i.source_id=ci.content_images_id AND i.site_id={$siteid}
			WHERE content_gallery_type_id='{$type}' AND ci.site_id='{$siteid}' AND i.image_id IS NOT NULL
			ORDER BY ci.views DESC
			LIMIT {$limit}
		");
		return $result;
	}
	
	function getImageByContentImagesId($siteid, $content_images_id)
	{
		$contentImagesTable = new content_images(array('db' => 'db'));
		$result = $contentImagesTable->getAdapter()->fetchRow("
			SELECT ci.*, i.*, cg.views, cg.content_gallery
			FROM content_images ci
			LEFT JOIN content_gallery_images cgi ON cgi.content_images_id=ci.content_images_id AND cgi.site_id={$siteid}
			LEFT JOIN content_gallery cg ON cg.content_gallery_id=cgi.content_gallery_id AND cg.site_id={$siteid}		
			LEFT JOIN images i ON i.source_id=ci.content_images_id AND i.site_id={$siteid}
			WHERE ci.content_images_id='{$content_images_id}' AND ci.site_id='{$siteid}'
		");
		return $result;
	}
	
	function updateImageView($content_images_id){
		$contentImagesTable = new content_images(array('db' => 'db'));
		$select = $contentImagesTable->select()
					->where("content_images_id=?", $content_images_id);
					
		$curview = $contentImagesTable->getAdapter()->fetchRow($select);
		$views = $curview["views"] + 1;
		
		$where = $contentImagesTable->getAdapter()->quoteInto("content_images_id=?", $content_images_id);
		$contentImagesTable->update(array("views"=>$views), $where);
	}
}

?>