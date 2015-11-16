
part of pci;

class ProjectListCtrl extends DefaultListCtrl {

  ProjectListCtrl() : super(new ProjectListView(), "Project");


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
    var parts = Address.instance.pathParts;
    if (parts.last == 'build') {
      Rest.instance.get('/rest/Project/${parts[2]}?method=buildInBackground&user=ui').then((_) {
        Address.instance.back();
      });
    }
    else {
      super.load();
    }
  }
}