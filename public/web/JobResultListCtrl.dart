
part of pci;

class JobResultListCtrl extends DefaultListCtrl {

  JobResultListCtrl() : super(new JobResultListView(), "JobResult") {
    view.addHandler("build", build);
  }

  void register(EventBus eventBus) {
    super.register(eventBus);
    eventBus.listenOn(JobListener.eventJobStateChange, stateChange);
  }

  void stateChange(String event) {
    if (canRun()) {
      load();
    }
  }

  void load() {
    var params = Address.instance.parameters;
    Rest.instance.get('/rest/JobResult?method=getByProjectUid&projectUid=${params['Project']}').then((data) {
      (view as JobResultListView).jobResults = data;
    });
  }

  build(String empty) {
    var params = Address.instance.parameters;
    Rest.instance.get('/rest/Project/${params['Project']}?method=buildInBackground&user=ui').then((_) {});
  }
}