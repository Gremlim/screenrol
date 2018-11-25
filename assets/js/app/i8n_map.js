function create_i8n_map(_id) {

	let element=document.getElementById(_id);

	if(!element) {
		throw new Error("Could not build i8n map: container does not exist");
	}

	let result=new i8n_map();
	let items=element.querySelectorAll('dd');
	for(let i=0; i<items.length; i++) {
		let key=items[i].dataset.key;
		let value=items[i].innerHTML;
		result.insert(key, value);
	};

	return result;
}

function i8n_map() {}
i8n_map.prototype={

	map : [],

	insert : function(_k, _v) {
		this.map[_k]=_v;
	},

	get : function(_k) {

		if(undefined==this.map[_k]) {
			console.error("Key '"+_k+"' is not defined for i8n_map'");
			return '***';
		}

		return this.map[_k];
	},
	compose: function (_k,_values){
		if(undefined==this.map[_k]) {
			console.error("Key '"+_k+"' is not defined for i8n_map'");
			return '***';
		}
		let data =this.map[_k];
		for (var _index in _values) {
			data = data.replace(('<<#' + _index.toUpperCase() + '#>>'), _values[_index]);
		};
		return data;
	
	}
}
