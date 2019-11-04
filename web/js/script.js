/*
	type - more(param:1) or less(param:0)
	item - subposts(0)
*/

function moreLess(type,item,id){
	
	elements = document.getElementsByClassName('subpost_' + id);
	
	for(i = 0;i<elements.length;i++){
		
		className = elements[i].className;
		
		if(className.includes('hidden'))
			break;
	}
	
	if(type == 1){
	
		elements[i].classList.remove("hidden");
		
		if(elements.length - 1 > i)
			elements[i + 1].classList.remove("hidden");
		
		if(elements.length <= i + 2)
			document.getElementById('subpostMore_' + id).classList.add('hidden');
			
		document.getElementById('subpostLess_' + id).classList.remove('hidden');
	}
	else{
	
		elements[i - 1].classList.add("hidden");
		
		if(i % 2 == 1)
			elements[i - 2].classList.add("hidden");
		
		if(i - 2 <= 3)
			document.getElementById('subpostLess_' + id).classList.add('hidden');
	}
}