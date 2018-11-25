let last_modal_type=null;
let last_modal_url=null;
$(document).ready(()=>{
	$('#dialog-modal').on('hide.bs.modal', function (_event) {
		if(_event.target.id==='dialog-modal'){
			$('#dialog-modal-body').html('');
		}
	});
})
function build_modal_controller_entrypoint(_type) {
 	return document.getElementById('document_base_tag').href+_type;
}


function open_modal_router(_title, _type, _data) {
		let url=build_modal_controller_entrypoint(_type);
		return fetch(
			url, {
				method: "POST",
				type:"json",
				body: url_encode_object(_data),
				headers : {'x-dash-modal':1},
				credentials : 'include'
			}
		)
		.then((_data) => {
			last_modal_type=_type;
			last_modal_url=url;
			return _data.text();
		})
		.then((_html) => {
			let modal = create_modal(_title,_html);
			modal.show();
			return modal;
		})
		.catch((_err) => {
			console.error(_err);
		});
}

function open_modal_data(_title=null, _body=null) {
	let modal = create_modal(_title, _body);
	modal.show();
}

function build_modal_body(_body) {
	return '<div id="modal_body">'+_body+'</div>';
}

function close_modal() {
	$('#dialog-modal').modal('hide');
}

function create_modal(_title=null,_body=null) {

	let base=document.createElement('div');
	base.className="modal fade";
	base.tabIndex=-1;
	base.setAttribute('role','dialog');
	base.setAttribute('aria-labelledby','dialog-modal-title');
	base.setAttribute('aria-hidden','true');

	let dialog=document.createElement('div');
	dialog.className ="modal-dialog modal-lg";

	let content=document.createElement('div');
	content.className="modal-content";

	let header=document.createElement('div');
	header.className="modal-header";
	
	let close_btn=document.createElement('button');
	close_btn.className="close";
	close_btn.type="button";
	close_btn.setAttribute('data-dismiss','modal');
	close_btn.innerHTML ='<span aria-hidden="true">&times;</span><span class="sr-only">Close</span>';
	
	let h4=document.createElement('h4');
	h4.className="modal-title";
	h4.innerHTML=_title;

	let body=document.createElement('div');
	body.className ="modal-body";
	body.innerHTML=_body;
	

	header.appendChild(close_btn);
	header.appendChild(h4);

	content.appendChild(header);
	content.appendChild(body);

	dialog.appendChild(content);

	base.appendChild(dialog);

	$(base).on('hide.bs.modal', function (_event) {
		if (_event.target === base) {
			base.parentNode.removeChild(base);
		}
	});

	document.body.appendChild(base);

	return {
		base : base,
		dialog : dialog,
		content : content,
		header : header,
		close_btn : close_btn,
		h4 : h4,
		body : body,
		close : () => {
			base.parentNode.removeChild(base);
		},
		show : () => {
			$(base).modal('show');
			try{
				runScripts(body);
			}catch(error){
				// console.error(error.msg);
			}
		},
		to_success : (_msg) => {
			let alert = document.createElement('div');
			alert.className = "alert alert-success";
			alert.innerHTML = _msg;

			let par = document.createElement('p');
			par.setAttribute('align', 'right');

			let btn = document.createElement('button');
			btn.className = "btn btn-default";
			btn.type = "button";
			btn.setAttribute('data-dismiss', 'modal');
			btn.addEventListener('click', () => { location.reload() }, true);
			btn.innerHTML = '<span aria-hidden="true">&times;</span> Close';

			par.appendChild(btn);

			body.innerHTML = '';
			body.appendChild(alert);
			body.appendChild(par);

		}, 
		to_error: (_msg) => {
			let alert = document.createElement('div');
			alert.className = "alert alert-danger";
			alert.innerHTML = _msg;
			
			let par = document.createElement('p');
			par.setAttribute('align','right');

			let btn = document.createElement('button');
			btn.className = "btn btn-default";
			btn.type = "button";
			btn.setAttribute('data-dismiss', 'modal');
			btn.addEventListener('click', () => { location.reload() }, true);
			btn.innerHTML = '<span class="fa fa-close"></span> Close';

			par.appendChild(btn);

			body.innerHTML = '';
			body.appendChild(alert);
			body.appendChild(par);
		}
	};
	
}
