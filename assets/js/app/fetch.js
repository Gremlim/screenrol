class fetch_error extends Error {
	constructor(_message, _body) {
		super(_message);
		this.body=_body;
	}
}

//!Quick, single fetch, unchainable, with built in error control.
function do_fetch(_url, _data, _callback_ok, _callback_err, _callback_finally) {

	return chain_fetch(_url, _data)
	.then((_data) => {
		_callback_ok(_data);
		if(undefined!==_callback_finally) {
			_callback_finally();
		}
	})
	.catch((_err) => {
		if(undefined===_callback_err) {
			console.error(_err);
			alert('Error fetching data: '+_err);
		}
		else {
			_callback_err(_err);
		}

		if(undefined!==_callback_finally) {
			_callback_finally();
		}
	});
}


//!Returns the fetch command that can be chained to others. There is no error
//!control, of course.
function chain_fetch(_url, _data) {

 	let url=_url;
	let response_type=get_fetch_val(_data, 'type');
	let fetch_data=prepare_fetch(_data, _url);

	return fetch(url, fetch_data)
	.then((_response) => {
		if(!_response.ok) {

			let err_type=get_fetch_val(_data, 'errtype', 'json');
			let proc=null;

			switch(response_type) {
				case 'json': proc=_response.json(); break;
				case 'text':
				default:
							proc=_response.text();
				break;
			}

			return proc.then((_err) => {throw new fetch_error('Fetch failed', _err);});
		}
		else {
			return _response;
		}
	})
	.then((_response) => {
			switch(response_type) {
				case 'text': return _response.text();
				case 'json': return _response.json();
				default: throw new Error("Unknown fetch type "+response_type);
			}
	});
}

function get_fetch_val(_data, _name, _default) {
	if(undefined===_data[_name]) {

		if(undefined===_default) {
			throw new Error("Could not find "+_name+" in do_fetch data");
		}
		return _default;
	}
	return _data[_name];
}

function prepare_fetch(_data, _url) {

	//Doing fetch data...
	let fetch_data={
		method : get_fetch_val(_data, 'method'),
	};

	if(undefined===_data['ignore_credentials']) {
		fetch_data.credentials='include';
	}

	//Quickheaders and headers are mutually exclusive.
	if(undefined!==_data['quickheaders']) {

		fetch_data.headers=new Headers();

		switch(_data['quickheaders']) {
			case 'form': fetch_data.headers.append('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8'); break;
			case 'auto':
				//Esto es por que si se mete una cabecera content-type a fuego
				//en el fetch, y es multipart con files no te agrega el boundary
				//solo lo hace en caso de no contener ninguna cabecera
						fetch_data.headers=null;
						delete fetch_data.headers;
			break;
			default: throw new Error("Undefined quick header type: "+_data['quickheaders']);
		}
	}
	else if(undefined!==_data['headers']) {
		if(!(_data['headers'] instanceof Headers)) {
			throw new Error("do_fetch headers must be of type Headers");
		}
		else {
			fetch_data.headers=_data['headers'];
		}
	}
	else {
		fetch_data.headers=new Headers();
	}

	if(undefined!==_data['body']) {

		fetch_data.body=_data['body'];
		//Assume the worst.
		if(undefined!==fetch_data.headers && !fetch_data.headers.has('Content-Type') && fetch_data.method!='GET') {
			console.error("No Content-Type headers were given to the request, adding defaults. Fix this by adding the headers through 'headers' or 'quickheaders'. This happened when calling "+_url+" with: ", fetch_data);
			fetch_data.headers.append('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
		}
	}

	return fetch_data;
}
