$(document).ready(function(){$(".mainTable").tablesorter({headers:{2:{sorter:"digit"},4:{sorter:false},5:{sorter:false},6:{sorter:false}},widgets:["zebra"],sortList:[[0,0]]});$("#file_tools").show();$("#download_selected").css("display","block");function b(d){$("#file_information_hold").slideDown("fast");$("#file_information_header").removeClass("closed");$("#file_information_header").addClass("open");$("#file_information_hold").html('<p style="text-align: center;"><img src="'+EE.THEME_URL+'images/indicator.gif" alt="'+EE.lang.loading+'" /><br />'+EE.lang.loading+"...</p>");$.get(EE.BASE+"&C=content_files&M=file_info",{file:d},function(e){$("#file_information_hold").html(e)})}$("#showToolbarLink a").toggle(function(){$("#file_manager_tools").hide();$("#showToolbarLink a span").text(EE.lang.show_toolbar);$("#showToolbarLink").animate({marginRight:"20"});$("#file_manager_holder").animate({marginRight:"10"})},function(){$("#showToolbarLink a span").text(EE.lang.hide_toolbar);$("#showToolbarLink").animate({marginRight:"314"});$("#file_manager_holder").animate({marginRight:"300"},function(){$("#file_manager_tools").show()})});$("#file_manager_tools h3 a").toggle(function(){$(this).parent().next("div").slideUp();$(this).toggleClass("closed")},function(){$(this).parent().next("div").slideDown();$(this).toggleClass("closed")});$("#file_manager_list h3").toggle(function(){document.cookie="exp_hide_upload_"+$(this).next().attr("id")+"=true";$(this).next().slideUp();$(this).toggleClass("closed")},function(){document.cookie="exp_hide_upload_"+$(this).next().attr("id")+"=false";$(this).next().slideDown();$(this).toggleClass("closed")});$("#file_manager_tools h3.closed").next("div").hide();$("#file_manager_tools h3.closed a").click();function a(d){$("#progress").html('<span class="notice">'+d+"</span>")}$("input[type=file]").ee_upload({url:EE.BASE+"&C=content_files&M=upload_file&is_ajax=true",onStart:function(d){$("#progress").html('<p><img src="'+EE.THEME_URL+'images/indicator.gif" alt="'+EE.lang.loading+'" />'+EE.lang.uploading_file+"</p>").show();dir_id=$("#upload_dir").val();return{upload_dir:dir_id}},onComplete:function(e,f,d){if(typeof(e)=="object"){if(e.success){var h="#dir_id_"+d.upload_dir;var g=EE.BASE+"&C=content_files&ajax=true&directory="+d.upload_dir+"&enc_path="+e.enc_path;$.get(g,function(i){var j=$("<div></div>");j.append(i);j=j.find("tbody tr");$(h+" tbody").append(j);$(h+" tbody .no_files_warning").parent().remove();$(h+" table").trigger("update");var k=[[0,0]];$("table").trigger("sorton",[k]);c(j);$("#progress").html(e).slideUp("slow")},"html")}else{a(e.error)}}}});$("#download_selected a").click(function(){$("#files_form").attr("action",$("#files_form").attr("action").replace(/delete_files_confirm/,"download_files"));$("#files_form").submit();return false});$("a#email_files").click(function(){alert("not yet functional");return false});$("#delete_selected_files a").click(function(){$("#files_form").attr("action",$("#files_form").attr("action").replace(/download_files/,"delete_files_confirm"));$("#files_form").submit();return false});$(".toggle_all").toggle(function(){$(this).closest("table").find("tbody tr").addClass("selected");$(this).closest("table").find("input.toggle").each(function(){this.checked=true})},function(){$(this).closest("table").find("tbody tr").removeClass("selected");$(this).closest("table").find("input.toggle").each(function(){this.checked=false})});$("input.toggle").each(function(){this.checked=false});function c(d){$("td.fancybox a").unbind("click").fancybox({showEditLink:true}).click(function(f){b($(this).attr("rel"))});$(".toggle").unbind("click").click(function(f){$(this).parent().parent().toggleClass("selected")});$(".mainTable td").unbind("click").click(function(f){if(f.ctrlKey||f.metaKey){$(this).parent().toggleClass("selected");if(!$(this).parent().find(".file_select :checkbox").attr("checked")){$(this).parent().find(".file_select :checkbox").attr("checked","true")}else{$(this).parent().find(".file_select :checkbox").attr("checked","")}}})}c()});