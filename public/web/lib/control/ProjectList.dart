part of pci;

class ProjectListCtrl extends CrudController {
	static Uri activationUrl = Uri.parse('list/${Project}');

	ProjectListCtrl(ViewBase view) : super(activationUrl, view);
}
