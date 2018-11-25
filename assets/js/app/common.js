
function build_url(_path) {

	return document.getElementById('document_base_tag').href + _path;
}
function url_encode_object(_data) {
	//Thanks https://stackoverflow.com/questions/1714786/query-string-encoding-of-a-javascript-object
	if (null === _data) {
		return null;
	}
	return Object.keys(_data).reduce(function (a, k) { a.push(k + '=' + encodeURIComponent(_data[k])); return a }, []).join('&')
}
function collection_to_array(_col) {
	return Array.prototype.slice.call(_col, 0);
}

Array.prototype.clone = function () {
	return this.slice(0);
};