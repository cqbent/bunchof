<?php
/*
Plugin Name: Forms
Plugin URI: http://wordpress.org/extend/plugins/forms/
Description: DRY forms (don't repeat yourself) which validate themselves server-side. Form results can be emailed. Hooks and options make plugin very flexible.
Version: 0.4 (beta11)
Author: Weston Ruter
Author URI: http://weston.ruter.net/
Copyright: 2010, Weston Ruter, Shepherd Interactive. GPL 3 License.

I thought: why are we trying to define a nice clean datastructure for the
schema, when this needs to be represented by a sloppy form. We need to start
with the sloppy form and derive the schema from it! Simply define a function in
functions.php which returns a FORM element, then in any page, add a [form]
shortcode with a name parameter which equals the name of the function, or
alternatively this may be specified in the postmeta "form_name" so that no
attribute is needed in the shortcode.

@todo Include form submission in CSV attachment?
@todo For invalid controls, wrap their labels with STRONG@class=error?
@todo Include a register_form('function_name') so that we can eventually have a drop down?
@todo Specify data-email-attachment="true" for the attachement to not only be included in the URL but to also be included as an attachment to the email\
@todo Not sure how useful 'form_process_submission' action is right now. Needs more attachments
@todo All form post-valid actions should include list of all attachments
@todo Allow user to automatically specify the Subject and have that pass through to the email
@todo Indicate that a single field should be the body of the email. There could be a form/shortcode param called "email_subject_input". Same goes for "email_from_input"

*/

/**
 * Turn on output buffering if this is a response for a form submission (so we can set HTTP status)
 * A hidden input element such as @name=_form_name_submitted is sent along with the name of the form.
 */
function si_form_init(){
	if(isset($_REQUEST['_form_name_submitted']) && function_exists($_REQUEST['_form_name_submitted']))
		ob_start();
}
add_action('template_redirect', 'si_form_init');


/**
 * Serialize the form
 */
function si_form_serialize(&$doc, &$xpath){
	return apply_filters('form_markup', $doc->saveXML($doc->documentElement));
}

/**
 * Default filter which ensures that non-empty elements in HTML4 don't get
 * serialized out as XML empty elements.
 */
function si_form_default_markup_filter($markup){
	$emptyElements = array(
		'area', 'base', 'basefont', 'br', 'col', 'frame', 'hr', 'img', 'input', 'isindex', 'link', 'meta', 'param'
	);
	$regex = '{<(?!(?:' . join('|', $emptyElements) . ')\b)(\w+?\b)([^>]*?)/>}';
	return preg_replace($regex, '<$1$2></$1>', $markup);
}
add_filter('form_markup', 'si_form_default_markup_filter');

/**
 * Default form subject
 */
function si_form_default_subject($subject){
	if(!$subject)
		return "Form submission:" . wp_title('', false);
	return $subject;
}
add_filter('form_email_subject', 'si_form_default_subject');

/**
 * Helper function that formats error messages for output.
 */
function si_form_error($message, $type = '',  $source = ''){
	$html = '<p style="color:red"><em>';
	if(!$type)
		$type = __("Error", 'si_forms');
	$html .= "<strong>" . htmlspecialchars($type) . "</strong>: ";
	$html .= $message;
	$html .= '</em></p>';
	if($source){
		$html .= '<pre style="margin-left:5px; border-left:solid 1px red; padding-left:5px;"><code class="xhtml malformed">';
		$html .= htmlspecialchars($source);
		$html .= '</code></pre>';
	}
	return $html;
}

/**
 * When POSTing data, the shortcode is called twice, so the results from the first call should be cached so that they
 * don't have to be processed a second time. The array is indexed by form (function) name.
 * @global array $si_form_shortcode_cache  
 */
//$si_form_shortcode_cache = array();

/**
 * Registers a 'form' shortcode that has a required @name param indicating the function name
 * which returns the HTML code for the shortcode
 */
