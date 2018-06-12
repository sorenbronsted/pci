part of pci;

class JobDetailCtrl extends DefaultDetailCtrl {

  JobDetailCtrl() : super(new JobDetailView(), "Job");

  void postLoad() {
    var parts = Address.instance.pathParts;
    if (parts.last == 'new') {
      var params = Address.instance.parameters;
      (view as JobDetailView).formdata = {
        'class' : 'Job',
        'project_uid': params['Project']
      };
    }
  }

}