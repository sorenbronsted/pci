const mvc = require('webui.js/lib/src/mvc');
const ui = require('webui.js/lib/src/ui');
const ServerEvent = require('./ServerEvent.js').ServerEvent;

class BuildListCtrl extends mvc.ListController {
	constructor(model, view) {
		super('/list/'+Build.name, model, view);
		model.get(ServerEvent.name).addEventListener(this);
	}

	populateStateMachine() {
		super.populateStateMachine();
		this.addTransition(new mvc.Transition(this.states.input, this.states.find, this.reload, this.isEventServer));
	}

	isEventServer(event) {
		let subject = this.repo.get(ServerEvent.name);
		return event.sender === ServerEvent.name && event.name === subject.eventMesssageOk;
	}

	reload(event) {
		this.repo.get(Build.name).read();
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
		model.get(ServerEvent.name).addEventListener(this);
	}
}
exports.BuildDetailCtrl = BuildDetailCtrl;

class Build extends mvc.CrudProxy {
	constructor(store) {
		super(store);
	}
}
exports.Build = Build;