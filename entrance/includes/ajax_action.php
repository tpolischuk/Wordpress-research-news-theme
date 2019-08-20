<?php
/**
 * Action for contact form
 * called from contact page template and contact widget
 */
if (!function_exists('g7_contact_send')) {
	function g7_contact_send() {
		$contact['name']    = trim(stripslashes($_POST['contact_name']));
		$contact['email']   = trim(stripslashes($_POST['contact_email']));
		$contact['message'] = trim(stripslashes($_POST['contact_message']));
		if (empty($contact['name'])) {
			$error['name'] = __('Please enter your name.', 'g7theme');
		}
		if (empty($contact['email'])) {
			$error['email'] = __('Please enter your email.', 'g7theme');
		} elseif (!preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $contact['email'])) {
			$error['email'] = __('Please enter a valid email address', 'g7theme');
		}
		if (empty($contact['message'])) {
			$error['message'] = __('Please enter your message.', 'g7theme');
		}
		if (!empty($_POST[HONEYPOT_FIELD])) {
			//honeypot
			$error['body'] = __('Sorry, there was a problem sending your message.', 'g7theme');
		}
		if (empty($error)) {
			$email_to = g7_option('contact_email');
			if (empty($email_to)) {
				$email_to = get_option('admin_email');
			}
			$subject = g7_option('contact_subject');
			$subject = str_replace('{name}', $contact['name'], $subject);
			$body = sprintf(
				"Name: %s \n\nEmail: %s \n\nMessage: %s",
				$contact['name'],
				$contact['email'],
				$contact['message']
			);
			$headers = sprintf(
				"From: %s <%s>\r\nReply-To: %s",
				$contact['name'],
				$contact['email'],
				$contact['email']
			);

			if (wp_mail($email_to, $subject, $body, $headers)) {
				$contact['status'] = 1; //send email success
				$contact['msg']    = g7_option('contact_success');
			} else {
				$contact['status'] = 2; //send email failed
				$contact['msg']    = __('Sorry, a server error occurred. Your message has not been sent.', 'g7theme');
			}
		} else {
			$contact['status'] = 3; //validation error
			$contact['error']  = $error;
		}
		return $contact;
	}
}


/**
 * Ajax contact form action
 */
if (!function_exists('g7_contact_form')) {
	function g7_contact_form() {
		check_ajax_referer('contact_nonce', 'security');
		$response = g7_contact_send();
		header('Content-type: application/json');
		echo json_encode($response);
		die;
	}
	add_action('wp_ajax_g7_contact_form', 'g7_contact_form');
	add_action('wp_ajax_nopriv_g7_contact_form', 'g7_contact_form');
}
