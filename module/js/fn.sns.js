/*SNS공유하기*/
var snsSend = {
	title: "",
	url: "",
	image: "",
	label: "",
	twitter: function(){
		var _this = this;
		var url   = _this.url; 
		var title = _this.title;

		window.open("http://twitter.com/intent/tweet?text=" + encodeURIComponent(title) + '&url=' + encodeURIComponent(url), "sns_pop", "width=600, height=400");
	},
	facebook: function(){
		var _this = this;
		var url   = _this.url; 
		var title = _this.title;

		window.open("http://www.facebook.com/sharer.php?u=" + encodeURIComponent(url) + "&t=" + encodeURIComponent(title), "sns_pop", "width=600, height=400");
	}
}