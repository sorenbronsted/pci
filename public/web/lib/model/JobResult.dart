part of pci;

class JobResult extends Proxy {

	void build(int uid) {
		Rest.instance.get('/rest/Project/${uid}/buildInBackground?user=ui').then((_) {
			Duration d = new Duration(seconds: 5);
			Timer timer = new Timer.periodic(d, _run);
		});
	}

	void _run(Timer timer) {
		Rest.instance.get('/rest/Project/isBuilding').then((data) {
			read();
			if (data['isBuilding'] == false) {
				timer.cancel();
			}
		});
	}

	@override
	String getReadByParameters() {
		Map params = Repo.instance.getByType(Router).uri.queryParameters;
		return '/getByProjectUid?projectUid=${int.parse(params['project_uid'])}';
	}
}