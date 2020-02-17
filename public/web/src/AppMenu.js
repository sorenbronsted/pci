const m = require('webui.js/lib/src/menu');
const mvc = require('webui.js/lib/src/mvc');

class AppMenuCtrl extends m.MenuCtrl {
	constructor(repo, view) {
		super(repo.get(mvc.Router.name), new AppMenu(), view, repo.get(mvc.CurrentViewState.name));
	}

	handleEvent(event) {
		if (event.body != null && event.body.uid != null && event.body.name != null) {
			this._view.populate(event.sender, event.body);
		}
		else {
			super.handleEvent(event);
		}
	}
}
exports.AppMenuCtrl = AppMenuCtrl;

class AppMenu extends m.MenuProxy {

	_populate(root) {
		/* The uid's must match anchors data-uid in html file
		let data = new m.Menu(1,'/list/PensionRecipient');
		root.push(data);
		data.push(new m.Menu(10, '/list/PensionRecipient'));
		data.push(new m.Menu(11, '/detail/PensionRecipient', 'uid'));
		data.push(new m.Menu(12, '/list/PensionEvent', 'pensionrecipient_uid'));
		data.push(new m.Menu(13, '/list/PensionPostpayment', 'pensionrecipient_uid'));
		data.push(new m.Menu(14, '/list/CivilServantRefund', 'pensionrecipient_uid'));

		data = new m.Menu(2,'/list/Payroll');
		root.push(data);
		data.push(new m.Menu(20, '/list/Payroll'));

		data = new m.Menu(3, '/list/ImportLog');
		root.push(data);
		data.push(new m.Menu(30, '/list/ImportLog'));

		 */
	}
}
exports.AppMenu = AppMenu;