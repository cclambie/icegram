function Icegram_Message_Type_Messenger(e){Icegram_Message_Type.apply(this,arguments)}Icegram_Message_Type_Messenger.prototype=Object.create(Icegram_Message_Type.prototype),Icegram_Message_Type_Messenger.prototype.constructor=Icegram_Message_Type_Messenger,Icegram_Message_Type_Messenger.prototype.get_template_default=function(){return'<div class="icegram ig_messenger ig_{{=theme}} ig_{{=animation}} ig_container ig_cta" data="{{=id}}" id="icegram_message_{{=id}}"><div class="ig_content"><div class="ig_header"><div class="ig_header_image"></div><div class="ig_header_text"><div class="ig_headline">{{=headline}}</div></div></div><div class="ig_header2_image"></div><div class="ig_body"><img class="ig_icon" src="{{=icon}}"/><div class="ig_message">{{=message}}</div><div class="ig_separator"></div></div><div class="ig_footer"><div class="ig_footer_image"></div></div></div><div class="ig_close" id="ig_close_{{=id}}"></div></div>'},Icegram_Message_Type_Messenger.prototype.post_render=function(){""==this.data.icon&&(this.el.find(".ig_icon").remove(),this.el.find(".ig_message").addClass("ig_no_icon"))},Icegram_Message_Type_Messenger.prototype.set_position=function(){switch(this.data.position){case"20":this.el.css({left:5,bottom:0}),this.el.addClass("ig_left");break;case"22":default:this.el.css({left:jQuery(window).width()-this.el.outerWidth()-5,bottom:0}),this.el.addClass("ig_right")}},Icegram_Message_Type_Messenger.prototype.show=function(e,s){if(!this.is_visible()){var i=s!==!0?1e3:0;switch(this.data.animation){case"appear":this.el.fadeIn(i);break;case"slide":this.el.slideDown(i)}s!==!0&&this.track("shown")}},Icegram_Message_Type_Messenger.prototype.add_powered_by=function(e){this.el.find(".ig_content").after('<div class="ig_powered_by"><a href="'+e.link+'" target="_blank">'+e.text+"</a></div>")},Icegram_Message_Type_Messenger.prototype.hide=function(e,s){if(this.is_visible()){var i=s!==!0?1e3:0;switch(this.data.animation){case"appear":this.el.fadeOut(i);break;case"slide":this.el.slideUp(i)}s!==!0&&this.track("closed")}};