/**
 * Icegram Message Type - Toast
 **/
function Icegram_Message_Type_Toast(t){this.width=300,this.sticky=!1,this.duration=1e4,Icegram_Message_Type.apply(this,arguments)}Icegram_Message_Type_Toast.prototype=Object.create(Icegram_Message_Type.prototype),Icegram_Message_Type_Toast.prototype.constructor=Icegram_Message_Type_Toast,Icegram_Message_Type_Toast.prototype.get_template_default=function(){return'<li class="icegram toast ig_container {{=animation}} {{=theme}} ig_cta" id="icegram_message_{{=id}}"><div class="ig_wrapper"><div class="ig_content"><div class="ig_base"></div><div class="ig_line"></div><img class="ig_icon" src="{{=icon}}"/><div class="ig_headline">{{=headline}}</div><div class="ig_message">{{=message}}</div></div></div></li>'},Icegram_Message_Type_Toast.prototype.pre_render=function(){if(("10"==this.data.position||"12"==this.data.position)&&(this.data.position="20"),jQuery("ul#"+this.data.position).length)var t=jQuery("ul#"+this.data.position)
else{var t=jQuery('<ul id="'+this.data.position+'"></ul>').addClass("ig_toast_block").appendTo(this.root_container).hide()
t.width(this.width),"00"==this.data.position?t.css({top:"0",left:"0"}).addClass("left"):"01"==this.data.position?t.css({top:"0",left:"50%",margin:"5px 0 0 -"+this.width/2+"px"}).addClass("center"):"02"==this.data.position?t.css({top:"0",right:"0"}).addClass("right"):"20"==this.data.position?t.css({bottom:"0",left:"0"}).addClass("left"):"21"==this.data.position?t.css({bottom:"0",left:"50%",margin:"5px 0 0 -"+this.width/2+"px"}).addClass("center"):"22"==this.data.position?t.css({bottom:"0",right:"0"}).addClass("right"):"11"==this.data.position&&t.css({top:"50%",left:"50%",margin:"-"+this.width/2+"px 0 0 -"+this.width/2+"px"}).addClass("center")}this.root_container=t},Icegram_Message_Type_Toast.prototype.show=function(t,s){if(!this.is_visible()){!this.root_container.hasClass("active")&&this.root_container.addClass("active").show()
var i=this
setTimeout(function(){i.el.show(),i.el.fadeIn("slow")},this.data.delay_time),s!==!0&&i.track("shown"),!this.sticky&&this.duration>0&&setTimeout(function(){i.el.fadeOut("slow"),i.hide(),i.root_container.children().length||i.root_container.removeClass("active").hide()},this.duration)}},Icegram_Message_Type_Toast.prototype.hide=function(t,s){this.is_visible()&&(this.el.hide(),s!==!0&&this.track("closed"))}
