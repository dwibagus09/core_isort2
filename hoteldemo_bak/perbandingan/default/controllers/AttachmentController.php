<?php
require_once('actionControllerBase.php');

if(!function_exists('mime_content_type')) {
	function mime_content_type($filename) {

			$mime_types = array(
					'txt' => 'text/plain',
					'htm' => 'text/html',
					'html' => 'text/html',
					'php' => 'text/html',
					'css' => 'text/css',
					'js' => 'application/javascript',
					'json' => 'application/json',
					'xml' => 'application/xml',
					'swf' => 'application/x-shockwave-flash',
					'flv' => 'video/x-flv',

					// images
					'png' => 'image/png',
					'jpe' => 'image/jpeg',
					'jpeg' => 'image/jpeg',
					'jpg' => 'image/jpeg',
					'gif' => 'image/gif',
					'bmp' => 'image/bmp',
					'ico' => 'image/vnd.microsoft.icon',
					'tiff' => 'image/tiff',
					'tif' => 'image/tiff',
					'svg' => 'image/svg+xml',
					'svgz' => 'image/svg+xml',

					// archives
					'zip' => 'application/zip',
					'rar' => 'application/x-rar-compressed',
					'exe' => 'application/x-msdownload',
					'msi' => 'application/x-msdownload',
					'cab' => 'application/vnd.ms-cab-compressed',

					// audio/video
					'mp3' => 'audio/mpeg',
					'qt' => 'video/quicktime',
					'mov' => 'video/quicktime',

					// adobe
					'pdf' => 'application/pdf',
					'psd' => 'image/vnd.adobe.photoshop',
					'ai' => 'application/postscript',
					'eps' => 'application/postscript',
					'ps' => 'application/postscript',

					// ms office
					'doc' => 'application/msword',
					'rtf' => 'application/rtf',
					'xls' => 'application/vnd.ms-excel',
					'ppt' => 'application/vnd.ms-powerpoint',

					// open office
					'odt' => 'application/vnd.oasis.opendocument.text',
					'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
			);

			$ext = strtolower(array_pop(explode('.',$filename)));
			if (array_key_exists($ext, $mime_types)) {
					return $mime_types[$ext];
			}
			elseif (function_exists('finfo_open')) {
					$finfo = finfo_open(FILEINFO_MIME);
					$mimetype = finfo_file($finfo, $filename);
					finfo_close($finfo);
					return $mimetype;
			}
			else {
					return 'application/octet-stream';
			}
	}
}

class AttachmentController extends actionControllerBase
{
	function openattachmentAction()
	{
			$params = $this->_getAllParams();
			
			switch($params['c']) {
				case 1: $folder = 'security'; break;
				case 2: $folder = 'housekeeping'; break;
				case 3: $folder = 'safety'; break;
				case 5: $folder = 'parking'; break;
				case 7: $folder = 'operational'; break;
				case 8: $folder = 'mod'; break;
				case 9: $folder = 'bm'; break;
			}

			$attachmentFile = $this->config->paths->html.'/attachment/'.$folder.'/'.$params['s']."/".$params['f'];
			$mime = mime_content_type($attachmentFile);
			
			/*if($mime == 'application/pdf')
			{*/
				header("Content-type: ".$mime);
				header("Content-Disposition: inline; filename=".$params['f']);
				@readfile($attachmentFile);
			/*}
			/else
			{
				header("Content-type: {$mime}");
				@readfile($attachmentFile);
			}*/

	}
		
}

?>
