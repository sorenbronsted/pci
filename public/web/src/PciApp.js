const mvc = require('webui.js/lib/src/mvc');
const ui = require('webui.js/lib/src/ui');
const app = require('./App.js');

class PciApp extends mvc.App {
	constructor(httpCtor, formCtor, window) {
		super();
		this._httpCtor = httpCtor;
		this._formCtor = formCtor;
		this._window = window;
	}

	async _init() {
		let rest   = new mvc.Rest(this._httpCtor);
		let store  = new mvc.RestStore(rest, this._formCtor);
		let css    = new ui.CssDelegate(new ui.InputCssW3(), new ui.AnchorCssW3(), new ui.TableCssW3(this._window.document), new ui.ViewCssW3());
		let router = new mvc.Router(this._window);

		// Add elements to repo
		let repo = new mvc.Repo();
		repo.add(router);
		repo.add(new mvc.CurrentViewState());
		repo.add(new app.Project(store));
		repo.add(new app.Job(store));
		repo.add(new app.JobResult(store));

		// Load views and add controllers
		await rest.get('/web/html/ProjectList.html').then(html => {
			repo.add(new app.ProjectListCtrl(repo, new app.ProjectListView(this._window, html, css)));
		});

		await rest.get(`/web/html/ProjectDetail.html`).then(html => {
			repo.add(new app.ProjectDetailCtrl(repo, new ui.View(this._window, app.Project.name+'DetailView', html, css)));
		});

		await rest.get('/web/html/JobList.html').then(html => {
			repo.add(new app.JobListCtrl(repo, new ui.View(this._window, app.Job.name+'ListView', html, css)));
		});

		await rest.get(`/web/html/JobDetail.html`).then(html => {
			repo.add(new app.JobDetailCtrl(repo, new ui.View(this._window, app.Job.name+'DetailView', html, css)));
		});

		await rest.get('/web/html/JobResultList.html').then(html => {
			repo.add(new app.JobResultListCtrl(repo, new ui.View(this._window, app.JobResult.name+'ListView', html, css)));
		});

		await rest.get(`/web/html/JobResultDetail.html`).then(html => {
			repo.add(new app.JobResultDetailCtrl(repo, new ui.View(this._window, app.JobResult.name+'DetailView', html, css)));
		});

		return router;
	}
}
exports.PciApp = PciApp;