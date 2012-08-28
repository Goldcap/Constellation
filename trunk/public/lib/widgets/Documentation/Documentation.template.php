<div id="docs">
<h1>Constellation.tv</h1>

<h2>Development Documentation</h2>

<h3>Overview</h3>

<p>Constellation.tv is written in Symfony 1.4, with two standard Symfony applications:</p>

<ul>
	<li>frontend</li>
	<li>admin</li>
</ul>

<p>Most standard Symfony features are used, the ORM being <a href="http://www.propelorm.org/documentation/">Propel</a> (not Doctrine). Symfony is a well-documented opensource framework originally written for use by Yahoo, and can be <a href="http://www.symfony-project.org/doc/">found here</a>. There are three notable extensions to the Standard symfony toolkit, which are:</p>

<ul>
	<li>Symfony Page Widgets</li>
	<li>WTVR Components</li>
	<li>Styroform Form Libraries</li>
</ul>

<p>These extensions, along with all other third party libraries, are listed below along with any available documentation.</p> 

<h3>Primary Libraries</h3>

<ul>
	<li><a href="#" class="title">Symfony Page Widgets</a>
			<p><a href="/docs/libraries/PageWidgets/docs/README.doc" target="_new">Overview (doc)</a> | <a href="/docs/libraries/PageWidgets/docs/index.html" target="_new">PHP Docs</a></p>
			<p><strong>Symfony Page Widgets</strong> are superclass of the standard "Module Action" class, and inheret all of those properties, and are used to abstract
many of the deeper features of Symfony 1.4, and also organize and standardize application components into unique "Widgets" that can be run either in a Widget Factory pattern, or separately from the command line as utility wrappers.</p>
	</li>
	<li><a href="#" class="title">WTVR Components</a>
			<p><a href="/docs/libraries/WTVR/docs/README.doc" target="_new">Overview (doc)</a></p>
			<p><strong>WTVR Components</strong> are ported from a PHP XML framework developed in 2003. The specific parts of WTVR in use are WTVR Data, and WTVR Mail, used for abstracting queries into XML, and the Mail Template/Queue features.</p>
</li>
	<li><a href="#" class="title">Styroform Form Generator</a>
			<p><a href="/docs/libraries/styroform/docs/README.doc" target="_new">Overview (doc)</a> | <a href="/docs/libraries/styroform/docs/index.html" target="_new">PHP Docs</a></p>
			<p><strong>Styroform Form Libraries</strong> are used extensively in the administrative application, but not in the frontend. Styroform provides CRUD Functions, as well as XML/XSL Forms to easily generate administrative features.</p>
	</li>
</ul>

<h3>Other Libraries</h3>

