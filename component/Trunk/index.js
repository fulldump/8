// Component Trunk
(function(namespace){
	'use strict';
	
	var trunk = 'Trunk';
	var components = {};
	
	function register (component) {

		if ('function' != typeof component) {
			console.error('Trunk:register - The parameter "component" is not a function');
			return;
		}
		
		component.prototype.instances = {};
		component.prototype.autonumeric = 0;
		components[component.name] = component;
	}

	function get_component_id(component) {
		component.prototype.autonumeric++;
		return component.name + '-' + component.prototype.autonumeric;
	}

	function create_component_instance(component) {
		var id = get_component_id(component);

		var instance = new component();
		instance.dom.setAttribute('id', id);
		instance.dom.setAttribute('component', component.name);

		component.prototype.instances[id] = instance;

		return instance;
	}

	function create(component_name) {
		component_name = trunk + component_name;
		if (!(component_name in components)) {
			console.error('Trunk:create - The component does not exists');
			return;
		}

		var component = components[component_name];

		return create_component_instance(component);
	}
	
	function log() {
		console.log(components);
	}
	
	namespace.trunk = {
		register: register,
		create: create,
		log: log,
	};

})(window);
