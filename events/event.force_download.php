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
					'name' => 'Twisted Interactive',
					'website' => 'http://www.twisted.nl'),
				'version' => '1.2',
				'release-date' => '2012-05-09');
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
				To do this, you need to add a list of trusted locations to the \'Force Download\'-section on the preferences page.
			</p>
			<h3>Download the current page</h3>
			<p>
				You can also download the page itself, by adding the parameter <code>download</code> to the URL. The value of this parameter will be the name of the file. For example:
			</p>
			<pre class="XML"><code>'.htmlentities('<a href="/sheet/?download=sheet.xml">Download sheet in XML-format</a>').'</code></pre>
        ';
		}
		
		public function load()
		{
			// In case of the page:
			if(isset($_GET['download']))
			{
				header('Content-Disposition: attachment; filename='.$_GET['download']);
			}
			
			// In case of a file:
			if(isset($_GET['file'])) {
				// include_once('event.force_download.config.php');

				$driver = ExtensionManager::getInstance('force_download');
				/* @var $driver extension_force_download */
				$allowedDirs = $driver->getLocations();

				$pathInfo = pathinfo($_GET['file']);

				// Check to see if the directory is allowed to direct-download from:
				$wildCardMatch = false;
				$info = pathinfo($_GET['file']);
				foreach($allowedDirs as $allowedDir)
				{
					if(strstr($allowedDir, '/*') !== false)
					{
						$match = str_replace('/*', '', $allowedDir);
						if(strstr($match, $info['dirname']) !== false)
						{
							$wildCardMatch = true;
						}
					}
				}

				if(in_array($pathInfo['dirname'], $allowedDirs) || $wildCardMatch)
				{
					// Force the download:
					if (file_exists($_GET['file'])) {
						// Determine the mimetype:
						if(function_exists('mime_content_type'))
						{
							$mimeType = mime_content_type($_GET['file']);
						} elseif(function_exists('finfo_open')) {
							$finfo = finfo_open(FILEINFO_MIME_TYPE);
							$mimeType = finfo_file($finfo, $_GET['file']);
						} else {
							$mimeType = "application/force-download";
						}
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

