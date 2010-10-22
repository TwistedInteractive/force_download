# Force Download #

When this event is attached to a page, it enables the page to force a download. The download can be triggered by adding the parameter `file` to the URL:

	<a href="/download/?file=workspace/uploads/manual.pdf">Download manual</a>

## Security ##

To prevent that anyone can download any file from your website you have to set which folders are allowed for visitors to download files of. Otherwise evil people can download your config-settings for example simply by changing the URL in the browser bar to: `/download/?file=manifest/config.php`.

To do this, you need to edit the file `event.force_download.config.php`:

	$allowedDirs = array(
	  'workspace/uploads',
	  'workspace/uploads/manuals',
	  'workspace/uploads/images',
	  
	  ...etc...
	  
	);

Please note that each dir should be mentioned individualy, so a wildcard like `workspace/uploads/*` will not work.

## Download the current page ##

You can also download the page itself, by adding the parameter `download` to the URL. The value of this parameter will be the name of the file. For example:

	<a href="/sheet/?download=sheet.xml">Download sheet in XML-format</a>