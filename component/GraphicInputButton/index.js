[[INCLUDE component=ShadowButton]]

var newGraphicInputButton = function(name) {
	var dom = document.createElement('div');
	dom.className = 'comGraphicInputButton';

	var inputdiv = document.createElement('div');
	inputdiv.className = 'inputdiv';

	var input = document.createElement('input');
	inputdiv.appendChild(input);
	
	var button = document.createElement('button');
	button.className = 'shadow-button';
	button.innerHTML = name;


	dom.appendChild(button);
	dom.appendChild(inputdiv);

	input.addEventListener('keyup', function(event){
		if (this.value == '') {
			button.className = 'shadow-button';
		} else {
			button.className = 'shadow-button shadow-button-blue';
		}
	}, true);


	dom.input = input;
	dom.button = button;

	return dom;
};
