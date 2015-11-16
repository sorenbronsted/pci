
part of pci;

class JobResultListCtrl extends DefaultListCtrl {

  JobResultListCtrl() : super(new JobResultListView(), "JobResult") {
    view.addHandler("build", build);
  }
  
  void load() {
    var params = Address.instance.parameters;
    Rest.instance.get('/rest/JobResult?method=getByProjectUid&projectUid=${params['Project']}').then((data) {
      (view as JobResultListView).jobResults = data;
    });
  }

  build(String empty) {
    var params = Address.instance.parameters;
    Rest.instance.get('/rest/Project/${params['Project']}?method=buildInBackground&user=system').then((_) {});
  }
}