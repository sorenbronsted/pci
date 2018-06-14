part of pci;

class JobResultListCtrl extends CrudController {
	static Uri activationUrl = Uri.parse('list/${JobResult}');

	JobResultListCtrl(ViewBase view) : super(activationUrl, view) {
		addEventHandler('home', _home);
		addEventHandler('build', _build);
	}

	_home(Type sender, Object value) {
		Router router = Repo.instance.getByType(Router);
		router.goto(Uri.parse('list/${Project}'));
	}

  _build(Type sender, Object value) {
		Router router = Repo.instance.getByType(Router);
		var projectUid = router.uri.queryParameters['project_uid'];

		JobResult jobResult = Repo.instance.getByType(JobResult);
		jobResult.build(projectUid);
  }
}
