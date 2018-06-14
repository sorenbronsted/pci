part of pci;

class JobResultDetailCtrl extends CrudController {
	static Uri activationUrl = Uri.parse('detail/${JobResult}');

	JobResultDetailCtrl(ViewBase view) : super(activationUrl, view);
}
