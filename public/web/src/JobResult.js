const collect = require('collect.js');
const mvc = require('webui.js/lib/src/mvc');
const ui = require('webui.js/lib/src/ui');

class JobResultListCtrl extends mvc.ListController {
	constructor(model, view) {
		super('/list/'+JobResult.name, model, view);
	}

	populateStateMachine() {
		super.populateStateMachine();
		this.addTransition(new mvc.Transition(this.states.input, this.states.input, this.build, this.isEventBuild));
		this.addTransition(new mvc.Transition(this.states.input, this.states.start, this.home, this.isEventHome));
	}

	isEventHome(event) {
		return event.name === 'home';
	}

	home(event) {
		this.repo.get(mvc.Router.name).goto('/list/Project');
	}

	isEventBuild(event) {
		return event.name === 'build';
	}

	build(event) {
		this.repo.get(JobResult.name).build();
	}
}
exports.JobResultListCtrl = JobResultListCtrl;

class JobResultDetailCtrl extends mvc.DetailController {
	constructor(model, view) {
		super('/detail/'+JobResult.name, model, view);
	}
}
exports.JobResultDetailCtrl = JobResultDetailCtrl;

class JobResult extends mvc.CrudProxy {
	constructor(store) {
		super(store);
		this.project_uid = null;
	}

	build() {
		this._store.read('Project', collect({uid:this.project_uid, user:'ui'}), 'buildInBackground');
	}
}
exports.JobResult = JobResult;