<ul>	
	<li><a href="#" class="title">BaseIntEncoder</a>
			<p>Random Numeric Sequence Generator</p>
	</li>
	<li><a href="#" class="title">Akamai EdgeAuth-2.0.1</a>
			<p><a href="docs/libraries/EdgeAuth-2.0.1/README" target="_new">Overview</a></p>
			<p>Akamai Tokenizer for RTMP/HD</p>
	</li>
	<li><a href="#" class="title">FacebookOAuth</a>
		  <p><a href="docs/libraries/FacebookOAuth/readme.md" target="_new">Overview</a></p>
			<p>Facebook OAuth Wrapper, from Facebook</p>
			
	</li>
	<li><a href="#" class="title">MailChimp</a>
			<p><a href="http://apidocs.mailchimp.com/" target="_new">Overview</a></p>
			<p>Mailchimp PHP API</p>
	</li>
	<li><a href="#" class="title">Maxmind</a>
			<p><a href="http://apidocs.mailchimp.com/" target="_new">Overview</a> | <a href="/docs/libraries/Maxmind/ccfd_php_1.49/README" target="_new">Docs</a></p>
			<p>For fraud detection during purchases, and for GEOIP, we use MAXMIND.</p>
	</li>
	<li><a href="#" class="title">MongoSession</a>		
			<p>MongoDB Wrapper for sessions, used in the Admin Only.</p>
	</li>
	<li><a href="#" class="title">OLE-0.5</a>
			<p>Static build of OLE for Excel Spreadsheet Writer and PEAR.</p>
			
	</li>
	<li><a href="#" class="title">Opentok</a>
			<p><a href="/docs/libraries/Opentok/README" target="_new">Overview</a> | <a href="http://www.tokbox.com/opentok/api/documentation" target="_new">Documentation</a></p>
			<p>Video Chat API</p>
			
	</li>
	<li><a href="#" class="title">Paypal</a>
			<p><a href="https://cms.paypal.com/us/cgi-bin/?cmd=_render-content&content_ID=developer/library_documentation" target="_new">Documentation</a></p>
			<p>For Paypal Express Payment</p>

	</li>
	<li><a href="#" class="title">PaypalWPP</a>
			<p><a href="https://cms.paypal.com/us/cgi-bin/?cmd=_render-content&content_ID=developer/library_documentation" target="_new">Documentation</a></p>
			<p>For Paypal Website Payments Pro</p>

	</li>
	<li><a href="#" class="title">PEAR-1.5.0RC2</a>
			<p>Static build of PEARL for Excel Spreadsheet Writer.</p>
			
	</li>
	<li><a href="#" class="title">php-smtp-email-validation</a>
			<p><a href="http://code.google.com/p/php-smtp-email-validation/" target="_new">Documentation.</a></p>
			<p>Checks an email at the smtp level to validate an address.</p>
	
	</li>
	<li><a href="#" class="title">PHPCLI</a>
			<p>Formats output for Bash in colors, foreground and background.</p>
	
	</li>
	<li><a href="#" class="title">phpDocumentor2</a>
			<p><a href="http://www.phpdoc.org/docs/latest/index.html" target="_new">Documentation</a></p>
			<p>Generates PHP Documentation.</p>
	
	</li>
	<li><a href="#" class="title">phpexcel</a>
			<p><a href="http://www.codeplex.com/PHPExcel" target="_new">Documentation.</a></p>
			<p>Excel Input Parser for PHP</p>
	
	
	</li>
	<li><a href="#" class="title">phpmailer</a>
			<p><a href="docs/libraries/phpmailer/docs/faq.html" target="_new">Overview</a></p>
			<p>Mail Transport Wrapper for PHP</p>
			
	</li>
	<li><a href="#" class="title">Selenium</a>
			<p><a href="http://www.phpunit.de/manual/3.6/en/selenium.html" target="_new">Overview</a></p>
			<p>Selenium Wrappers for PHP Unit Tests</p>
	
	</li>
	<li><a href="#" class="title">shuber-curl</a>
			<p>CURL Wrappers for PHP</p>
			
	</li>
	<li><a href="#" class="title">SolrPhpClient</a>
			<p>Solr XML wrapper for PHP.</p>
	
	</li>
	<li><a href="#" class="title">Spreadsheet_Excel_Writer-0.9.1</a>
			<p><a href="http://pear.php.net/package/Spreadsheet_Excel_Writer/download/0.9.1/" target="_new">Documentation.</a></p>
			<p>PEAR Excel Formatter for PHP</p>
			
	</li>
	<li><a href="#" class="title">SpreadsheetReader</a>
	    <p><a href="http://code.google.com/p/php-spreadsheetreader/" target="_new">Documentation.</a></p>
			<p>PEAR Excel Reader for PHP</p>
			
	
	</li>
	<li><a href="#" class="title">TwitterOAuth</a>
	    <p><a href="http://www.jaisenmathai.com/articles/twitter-php-oauth.html" target="_new">Overview</a></p>
			<p>Twitter OAuth Wrapper, from GitHub</p>
	
	</li>
	<li><a href="#" class="title">utils</a>
	    <p>Generic String and Date Formatting Utilities</p>
	
	</li>
	<li><a href="#" class="title">WideXML</a>
			<p>XML Abstraction Classes</p>
			
	</li>
</ul>

<h3>Unused Libraries</h3>

<ul>
	<li>AES</li>
	<li>AmazonS3</li>
	<li>AWS-SDK</li>
	<li>abraham-twitteroauth-76446fa</li>
	<li>dadoo</li>
	<li>Date-1.4.7</li>
	<li>ebayapi</li>
	<li>FedExDC</li>
	<li>filenice</li>
	<li>Flickr</li>
	<li>fpdf</li> 
	<li>googleAnalytics</li>
	<li>Log</a></li>
	<li>Log.php</a></li>
	<li>MailUser</li>
	<li>MerchantOne</li>
	<li>OpenInviter</li>
	<li>PFPro</li>
	<li>PortStemmer.class.php</li>
	<li>phpTimer</li>
	<li>PHPUnit</li>
	<li>phpYoutube</li>
	<li>Phlickr</li>
	<li>SecureStreamingPHP-3.0.1</li>
	<li>stringTools.php</li>
	<li>ZendGdata-1.10.8</li>
	
</ul>

</div>