function si_form_shortcode_handler($atts, $content = null){
	global $si_form_shortcode_cache, $post;
	extract(shortcode_atts(array(
		'name' => '',
		'email_to' => '',
		'email_cc' => '',
		'email_bcc' => '',
		'email_subject' => '',
		'email_include_empty_fields' => null,
		'success_url' => '',
		'success_page_id' => 0,
		'cc_sender' => false,
		'email_type' => 'table'
	), $atts));
	$form_name = $name; unset($name);
	
	//if(!is_null($content))
		//return si_form_error(sprintf(__("Enclosing form short codes not supported.", 'si_forms'), htmlspecialchars($form_name)));
	
	//Serve cached output if it has already been processed
	if(!empty($si_form_shortcode_cache[$form_name]))
		return $si_form_shortcode_cache[$form_name];

	//Error: missing shortcode 'name' attribute
	if(!$form_name && !($form_name = get_post_meta($post->ID, 'form_name', true)))
		return si_form_error(sprintf(__("Missing required 'form_name' postmeta or 'name' attibute for 'form' shortcode, eg: <code>[form name=\"my_form_function\"]</code>", 'si_forms')));
	
	//Get recipient
	if(!$email_to)
		$email_to = join(',', (array)get_post_meta($post->ID, 'form_email_to', false));
	if(!$email_cc)
		$email_cc = join(',', (array)get_post_meta($post->ID, 'form_email_cc', false));
	if(!$email_bcc)
		$email_bcc = join(',', (array)get_post_meta($post->ID, 'form_email_bcc', false));
	//if(!$email_to && !($email_to = get_post_meta($post->ID, 'form_recipient', true))){
	//	$email_to = get_option('admin_email');
	//	#return si_form_error(sprintf(__("Missing required 'form_recipient' postmeta or 'recipient' attibute for 'form' shortcode.", 'si_forms')));
	//}
	
	//Shortcode 'subject' attribute
	if(!$email_subject)
		$email_subject = get_post_meta($post->ID, 'form_email_subject', true);
	
	if(is_null($email_include_empty_fields))
		$email_include_empty_fields = (bool)get_post_meta($post->ID, 'form_email_include_empty_fields', true);
	
	//Shortcode 'success_page_id' attribute 
	if(!$success_page_id)
		$success_page_id = (int)get_post_meta($post->ID, 'form_success_page_id', true);
	
	//success_page_id overwrites success_url
	if($success_page_id)
		$success_url = get_permalink($success_page_id);
	
	//Shortcode 'success_url' attribute
	if(!$success_url)
		$success_url = get_post_meta($post->ID, 'form_success_url', true);
	
	//The upload_path may not be specified in the shortcode, as authors should not be able to do so
	//UPDATE: Don't even let anyone set the upload path from WordPress; only those who have access to filesystem should be able
	//$upload_path = get_post_meta($post->ID, 'form_success_url', true);
	
	//Option: send_email
	#$_send_email = get_post_meta($post->ID, 'form_send_email', false);
	#if(count($_send_email))
	#	$send_email = (boolean)$_send_email[0];
	
	//Option: cc_sender
	#$_cc_sender = get_post_meta($post->ID, 'form_cc_sender', false);
	#if(count($_cc_sender))
	#	$cc_sender = (boolean)$_cc_sender[0];
	
	$_email_type = get_post_meta($post->ID, 'form_email_type', true);
	if($_email_type)
		$email_type = $_email_type;
	
	//Error: name does not correspond to an existing function
	if(!function_exists($form_name))
		return si_form_error(sprintf(__("No function <code>%s</code> exists which returns the HTML for this form.", 'si_forms'), htmlspecialchars($form_name)));

	//Call the function and grab the results (if nothing, output a comment noting that it was empty)
	$xhtml = call_user_func_array($form_name, array($atts, $content));
	if(!$xhtml)
		return "<!-- form handler '$form_name' returned nothing -->";

	//Parse the form, return error if isn't well-formed
	$doc = new DOMDocument();
	if(!$doc->loadXML('<?xml version="1.0" encoding="utf-8"?>' . $xhtml))
		return si_form_error(sprintf(__("The function <code>%s</code> did not return wellformed XML:", 'si_forms'), htmlspecialchars($form_name)), __('XML Parse Error', 'si_forms'), $xhtml);
	$xpath = new DOMXPath($doc);
	
	//Error: root element must be "form"
	if($doc->documentElement->nodeName != 'form')
		return si_form_error(sprintf(__("The function <code>%s</code> did not return valid XML. Root element must be <code>form</code>:", 'si_forms'), htmlspecialchars($atts['name'])), __('XML Wellformedness Error', 'si_forms'), $xhtml);
	$form = $doc->documentElement;
	
	//Add a hidden input which tells the server which form the request data is associated with
	//This element is removed before processing
	$formNameInput = $doc->createElement('input');
	$formNameInput->setAttribute('type', 'hidden');
	$formNameInput->setAttribute('name', '_form_name_submitted');
	$formNameInput->setAttribute('value', $form_name);
	$form->appendChild($formNameInput);
	
	//Set the default attributes on the FORM element
	if(!$form->hasAttribute('action'))
		$form->setAttribute('action', get_permalink());
	if(!$form->hasAttribute('method'))
		$form->setAttribute('method', 'post');
	if(!$form->hasAttribute('id'))
		$form->setAttribute('id', $form_name);
	
	//Populate the form with the values provided in the request
	$items = si_form_populate_with_request_and_return_values($doc, $xpath);
	
	$invalidCount = 0;
	$invalidElements = array();
	
	//Allow the form to be customized
	do_action('form_before_validation', $form_name, $doc, $xpath);
	
	//Detect whether or not any of the elements are in error (only do this if the request method is the same as the form's method,
	//  so that we can pre-fill form values for POST requests with GET parameters.)
	//  TODO: If there are two forms on a page, this will fail!!
	if(strtoupper($doc->documentElement->getAttribute('method')) == $_SERVER['REQUEST_METHOD'] &&
	   (($_SERVER['REQUEST_METHOD'] == 'POST' && @$_POST['_form_name_submitted'] == $form_name) ||
		($_SERVER['REQUEST_METHOD'] == 'GET'  && @$_GET['_form_name_submitted'] == $form_name))
	){
		
		$validFileInputs = array();
		foreach($xpath->query("//*[@name and not(@disabled) and not(@readonly)]") as $input){
			//Skip the hidden self-identifying field
			if($input->getAttribute('name') == '_form_name_submitted')
				continue;
			
			$invalidTypes = array();
			$isToggle = ($input->getAttribute('type') == 'radio' || $input->getAttribute('type') == 'checkbox');
			$isFile = ($input->getAttribute('type') == 'file');
			
			if($input->nodeName == 'textarea')
				$value = (string)$input->textContent;
			else if($input->nodeName == 'select'){
				$selectedOption = $xpath->query(".//option[@selected]", $input)->item(0); //TODO: no multiple values supported
				if($selectedOption){
					$value = $selectedOption->hasAttribute('value') ? $selectedOption->getAttribute('value') : $selectedOption->textContent;
				}
				else {
					$value = null;
				}
			}
			elseif($isFile)
				$value = @$_FILES[$input->getAttribute('name')]['name'];
			else
				$value = (string)$input->getAttribute('value'); #TODO: Multi file uploads not currently supported
			
			//REQUIRED
			//If the element is required, and its value DOM attribute applies and is in the mode value, and the element is
			//  mutable, and the element's value is the empty string, then the element is suffering from being missing.
			if($input->hasAttribute('required') && ($isToggle ? !$input->hasAttribute('checked') : !$value)){
				$invalidTypes[] = 'valueMissing';
			}
			else if($value) {
				//FILE UPLOAD
				if($isFile){
					$validationMessage = '';
					switch($_FILES[$input->getAttribute('name')]['error']){
						case 1:
							$validationMessage = __("File is bigger than this PHP installation allows.", 'si_forms');
							break;
						case 2:
							$validationMessage = __("File is bigger than this form allows.", 'si_forms');
							break;
						case 3:
							$validationMessage = __("Only part of the file was uploaded.", 'si_forms');
							break;
						case 4:
							$validationMessage = __("No file was uploaded.", 'si_forms');
							break;
					}
					if($validationMessage){
						$input->setAttribute('data-validationMessage', $validationMessage);
						$invalidTypes[] = 'customError';
					}
				}
				
				//PATTERN
				//If the element's value is not the empty string, and the element's pattern  attribute is specified and the 
				//  attribute's value, when compiled as an ECMA 262 regular expression with the global, ignoreCase, and multiline
				//  flags disabled (see ECMA 262, sections 15.10.7.2 through 15.10.7.4), compiles successfully but the resulting
				//  regular expression does not match the entirety of the element's value, then the element is suffering from a pattern mismatch. [ECMA262]
				if($value && $input->getAttribute('pattern') && !preg_match('/^(?:' . $input->getAttribute('pattern') . ')$/', $value)){
					$invalidTypes[] = 'patternMismatch';
				}
				
				//MAXLENGTH
				if((int)$input->getAttribute('maxlength') && mb_strlen($value, 'utf-8') > (int)$input->getAttribute('maxlength')){
					$invalidTypes[] = 'tooLong';
				}
				
				//Input types
				switch($input->getAttribute('type')){
					case 'email':
						if(!preg_match('/.+@.+(\.\w+)$/', $value)){
							$invalidTypes[] = 'typeMismatch';
						}
						break;
					case 'range':
					case 'number':
						$value = trim($value);
						//Verify value
						if(!preg_match('/^-?\d+(\.\d+)?$/', $value)){
							$invalidTypes[] = 'typeMismatch';
						}
						else {
							$value = floatval($value);
							
							//Min
							if($input->hasAttribute('min')){
								$min = floatval($input->getAttribute('min'));
								if((float)$min > $value)
									$invalidTypes[] = 'rangeUnderflow';
							//Max
							}
							else if($input->hasAttribute('max')){
								$max = floatval($input->getAttribute('max'));
								if((float)$max < $value)
									$invalidTypes[] = 'rangeOverflow';
							}
						}
						
						//$step = intval($input->getAttribute('step'));
						//if(!$step)
						//	$step = 1;
						//
						if(!preg_match('/\d+/', $value)) #TODO: make this more robust
							$invalidTypes[] = 'typeMismatch';
						break;
					case 'url':
						if(!preg_match('{^http://.+}', $value))
							$invalidTypes[] = 'typeMismatch';
						break;
						
					#TODO: More input types
				}
			}
			
			//Custom Validity
			$validationMessage = apply_filters('form_control_custom_validity', $input->getAttribute('data-validationMessage'), $input, $form_name, $doc, $xpath);
			if($validationMessage){
				$input->setAttribute('data-validationMessage', $validationMessage);
				$invalidTypes[] = 'customError';
			}
			
			//Set the state of the input if it is invalid
			if(!empty($invalidTypes)){
				$input->setAttribute('class', $input->getAttribute('class') . ' invalid');
				$invalidElements[] = $input;
				$input->setAttribute('data-invalidity', join(' ', $invalidTypes));
				//TODO: Wrap a strong element around the text content of the invalid INPUT's LABEL?				
				$invalidCount++;
			}
			//Capture the valid file control for later processing
			else if($isFile && $value) {
				$validFileInputs[] = $input;
			}
		}
		
		//Get custom form validity
		$error_message = '';
		$formValid = !count($invalidElements);
		if($formValid){
			$error_message = apply_filters('form_custom_validity', '', $form_name, $doc, $xpath);
			if($error_message)
				$formValid = false;
		}
		
		if($formValid){
			try {
				$wpUploadDir = wp_upload_dir();
				//Handle file uploads
				foreach($validFileInputs as $input){
					//$uploadPath = $upload_path;
					$uploadPath = $input->getAttribute('data-upload-path'); //TODO Problem: this could result in people using this site as a file server
					if(!$uploadPath)
						$uploadPath = parse_url(trailingslashit($wpUploadDir['baseurl']), PHP_URL_PATH);
					$uploadPath = apply_filters('form_file_upload_path', $uploadPath, $input, $form_name, $doc, $xpath);
					$fileURL = '';
					if($uploadPath){
						//Verify that the upload path is in the /wp-content/uploads/ tree
						$uploadPath = trailingslashit($uploadPath);
						$uploadDir = realpath(ABSPATH . '/' . $uploadPath);
						if(strpos($uploadDir, realpath(trailingslashit($wpUploadDir['basedir']))) !== 0){
							throw new Exception(sprintf(__("As a security measure, uploading is restricted to the '%s' tree.", 'si_forms'), $wpUploadDir['baseurl']));
						}
						
						//Attempt to move the file
						$fileinfo = $_FILES[$input->getAttribute('name')];
						$file = wp_unique_filename( $uploadDir, basename($fileinfo['name']));
						if(!@move_uploaded_file($fileinfo['tmp_name'], trailingslashit($uploadDir) . $file)){
							$msg = str_replace(
								array('%file', '%path'),
								array($fileinfo['name'], $uploadPath),
								!is_writable(ABSPATH . $uploadPath) ?
									__("Unable to store your uploaded file '%file' because the directory %path is not writable.", 'si_forms') :
									__("Unable to store your uploaded file '%file' for some unknown reason.", 'si_forms')
							);
							if(@wp_mail(get_option('admin_email'),
							            $_SERVER['HTTP_HOST'] . " File Upload Error",
							            "$msg\n\nOn page: http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]\nUsing form in function: $form_name()"))
							{
								$msg .= ' ' . __("An email has been sent to the administrator.", 'si_forms');
							}
							throw new Exception($msg);
						}
						
						//Push the URL onto the items that will be sent in the email
						$items[$input->getAttribute('name')][] = array(
							'value' => get_option('siteurl') . trailingslashit($uploadPath) . $file,
							'label' => $xpath->query("//label[@for = '" . $input->getAttribute('id') . "']")->item(0),
							'input' => $input
						);
					}
				}
				do_action('form_process_submission', $form_name, $doc, $xpath); //TODO: This is weak
			}
			catch(Exception $e){
				$error_message = $e->getMessage();
				$formValid = false;
			}
		}
	
		//If there is an invalid element, then the form is invalid; autofocus on it
		if(!$formValid){
			@status_header(400);
			$form->setAttribute('class', $form->getAttribute('class') . ' form_error_400');
			
			//Remove any existing autofocus, and set it on the first invalid element
			foreach($xpath->query("//*[@autofocus]") as $input)
				$input->removeAttribute('autofocus');
			if(count($invalidElements))
				$invalidElements[0]->setAttribute('autofocus','autofocus');
			
			if(!$error_message){
				if(count($invalidElements) == 1)
					$error_message = __('There was an error with your form submission.', 'si_forms');
				else
					$error_message = __('There were errors with your form submission.', 'si_forms');
			}
			$error_message = apply_filters('form_error_message', $error_message, $form_name, $invalidElements, $doc, $xpath);
			do_action('form_error', $form_name, $error_message, $doc, $xpath);
			si_form_populate_errors($doc, $xpath, $error_message);
			
			do_action('form_complete', $form_name);
			return si_form_serialize($doc, $xpath); //apply_filters('form_markup', $doc->saveXML($doc->documentElement));
		}
		//Try processing the data
		else {
			try {
				
				$formSerialized = si_form_serialize($doc, $xpath); //apply_filters('form_markup', $doc->saveXML($doc->documentElement));
				
				$docEmail = $doc->cloneNode(true);
				do_action('form_before_controls_removed', $form_name, $docEmail);
				
				if($email_type != 'text'){
					$headers = array('Content-type: text/html; charset=utf-8');
					
					//Format the email in a DL description list
					if($email_type == 'dl'){
						$email_message = '<dl xmlns="http://www.w3.org/1999/xhtml">';
						foreach($items as $itemName => $itemContents){
							if(!apply_filters('form_email_included_field', true, $itemName, $itemContents, $form_name, $doc, $xpath))
								continue;
							foreach($itemContents as $part){
								if(($part['value'] || $email_include_empty_fields) && $part['input']->getAttribute('type') != 'hidden'){
									$email_message .= "<dt>";
									if($part['input']->hasAttribute('data-email-label')){
										$email_message .= htmlspecialchars($part['input']->getAttribute('data-email-label'));
									}
									else if($part['label']){
										$email_message .= htmlspecialchars(
																		$part['label']->hasAttribute('data-email-label') ?
																		$part['label']->getAttribute('data-email-label') :
																		$part['label']->firstChild->textContent);
									}
									$email_message .= "</dt>";
									$email_message .= "<dd>";
									if($part['input']->nodeName == 'textarea')
										$email_message .= make_clickable(wpautop(htmlspecialchars(strip_tags($part['value']))));
									else
										$email_message .= make_clickable(htmlspecialchars(strip_tags($part['value'])));
									$email_message .= "<dd>\n";
								}
							}
						}
						$email_message .= '</dl>';
					}
					//Format the email in the origianl form layout
					else if($email_type == 'form'){
						$email_message = si_form_remove_controls($docEmail);
					}
					//Format the email in a table
					else /*if($email_type == 'table')*/{
						$email_message = '<table xmlns="http://www.w3.org/1999/xhtml">';
						foreach($items as $itemName => $itemContents){
							if(!apply_filters('form_email_included_field', true, $itemName, $itemContents, $form_name, $doc, $xpath))
								continue;
							foreach($itemContents as $part){
								if(($part['value'] || $email_include_empty_fields) && $part['input']->getAttribute('type') != 'hidden'){
									$email_message .= "<tr>\n";
									$email_message .= "<th align='right' valign='top'>";
									if($part['input']->hasAttribute('data-email-label')){
										$email_message .= htmlspecialchars($part['input']->getAttribute('data-email-label'));
									}
									else if($part['label']){
										$email_message .= htmlspecialchars(
																		$part['label']->hasAttribute('data-email-label') ?
																		$part['label']->getAttribute('data-email-label') :
																		$part['label']->firstChild->textContent);
									}
									$email_message .= "</th>";
									$email_message .= "<td align='left' valign='top'>";
									if($part['input']->nodeName == 'textarea')
										$email_message .= make_clickable(wpautop(htmlspecialchars(strip_tags($part['value']))));
									else
										$email_message .= make_clickable(htmlspecialchars(strip_tags($part['value'])));
									$email_message .= "</td></tr>\n";
								}
							}
						}
						$email_message .= '</table>';
					}
				}
				//Plain text email
				else {
					$headers = array('Content-type: text/plain; charset=utf-8');
					$email_message = '';
					foreach($items as $itemName => $itemContents){
						if(!apply_filters('form_email_included_field', true, $itemName, $itemContents, $form_name, $doc, $xpath))
							continue;
						foreach($itemContents as $part){
							if(($part['value'] || $email_include_empty_fields) && $part['input']->getAttribute('type') != 'hidden'){
								if($part['input']->hasAttribute('data-email-label')){
									$email_message .= htmlspecialchars($part['input']->getAttribute('data-email-label'));
								}
								else if($part['label']){
									$email_message .= htmlspecialchars(
																	$part['label']->hasAttribute('data-email-label') ?
																	$part['label']->getAttribute('data-email-label') :
																	$part['label']->firstChild->textContent);
								}
								//$email_message .= trim($part['label'] ? $part['label']->firstChild->textContent : '');
								$after_label_whitespace = "";
								if($part['input']->nodeName == 'textarea')
									$after_label_whitespace = "\r\n"; //$email_message .= "\r\n" . $part['value'];
								else if(!($part['input']->getAttribute('type') == 'checkbox' || $part['input']->getAttribute('type') == 'radio') || $part['input']->getAttribute('value'))
									$after_label_whitespace = " "; //$email_message .= ' ' . $part['value'];
								$after_label_whitespace = apply_filters('form_text_email_after_label_whitespace', $after_label_whitespace, $itemName, $itemContents, $form_name, $doc, $xpath);
								
								$email_message .= $after_label_whitespace . $part['value'];
								$email_message .= apply_filters('form_text_email_field_separator', "\r\n\r\n", $itemName, $itemContents, $form_name, $doc, $xpath);
							}
						}
					}
				}
				
				//Get the from address
				$submitterName = "";
				$submitterEmail = "";
				if(is_user_logged_in() && apply_filters('form_email_from_logged_in_user', true)){
					$current_user = wp_get_current_user();
					if($current_user->first_name && $current_user->last_name)
						$submitterName = $current_user->first_name . ' ' . $current_user->last_name;
					else
						$submitterName = $current_user->display_name;
					$submitterEmail = $current_user->user_email;
				}
				else if(($emailInput = $xpath->query('//input[@type = "email"]')->item(0)) && $emailInput->getAttribute('value')){
					$fullnameInput =  $xpath->query('//input[@type = "text" and @value and @value != "" and contains(@name, "name")]')->item(0);
					$firstnameInput = $xpath->query('//input[@type = "text" and @value and @value != "" and contains(@name, "name") and (contains(@name, "first") )]')->item(0);
					$lastnameInput  = $xpath->query('//input[@type = "text" and @value and @value != "" and contains(@name, "name") and (contains(@name, "last") or contains(@name, "surname") )]')->item(0);
				
					if($firstnameInput && $lastnameInput)
						$submitterName = $firstnameInput->getAttribute('value') . ' ' . $lastnameInput->getAttribute('value');
					else if($fullnameInput)
						$submitterName = $fullnameInput->getAttribute('value');
					
					$submitterEmail = $emailInput->getAttribute('value');
					//if($submitterName){
					//	//TODO: How should these values be escaped?
					//	$from = '"' . str_replace('"', "''", stripslashes($submitterName)) . '" ';
					//	$from .= '<' . $emailInput->getAttribute('value') . '>';
					//}
					//else {
					//	$from = $emailInput->getAttribute('value');
					//}
				}
				
				//Prevent HTTP Header Injection (already handled by PHPMailer anyway)
				$submitterName = preg_replace("([\r\n\"<>])", "", $submitterName);
				$submitterEmail = sanitize_email($submitterEmail); //preg_replace("([\r\n\"<>])", "", $submitterEmail));
				
				$submitterName = apply_filters('form_email_submitter_name', $submitterName, $form_name, $doc, $xpath);
				$submitterEmail = apply_filters('form_email_submitter_email', $submitterEmail, $form_name, $doc, $xpath);
				
				//Construct From:
				if(!$submitterName && $submitterEmail)
					$from = $submitterEmail;
				else if($submitterName && $submitterEmail)
					$from = "\"$submitterName\" <$submitterEmail>"; #$from = '"' . ($submitterName) . '" <' . $submitterEmail . '>';
				else
					$from = '';
				$from = apply_filters('form_email_from', $from, $form_name, $doc, $xpath);
				if($from)
					$headers[] = "From: $from"; 
				
				//Construct Reply-To:
				$replyTo = "\"$submitterName\" <$submitterEmail>";
				$replyTo = apply_filters('form_email_reply_to', $replyTo, $form_name, $doc, $xpath);
				if($replyTo)
					$headers[] = "Reply-To: $replyTo"; 
				
				//Construct CC
				$email_cc = apply_filters('form_email_cc', $email_cc, $form_name, $doc, $xpath);
				if($cc_sender || $email_cc){
					$cc = array();
					if($cc_sender && !empty($from))
						$cc[] = $from;
					if($email_cc)
						$cc[] = $email_cc;
					
					$headers[] = "CC: " . join(', ', $cc);
				}
				
				//Construct BCC
				$email_bcc = apply_filters('form_email_bcc', $email_bcc, $form_name, $doc, $xpath);
				if($email_bcc)
					$headers[] = "BCC: $email_bcc";
				
				$email_to = apply_filters('form_email_to', $email_to, $form_name, $doc, $xpath);
				
				//Filter the subject
				$email_subject = apply_filters('form_email_subject', $email_subject, $form_name, $doc, $xpath);
				
				//Filter the email contents
				$email_message = apply_filters('form_email_message', $email_message, $form_name, $email_type, $headers, $doc, $xpath);
				
				$headers = apply_filters('form_email_headers', $headers, $form_name);
				
				$success = true;
				
				//Send mail
				if($email_to){
					$email_success = @wp_mail($email_to, $email_subject, $email_message, $headers);
					do_action('form_email', $form_name, $email_success, $email_to, $email_subject, $email_message, join("\r\n", $headers), $doc, $xpath);
					if(apply_filters('form_abort_on_email_failure', true, $form_name))
						$success = $email_success;
				}
				
				if($success){
					$formSerialized = $email_message;
					$success_url = apply_filters('form_success_url', $success_url, $form_name, $doc, $xpath);
					do_action('form_success', $form_name, $success_url, $doc, $xpath);
					if($success_url)
						wp_redirect($success_url, 303);
				}
				else {
					throw new Exception(__('We were unable to accept your request at this time (unable to send email). Please try again.', 'si_forms'));
				}
				
				do_action('form_complete', $form_name);
				return $formSerialized;
			}
			catch(Exception $e){
				do_action('form_error', $form_name, $e->getMessage(), $doc, $xpath, $e);
				@status_header(500);
				$form->setAttribute('class', $form->getAttribute('class') . ' form_error_500');
				si_form_populate_errors($doc, $xpath, $e->getMessage());
				
				//Set the autofocus on the submit button (this only works if there is only one submit button! TODO)
				$submit = $xpath->query('//*[@type = "submit"]')->item(0);
				if($submit){
					foreach($xpath->query("//*[@autofocus]") as $input)
						$input->removeAttribute('autofocus');
					$submit->setAttribute('autofocus','autofocus');
				}
				
				do_action('form_complete', $form_name);
				return si_form_serialize($doc, $xpath); //apply_filters('form_markup', $doc->saveXML($doc->documentElement));
			}
		}
	}
	else {
		return si_form_serialize($doc, $xpath); //apply_filters('form_markup', $doc->saveXML($doc->documentElement));
	}

	#return $si_form_shortcode_cache[$name];
}
add_shortcode('form', 'si_form_shortcode_handler');


