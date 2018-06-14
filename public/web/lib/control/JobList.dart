part of pci;

class JobListCtrl extends CrudController {
	static Uri activationUrl = Uri.parse('list/${Job}');

	JobListCtrl(ViewBase view) : super(activationUrl, view) {
		addEventHandler('home', _home);
	}

  _home(Type sender, Object value) {
		Router router = Repo.instance.getByType(Router);
		router.goto(Uri.parse('list/${Project}'));
  }
}
