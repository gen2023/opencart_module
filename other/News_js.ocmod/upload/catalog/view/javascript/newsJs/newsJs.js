const title = document.querySelectorAll('.newsJs_title');
	let current='';
	let description='';
	function currentDescription(){
		if (current){currentOff();}
		current=event.currentTarget;
		description = current.querySelector('.js_newsJs_content');
		
		current.classList.add("selected");
		description.style.display='block';
		
	}
	function currentOff(){
		current.classList.remove("selected");
		description.style.display='none';
	}
	for (let i=0; i<title.length; i++){
		title[i].addEventListener('click',currentDescription);
		title[i].addEventListener('mouseover',currentDescription);
	}