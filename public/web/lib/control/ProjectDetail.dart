part of pci;

class ProjectDetailCtrl extends CrudController {
	static Uri activationUrl = Uri.parse('detail/${Project}');

	ProjectDetailCtrl(ViewBase view) : super(activationUrl, view);
}
