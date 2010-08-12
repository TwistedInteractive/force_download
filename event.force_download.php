<?php

	require_once(TOOLKIT . '/class.event.php');
	
	Class eventforce_download extends Event{
		
		const ROOTELEMENT = 'force-download';
		
		public $eParamFILTERS = array(
			
		);
			
		public static function about(){
			return array(
					 'name' => 'Force Download',
					 'author' => array(
							'name' => 'Giel Berkers',
							'website' => 'http://www.gielberkers.com',
							'email' => 'info@gielberkers.com'),
					 'version' => '1.0',
					 'release-date' => '2010-08-12T11:48:04+00:00');	
		}

		public static function getSource(){
			return false;
		}

		public static function allowEditorToParse(){
			return false;
		}

		public static function documentation(){
			return '
			<h3>Force Download</h3>
			<p>
				When this event is attached to a page, it enables the page to force a download.
				The download can be triggered by adding the parameter <code>file</code> to the URL:
			</p>
			<pre class="XML"><code>'.htmlentities('<a href="/download/?file=workspace/uploads/manual.pdf">Download manual</a>').'</code></pre>
			<h3>Security</h3>
			<p>
				To prevent that anyone can download any file from your website you have to set which folders
				are allowed for visitors to download files of. Otherwise evil people can download your config-settings
				for example simply by changing the URL in the browser bar to: <code>/download/?file=manifest/config.php</code>.
			</p>
			<p>
				To do this, you need to edit the file <code>event.force_download.config.php</code>:
			</p>
			<pre class="PHP"><code>'.htmlentities('$allowedDirs = array(
	\'workspace/uploads\',
	\'workspace/uploads/manuals\',
	\'workspace/uploads/images\',
	
	...etc...
	
);').'</code></pre>
			<p>
				<em>Please note that each dir should be mentioned individualy, so a wildcard like <code>workspace/uploads/<strong>*</strong></code> will not work.</em>
			</p>
        ';
		}
		
		public function load(){
			if(isset($_GET['file'])) {
				include_once('event.force_download.config.php');
				$pathInfo = pathinfo($_GET['file']);
				// Check to see if the directory is allowed to direct-download from:
				if(in_array($pathInfo['dirname'], $allowedDirs))
				{
					// Determine the mimetype:
					if(array_key_exists(strtolower($pathInfo['extension']), $mime_types))
					{
						$mimeType = $mime_types[strtolower($pathInfo['extension'])];
					} else {
						$mimeType = "application/force-download";
					}
					// Force the download:
					if (file_exists($_GET['file'])) {
						header('Content-Description: File Transfer');
						header('Content-Type: '.$mimeType);
						header('Content-Disposition: attachment; filename='.$pathInfo['basename']);
						header('Content-Transfer-Encoding: binary');
						header('Expires: 0');
						header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
						header('Pragma: public');
						header('Content-Length: ' . filesize($_GET['file']));
						ob_clean();
						flush();
						readfile($_GET['file']);
						exit;
					} else {
						die('File does not exist!');
					}
				} else {
					die('Permission denied!');
				}
			}
		}
		
		protected function __trigger(){
			include(TOOLKIT . '/events/event.section.php');
			return $result;
		}		

	}

