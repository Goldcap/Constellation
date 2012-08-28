// JavaScript Document

$(document).ready(function() {
  if (!window.console) window.console = {};
  if (!window.console.log) window.console.log = function() {};
  
  error.showError('notice','TESTING NOTICE TITLE','Testing notice message body','left');
  error.showError('notice','TESTING NOTICE TITLE','Testing notice message body','right');
  
  error.showError('error','TESTING ERROR TITLE','Testing error message body',1000);
  error.showError('error','TESTING ERROR TITLE','Testing error message body',4000);
  
  error.showError('alert','TESTING ALERT TITLE','Testing alert message body',1000);
  error.showError('alert','TESTING ALERT TITLE','Testing alert message body',4000);
                                                        
});