/**
 * Ensure that HTML emails only have allowed HTML tags
 */
add_filter('form_email_message', 'wp_filter_post_kses');


/**
 * Populate the form with the error message
 */
function si_form_populate_errors(&$doc, &$xpath, $message){

	//Populate the error message
	$containers = $xpath->query('//*[contains(@class, "form_error_message")]');
	if($containers->length){
		foreach($containers as $container){
			while($container->firstChild)
				$container->removeChild($container->firstChild);
			$em = $doc->createElement('em');
			$em->appendChild($doc->createTextNode($message));
			$container->appendChild($em);
			
			//Make sure that it's visible
			$container->removeAttribute('hidden');
			if($container->hasAttribute('style'))
				$container->setAttribute('style', preg_replace('/display:\s*none\s*;?|visibility:\s*hidden\s*;?/i', '', $container->getAttribute('style')));
		}
	}
	else {
		$notice = $doc->createElement('p');
		$notice->setAttribute('class', 'form_error_message');
		$em = $doc->createElement('em');
		$em->appendChild($doc->createTextNode($message));
		$notice->appendChild($em);
		$doc->documentElement->insertBefore($notice, $doc->documentElement->firstChild);
	}
}


/**
 * Populate the form with the values present in the request
 */
function si_form_populate_with_request_and_return_values(&$doc, &$xpath){

	if($_SERVER['REQUEST_METHOD'] == 'POST')
		$request = &$_POST;
	else if($_SERVER['REQUEST_METHOD'] == 'GET')
		$request = &$_GET;
	else
		return null;
	
	$items = array();
		
	$populatedValues = 0;
	
	//Strip magic quotes
	//if(get_magic_quotes_gpc())
		$request = stripslashes_deep((array)$request);
	
	//Iterate over the request name/value pairs
	foreach($request as $attrName => $attrValue){
		//Skip the hidden self-identifying field
		if($attrName == '_form_name_submitted')
			continue;
			
		$items[$attrName] = array();
		
		/*** Array values *********************************************/
		if(is_array($attrValue)){
			#$items[$inputName]['values'] = $attrValue;
			
			//Strip slashes if magic quotes are turned on
			//if(get_magic_quotes_gpc()){
			//	foreach($attrValue as &$v){
			//		$v = stripslashes($v);
			//	}
			//}
			
			$originalAttrValue = (array) $attrValue;
			
			//Iterate over all form elements with the current name
			$inputs = $xpath->query("//*[@name='{$attrName}[]']");
			if(!$inputs->length)
				continue;
			$populatedValues++;
			
			//$inputValue = '';
			foreach($inputs as $input){
				switch($input->nodeName){
					//INPUT elements
					case 'input':
						switch($input->getAttribute('type')){
							case 'checkbox':
							case 'radio':
								if($input->getAttribute('value') == @$attrValue[0] || (!$input->hasAttribute('value') && @$attrValue[0] == 'on')){
									$input->setAttribute('checked','checked');
									@array_shift($attrValue);
								}
								else {
									$input->removeAttribute('checked');
								}
								break;
							case 'file':
								break;
							default:
								$input->setAttribute('value', @$attrValue[0]);
								@array_shift($attrValue);
								break;
						}
						break;
					//TEXTAREA element
					case 'textarea':
						while($input->firstChild)
							$input->removeChild($input->firstChild);
						if(@$attrValue[0]){
							$input->appendChild($doc->createTextNode(@$attrValue[0]));
							@array_shift($attrValue);
						}
						break;
					//SELECT element
					case 'select':
						$options = $input->getElementsByTagName('option');
						if($options->length){
							foreach($options as $option){
								//If the OPTION has a @value
								if($option->hasAttribute('value')){
									if($option->getAttribute('value') == @$attrValue[0]){
										$option->setAttribute('selected','selected');
										@array_shift($attrValue);
									}
									else {
										$option->removeAttribute('selected');
									}
								}
								//If value passed from child node
								else {
									if((!@$attrValue[0] && !$option->firstChild) || ($option->firstChild && $option->firstChild->nodeValue == @$attrValue[0])){
										$option->setAttribute('selected','selected');
										@array_shift($attrValue);
									}
									else
										$option->removeAttribute('selected');
								}
							}
						}
						break;
				}
				
				
				$isRadioOrCheckbox = ($input->getAttribute('type') == 'radio' || $input->getAttribute('type') == 'checkbox');	
				if(!$isRadioOrCheckbox || $input->hasAttribute('checked')){
					$item = array(
						'value' => $input->getAttribute('value'),
						'label' => '',
						'input' => $input
					);
					if($input->nodeName == 'button' || $input->getAttribute('type') == 'submit')
						$item['label'] = $input;
					else #if(!$isRadioOrCheckbox)
						$item['label'] = $xpath->query("//label[@for = '" . $input->getAttribute('id') . "']")->item(0);
					$items[$attrName][] = $item;
				}
			}
		}
		/*** Scalar values *********************************************/
		else {
			//if(get_magic_quotes_gpc())
			//	$attrValue = stripslashes($attrValue);
			
			$inputs = $xpath->query("//*[@name='$attrName']");
			if($inputs->length){ //== 1
				$populatedValues++;
			
				foreach($inputs as $input){
					
					switch($input->nodeName){
						//INPUT elements
						case 'input':
							switch($input->getAttribute('type')){
								case 'checkbox':
								case 'radio':
									if($input->getAttribute('value') == @$attrValue || (!$input->hasAttribute('value') && @$attrValue == 'on')) //if($input->getAttribute('value') == $attrValue)
										$input->setAttribute('checked','checked');
									else
										$input->removeAttribute('checked');
									break;
								case 'file':
									break;
								default:
									$input->setAttribute('value', $attrValue);
									break;
							}
							break;
						//TEXTAREA element
						case 'textarea':
							while($input->firstChild)
								$input->removeChild($input->firstChild);
							$input->appendChild($doc->createTextNode($attrValue));
							break;
						//SELECT element
						case 'select':
							$options = $input->getElementsByTagName('option');
							if($options->length){
								foreach($options as $option){
									//If the OPTION has a @value
									if($option->hasAttribute('value')){
										if($option->getAttribute('value') == $attrValue)
											$option->setAttribute('selected','selected');
										else
											$option->removeAttribute('selected');
									}
									//If value passed from child node
									else {
										if((!$attrValue && !$option->firstChild) || ($option->firstChild && $option->firstChild->nodeValue == $attrValue))
											$option->setAttribute('selected','selected');
										else
											$option->removeAttribute('selected');
									}
								}
							}
							break;
					}
					
					$isRadioOrCheckbox = ($input->getAttribute('type') == 'radio' || $input->getAttribute('type') == 'checkbox');
					if(!$isRadioOrCheckbox || $input->hasAttribute('checked')){
						$item = array(
							'value' => $attrValue,
							'label' => '',
							'input' => $input
						);
						if($input->nodeName == 'button' || $input->getAttribute('type') == 'submit')
							$item['label'] = $input;
						else #if(!$isRadioOrCheckbox)
							$item['label'] = $xpath->query("//label[@for = '" . $input->getAttribute('id') . "']")->item(0);
						$items[$attrName][] = $item;
					}
					//$items[$attrName][] = array(
					//	'label' => $xpath->query("//label[@for = '" . $input->getAttribute('id') . "']")->item(0),
					//	'value' => $attrValue
					//);
				}
			}
		}
		
	} //end foreach
	
	//Populate input@type=file elements with user-supplied value
	//The file inputs are added to $items later; TODO NOTE: we should get rid of $items anyway
	//TODO: Multi-uploads not supported
	foreach((array)$_FILES as $inputName => $file){
		if(!@$file['name'])
			continue;
		$input = $xpath->query("//input[@type='file' and @name='" . addslashes($inputName) . "']")->item(0);
		if($input){
			$input->setAttribute('value', @$file['name']);
		}
	}
	
	if($populatedValues)
		$doc->documentElement->setAttribute('class', $doc->documentElement->getAttribute('class') . " form_populated");
	
	return $items;
}


