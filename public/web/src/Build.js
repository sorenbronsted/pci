const mvc = require('webui.js/lib/src/mvc');
const ui = require('webui.js/lib/src/ui');

class BuildListCtrl extends mvc.ListController {
	constructor(model, view) {
		super('/list/'+Build.name, model, view);
	}
}
exports.BuildListCtrl = BuildListCtrl;

class BuildListView extends ui.View {
	constructor(window, html, css) {
		super(window, Build.name+'ListView', html, css);
	}

	onTableRow(tr, row) {
		switch (row.state) {
			case 2:
				tr.classList.add('w3-pale-green');
				break;
			case 3:
				tr.classList.add('w3-pale-red');
				break;
		}
	}
}
exports.BuildListView = BuildListView;



class BuildDetailCtrl extends mvc.DetailController {
	constructor(model, view) {
		super('/detail/'+Build.name, model, view);
	}
}
exports.BuildDetailCtrl = BuildDetailCtrl;

class Build extends mvc.CrudProxy {
	constructor(store) {
		super(store);
	}
}
exports.Build = Build;