
var newCodeEditor = function() {

	var setValue = function(text) {
		this.value = text;
		this.selectionStart = 0;
		this.selectionEnd = 0;
	}

	var setBlurCallback = function(cb) {
		this.addEventListener('blur', cb, true);
	}

	// Constructor:
	var codeEditor = document.createElement('textarea');
	codeEditor.setAttribute('class', 'code-editor');
	//codeEditor.setAttribute('contentEditable', 'true');

	codeEditor.setValue = setValue;
	codeEditor.setBlurCallback = setBlurCallback;
	codeEditor.addEventListener('keydown', function(event) {
			if (event.keyCode == 9 && !event.shiftKey && !event.altKey && !event.ctrlKey) {
				event.stopPropagation();
				event.preventDefault();

				var selection_start = this.selectionStart;
				var selection_end = this.selectionEnd;
				if (selection_start == selection_end && !event.shiftKey) {
					var top = this.scrollTop;
					this.value = this.value.substring(0, selection_start)+ '\t' +
						this.value.substring(selection_end, this.value.length);
					this.selectionStart = selection_start+1;
					this.selectionEnd = selection_start+1;
					this.scrollTop = top;
				}
			}
		}, true)
	
	return codeEditor;
};
