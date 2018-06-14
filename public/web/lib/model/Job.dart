part of pci;

class Job extends Proxy {
	int _selectedProjectUid;

	Router get router => Repo.instance.getByType(Router);

	@override
	void read([int uid]) {
		if (uid == null) {
			Map params = router.uri.queryParameters;
			_selectedProjectUid = int.parse(params['project_uid']);
			if (_selectedProjectUid == 0) {
				return;
			}
		}
		super.read(uid);
	}

	@override
	void create() {
		super.create();
		var data = getData(0);
		data.set('project_uid', _selectedProjectUid);
	}

	@override
	String getReadByParameters() => '?project_uid=${_selectedProjectUid}&orderby=sequence&order=asc';
}