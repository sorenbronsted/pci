const mvc = require('webui.js/lib/src/mvc');
const ui = require('webui.js/lib/src/ui');

class JobListCtrl extends mvc.ListController {
	constructor(model, view) {
		super('/list/' + Job.name, model, view);
	}

	populateStateMachine() {
		super.populateStateMachine();
		this.addTransition(new mvc.Transition(this.states.input, this.states.start, this.home, this.isEventHome));
	}

	isEventHome(event) {
		return event.name === 'home';
	}

	home(event) {
		this.repo.get(mvc.Router.name).goto('/list/Project');
	}
}
exports.JobListCtrl = JobListCtrl;

class JobDetailCtrl extends mvc.DetailController {
	constructor(model, view) {
		super('/detail/' + Job.name, model, view);
	}
}

exports.JobDetailCtrl = JobDetailCtrl;

class Job extends mvc.CrudProxy {
	constructor(store) {
		super(store);
		this.project_uid = null;
	}

	create() {
		const uid = 0;
		this.add({
			uid: uid,
			project_uid: this.project_uid,
			class: Job.name,
		});
		this.fire(new mvc.Event(this.cls, this.eventOk, this.get(uid)));
	}
}
exports.Job = Job;