// JavaScript Document
var conf=
{
APIKey: '2_7e39X8h9Vjd3lvSyP9J9lKiNzQmuteFXhEFL_Jjo4preAg-dgJOnNy-9hd6gwcev'
,enabledProviders: 'facebook,twitter,yahoo,messenger,google,linkedin'
}

$(document).ready(function(){
	
  var act = new gigya.services.socialize.UserAction();
  
  var share_params=
  {
  userAction: act
  ,showEmailButton: true
  ,showMoreButton: true
  ,cid: ''
  }
  gigya.services.socialize.showShareUI(conf,share_params);
});
