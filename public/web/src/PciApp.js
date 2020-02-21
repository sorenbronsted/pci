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
		repo.add(new app.Build(store));

		// Load views and add controllers
		await rest.get('/web/html/BuildList.html').then(html => {
			repo.add(new app.BuildListCtrl(repo, new app.BuildListView(this._window, html, css)));
		});

		await rest.get(`/web/html/BuildDetail.html`).then(html => {
			repo.add(new app.BuildDetailCtrl(repo, new ui.View(this._window, app.Build.name+'DetailView', html, css)));
		});

		return router;
	}
}
exports.PciApp = PciApp;