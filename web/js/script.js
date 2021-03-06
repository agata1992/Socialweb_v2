window.onload = window.onresize = function(event){
	
	var screenWidth = $(window).width();
	
	if(screenWidth < 768){
		
		if(document.getElementById('jumbotronProfile') != null){
			document.getElementById("infoDiv").classList.remove("pull-right");
			document.getElementById("infoDiv").classList.add("text-center");
			document.getElementById("imageDiv").classList.add("text-center");
			document.getElementById("jumboImg").style.marginLeft = "auto";
			document.getElementById("jumboImg").style.marginRight = "auto";
			document.getElementById('secondSection').getElementsByTagName('div')[0].classList.add("text-center");
			document.getElementById('secondSection').getElementsByTagName('a')[1].classList.remove("pull-right");
			document.getElementById('secondSection').getElementsByTagName('div')[1].classList.add("text-center");
		}
	}
	else{
	
		if(document.getElementById('jumbotronProfile') != null){
			document.getElementById("infoDiv").classList.add("pull-right");
			document.getElementById("imageDiv").classList.remove("text-center");
			document.getElementById("jumboImg").style.marginLeft = "0";
			document.getElementById('secondSection').getElementsByTagName('div')[0].classList.remove("text-center");
			document.getElementById('secondSection').getElementsByTagName('div')[0].classList.remove("text-center");
			document.getElementById('secondSection').getElementsByTagName('a')[1].classList.add("pull-right");
		}
	}
	
	if(screenWidth < 992){
		
		if(document.getElementById('postsField') != null){
			document.getElementById('postsField').classList.remove('mainContainer');
			document.getElementById('postsField').classList.add('mainContainer2');
		}
	}
	else{
		
		if(document.getElementById('postsField') != null){
			document.getElementById('postsField').classList.remove('mainContainer2');
			document.getElementById('postsField').classList.add('mainContainer');
		}
	}
	
	elements = document.getElementsByClassName('postImg');
	
	for(i=0;i<elements.length;i++ ){
		width = elements[i].width;
		elements[i].height = width;
	}
	
	elements = document.getElementsByClassName('subpostImg');
	
	for(i=0;i<elements.length;i++ ){
		width = elements[i].width;
		elements[i].height = width;
	}
	
	if ( $( "#modal1 .has-error" ).length ){
		
		element = $("#modal1 .has-error");
		
		if(element.attr("class").includes('has-error'))
			$("#modal1").modal();
	}
	
	if(document.getElementById('profileImage') != null){
		width = document.getElementById('profileImage').width;
		document.getElementById('profileImage').height = width;
	}
	
	if(document.getElementById('submenu') != null){
		pathMenu = document.getElementById('submenu').classList;
	
		if(pathMenu == 'path_album' || pathMenu == 'path_user_album')
			document.getElementById('path_galery').classList.add('active');
		}
}

/*
	type - more(param:1) or less(param:0)
	item - subposts(0) , comments(1) , subcomments(2)
*/

function moreLess(type,item,id){
	
	if(item == 0 || item == 2)
		minElement = 3;
	else if(item == 1)
		minElement = 13;
	
	if(item == 0)
		elements = document.getElementsByClassName('subpost_' + id);
	else if(item == 1)
		elements = document.getElementsByClassName('comment_');
	else if(item == 2)
		elements = document.getElementsByClassName('subcomm_' + id);
	
	for(i=0;i<elements.length;i++){
		
		className = elements[i].className;
		
		if(className.includes('hidden'))
			break;
	}
	
	if(type == 1){
	
		elements[i].classList.remove("hidden");
		
		if(item == 1){
		
			commId = elements[i].id;
			commId = commId.replace('commment_','');
			
			subcomments = document.getElementsByClassName('subcomm_' + commId);
			
			for(k = 0;k < subcomments.length;k++){
				
				subcomments[k].classList.remove("hidden");
				
				if(k == 2)
					break;
			}
				
			document.getElementById('subcomminp_' + commId).classList.remove("hidden");
			
			if(subcomments.length > 3)
				document.getElementById('subcommMore_' + commId).classList.remove('hidden');
		}
		
		if(elements.length - 1 > i){
		
			elements[i + 1].classList.remove("hidden");
			
			if(item == 1){
				commId = elements[i + 1].id;
				commId = commId.replace('commment_','');
				
				subcomments = document.getElementsByClassName('subcomm_' + commId);
			
				for(k = 0;k < subcomments.length;i++)
					subcomments[k].classList.remove("hidden");
				
				document.getElementById('subcomminp_' + commId).classList.remove("hidden");
			}
		}
		
		if(elements.length <= i + 2)
			
			if(item == 0)
				document.getElementById('subpostMore_' + id).classList.add('hidden');
			else if(item == 1)
				document.getElementById('commMore').classList.add('hidden');
			else if(item == 2)
				document.getElementById('subcommMore_' + id).classList.add('hidden');
		
		if(item == 0)
			document.getElementById('subpostLess_' + id).classList.remove('hidden');
		else if(item == 1)
			document.getElementById('commLess').classList.remove('hidden');
		else if(item == 2)
			document.getElementById('subcommLess_' + id).classList.remove('hidden');
		
		window.dispatchEvent(new Event('resize'));
	}
	else{
	
		elements[i - 1].classList.add("hidden");
		
		if(item == 1){
			commId = elements[i - 1].id;
			commId = commId.replace('commment_','');
			alert(commId);
			
			subcomments = document.getElementsByClassName('subcomm_' + commId);
			
			for(i = 0;i < subcomments.length;i++)
					subcomments[i].classList.add("hidden");
			
			document.getElementById('subcomminp_' + commId).classList.add("hidden");
			document.getElementById('subcommMore_' + commId).classList.add('hidden');
		}
		
		if(i % 2 == 1){
			
			elements[i - 2].classList.add("hidden");
			
			if(item == 1){
				
				commId = elements[i - 2].id;
				commId = commId.replace('commment_','');
				alert(commId);
				
				subcomments = document.getElementsByClassName('subcomm_' + commId);
			
				for(i = 0;i < subcomments.length;i++)
					subcomments[i].classList.add("hidden");
				
				document.getElementById('subcomminp_' + commId).classList.add("hidden");
			}
		}
		
		if(i - 2 <= minElement)
			if(item == 0)
				document.getElementById('subpostLess_' + id).classList.add('hidden');
			else if(item == 1)
				document.getElementById('commLess').classList.add('hidden');
			else if(item == 2)
				document.getElementById('subcommLess_' + id).classList.add('hidden');
			
		if(item == 0)
			document.getElementById('subpostMore_' + id).classList.remove('hidden');	
		else if(item == 1)	
			document.getElementById('commMore').classList.remove('hidden');
		else if(item == 2)	
			document.getElementById('subcommMore_' + id).classList.remove('hidden');
	}
}
	
function showModal(id){
	
	$("#" + id).modal();
}	


function uploadPhoto(){
	
	$('#form_photo').click();
}
	
$('#form_photo').change(function(){
	
	$('.photoUpload #form_submit1').click();
});