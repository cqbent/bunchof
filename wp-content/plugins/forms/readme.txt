=== Forms ===
Contributors: westonruter
Tags: web forms, forms, input, validation
Tested up to: 2.9.1
Requires at least: 2.7
Stable tag: trunk

DRY forms (don't repeat yourself) which validate themselves server-side. Form results can be emailed. Hooks and options make plugin very flexible.

== Description ==

<em>This plugin is developed at
<a href="http://www.shepherd-interactive.com/" title="Shepherd Interactive specializes in web design and development in Portland, Oregon">Shepherd Interactive</a>
for the benefit of the community. <b>No support is available. Please post any questions to the <a href="http://wordpress.org/tags/forms?forum_id=10">support forum</a>.</b></em>

Allows forms to be written with new HTML5 input type and validation attributes, and
then to have them validate themselves server-side: DRY forms (don't repeat yourself).
Forms are embedded into posts via the `form` shortcode. Results can be automatically emailed.
A web form is defined in a PHP function that returns an entire form element, for example:

`function my_contact_form($attrs, $content = null){
	ob_start();
	?>
	<form method="post">
		<p hidden="hidden" class="form_error_message"></p>
		<p>Name: <input type="text" required="required" name="contact_name" maxlength="255" placeholder="Jo Smith" autofocus="autofocus" /></p>
		<p>Email: <input type="email" required="required" name="contact_email" maxlength="255" placeholder="jsmith@example.com" /></p>
		<p>Message: <textarea required="required" name="contact_message" maxlength="1000" placeholder="Enter your message here"></textarea></p>
		<p><button type="submit">Submit</button></p>
	</form>
	<?php
	$ret = ob_get_contents();
	ob_end_clean();
	return $ret;
}`

And this form can be embedded into a post by entering <code>[form name="my_contact_form"]</code>.
Here are the shortcode options:

 * `name`
 * `email_to`
 * `email_cc`
 * `email_bcc`
 * `email_subject`
 * `success_url`
 * `success_page_id`
 * `cc_sender` default: <code>false</code>
 * `email_type` default: (HTML) <code>table</code>: other options <code>dl</code>, <code>text</code>, <code>form</code>

These options may also be specified via Custom Fields (postmeta):

 * `form_name`
 * `form_email_to`
 * `form_email_cc`
 * `form_email_bcc`
 * `form_email_subject`
 * `form_success_page_id`
 * `form_success_url`
 * `form_email_type`

Many filters and actions are available. Please see the source code.

When the form is submitted and the server determines that user data is invalid, then the server will respond with 400 Invalid Request.
In the 400 response, the page containing the form is returned and the form repopulated with the user's original request with
class names and `data-` attributes that describe the errors on an invalid element.

If the server successfully validates the form, then the user will be redirected to `success_url` or `success_page_id` if they are provided.

Please see source code for full documentation.

= Handling File Inputs =

Files are uploaded to `/wp-content/uploads/` (or whatever this is specified to
be in your install), unless filtered to be something different via
`form_file_upload_path` filter, or if the `data-upload-path` attribute is set on
the `file input` element, for example:

`<input type="file" data-upload-path="<?php
		$uploadDir = wp_upload_dir();
		echo esc_attr(parse_url(trailingslashit($uploadDir['baseurl']) . 'form-submissions/', PHP_URL_PATH));
?>" name="my_image" pattern=".+\.(jpe?g|gif|png)" />`

The upload path is appended to the <code>ABSPATH</code>,
and it must be within the <code>/wp-content/uploads/</code> directory (or
equivalent) or a security exception will be raised. When the server fails to
move the file or if it is too big or if some other error occurs (e.g. directory
not writable), then the form submission will respond with a 500 error and the
file control will be marked as <code>invalid customError</code> and the specific
error will be included on the <code>data-validationMessage</code> attribute.

== Changelog ==

= 2010-04-01: 0.4 (beta11) =
* Adding `form_abort_on_email_failure` filter so that emails don't have to abort success
* Added `form_complete` and `form_error` actions.

= 2010-01-22: 0.4 (beta10) =
* Fixing email_cc

= 2009-11-04: 0.4 (beta6) =
* Adding filters for plain text emails generated.
* Adding option/shortcode param `(form_)email_include_empty_fields` to force empty fields to be included in emails.

= 2009-10-29: 0.4 (beta5) =
* Adding `min` and `max` checks to number form inputs.

= 2009-10-15: 0.4 (beta2) =
* Adding `add_filter('form_email_message', 'wp_filter_post_kses')` and some other email sanitation checks, just in case.
* Now doing `stripslashes()` on request params regardless of `get_magic_quotes_gpc()` or `get_magic_quotes_runtime()` (WordPress always escapes the global request params in wp-settings.php)

= 2009-10-06: 0.4 (beta) =
* Non-empty HTML elements are ensured to not get serialized with XML shorthand
  empty syntax (space now not required in <code>p</code>)
* Handling of <code>file</code> input types. 
* The <code>data-email-label</code> attribute can now be supplied on the
  <code>input</code> element, not just the <code>label</code> element.
* Stubbed validation for <code>url</code>, <code>number</code>, and
  <code>range</code> types
* Multiple forms are now allowed on a single page. A <code>hidden</code> field
  named '_form_name_submitted' captures the name of the form that is submitted so the
  server can match up the request with the corresponding form.

= 2009-09-29: 0.3.9 =
* Initial release