/**
 * Replace all form elements with plain text nodes containing their values
 */
function si_form_remove_controls(&$doc){
	$xpath = new DOMXPath($doc);
	
	foreach($xpath->query('//*[@name]') as $input){
		
		$replacement = null;
		$removeLabel = false;
		
		//Find the label
		$label = null;
		if($input->parentNode->nodeName == 'label')
			$label = $input->parentNode;
		else if($input->hasAttribute('id'))
			$label = $xpath->query('//label[@for="' . $input->getAttribute('id') . '"]')->item(0);
		
		switch($input->nodeName){
			//INPUT element
			case 'input':
				switch($input->getAttribute('type')){
					case 'file':
						#Processed later
						break;
					
					case 'radio':
					case 'checkbox':
						//If not checked, the control and the label are removed;
						if($input->hasAttribute('checked')){
							//If there is a label, then it should be used as the display text
							if($label && $input->getAttribute('value')){
								$replacement = $doc->createElement('abbr');
								$replacement->setAttribute('title', $input->getAttribute('value'));
								$replacement->appendChild($doc->createTextNode($label->textContent));
							}
							else if($label) {
								$replacement = $doc->createTextNode($label->textContent);
							}
							else {
								$replacement = $doc->createTextNode($input->getAttribute('value'));
							}
							
							//Remove the label because it will be replaced with the input value
							if($label){
								if($input->parentNode->isSameNode($label))
									$label->parentNode->replaceChild($input, $label);
								else
									$label->parentNode->removeChild($label);
							}
						}
						//Remove the label if the control is not checked???
						else if($label){
							$label->parentNode->removeChild($label);
						}
						break;
					case 'hidden':
					case 'button':
					case 'add':
					case 'remove':
					case 'delete':
					case 'move-up':
					case 'move-down':
						break;
					default:
						if($input->getAttribute('value')){
							$replacement = $doc->createTextNode($input->getAttribute('value'));
						}
						else {
							$replacement = $doc->createElement('em');
							$replacement->appendChild($doc->createTextNode(__('(Empty)', 'si_forms')));
						}
						break;
				}
				break;
			
			//SELECT element
			case 'select':
				$options = $xpath->query('.//option[@selected]', $input);
				if($options->length > 1){
					$replacement = $doc->createElement('ul');
					foreach($options as $option){
						$li = $doc->createElement('li');
						if($option->getAttribute('value')){
							$contents = $doc->createElement('abbr');
							$contents->setAttribute('title', $option->getAttribute('value'));
							$contents->appendChild($doc->createTextNode($option->textContent));
						}
						else
							$contents = $doc->createTextNode($option->textContent);
						$li->appendChild($contents);
						$replacement->appendChild($li);
					}
				}
				else if($options->length == 1){
					$option = $options->item(0);
					if($option->getAttribute('value')){
						$replacement = $doc->createElement('abbr');
						$replacement->setAttribute('title', $option->getAttribute('value'));
						$replacement->appendChild($doc->createTextNode($option->textContent));
					}
					else
						$replacement = $doc->createTextNode($option->textContent);
				}
				break;
			//TEXTAREA element
			case 'textarea':
				if($input->firstChild){
					$replacement = $doc->createElement('pre');
					$replacement->appendChild($doc->createTextNode($input->textContent));
				}
				else {
					$replacement = $doc->createElement('em');
					$replacement->appendChild($doc->createTextNode(__('(Empty)', 'si_forms')));
				}
				break;
		}
		
		//Replace the control with the replacement
		if($replacement){
			$input->parentNode->replaceChild($replacement, $input);
			
			//if($label && $label == $input->parentNode){
			//	#$label->parentNode->replaceChild($replacement, $label);
			//	#$replacement->setAttribute('style', 'outline:solid 1px red;');
			//	$input->parentNode->replaceChild($replacement, $input);
			//}
			//else {
			//	#if($label)
			//	#	$label->parentNode->removeChild($label);
			//	$input->parentNode->replaceChild($replacement, $input);
			//}
		}
		//Remove the control completely if no replacement provided
		else {
			$input->parentNode->removeChild($input);
		}
	}
	
	//Remove all BUTTONs
	foreach($xpath->query('//button') as $button){
		$button->parentNode->removeChild($button);
	}
	
	return si_form_serialize($doc, $xpath); //apply_filters('form_markup', $doc->saveXML($doc->documentElement));
}