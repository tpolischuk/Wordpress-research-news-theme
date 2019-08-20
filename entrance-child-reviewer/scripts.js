
jQuery( document ).ready(function() {
  /* Stop message boxes from disappearing on click */
  jQuery('.g7-msg').unbind();

  /* Send e-mail subscription to Exact Target */
  function validateEmail($email) {
      var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
      return emailReg.test( $email );
    }

    //Cache the e-mail field
    $exactTargetEmailField = jQuery( "#exact-target-signup-form .email" );

    $exactTargetEmailField.focus(function() {
      jQuery( "#exact-target-names" ).slideDown();
    });

    jQuery("#exact-target-submit").on('click', function(e){

      var gdprConsent = jQuery("#gdpr-consent-wrapper input").is(":checked");

      var theEmail = $exactTargetEmailField.val();

      if (theEmail === "") {
        alert('E-mail address is required');
        e.preventDefault();
      } else if (gdprConsent === false) {
        alert('Please read and agree to the terms of the PLOS Privacy Policy.');
        e.preventDefault();
      }
  });
});
