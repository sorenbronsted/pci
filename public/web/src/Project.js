const mvc = require('webui.js/lib/src/mvc');
const ui = require('webui.js/lib/src/ui');

class ProjectListCtrl extends mvc.ListController {
	constructor(model, view) {
		super('/list/'+Project.name, model, view);
	}
}
exports.ProjectListCtrl = ProjectListCtrl;

class ProjectListView extends ui.View {
	constructor(window, html, css) {
		super(window, Project.name+'ListView', html, css);
	}

	onTableCellLink(cell, link, cls, property, row) {
		if (link.href.match('JobResult')) {
			link.text = 'builds';
		}
		else if (link.href.match('Job')) {
			link.text = 'jobs';
		}
	}
}
exports.ProjectListView = ProjectListView;



class ProjectDetailCtrl extends mvc.DetailController {
	constructor(model, view) {
		super('/detail/'+Project.name, model, view);
	}
}
exports.ProjectDetailCtrl = ProjectDetailCtrl;

class Project extends mvc.CrudProxy {
	constructor(store) {
		super(store);
	}
}
exports.Project = Project;