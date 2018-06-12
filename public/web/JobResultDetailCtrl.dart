part of pci;

class JobResultDetailCtrl extends DefaultDetailCtrl {

  JobResultDetailCtrl() : super(new JobResultDetailView(), "JobResult") {
    view.addHandler('back', back);
  }

  void back(String notused) {
    Address.instance.back();
  }
}