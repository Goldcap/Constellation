// JavaScript Document
function confirmEmail() {
  if (($("#user_email_new").val() == '') || ($("#user_email_confirm").val() == '') || ($("#user_email_confirm").val() != $("#user_email_new").val())) {
    return false;
  }
  return true;
}

function confirmPassword() {
  if (($("#new_password").val() == '') || ($("#new_password_confirm").val() == '') || ($("#new_password_confirm").val() != $("#new_password").val())) {
    return false;
  }
  return true;
}
