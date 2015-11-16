
part of pci;

class JobListCtrl extends DefaultListCtrl {

  JobListCtrl() : super(new JobListView(), "Job");
  
  void load() {
    var params = Address.instance.parameters;
    Rest.instance.get('/rest/Job?project_uid=${params['Project']}&orderby=sequence&order=asc').then((data) {
      (view as JobListView).jobs = data;
    });
  }
}