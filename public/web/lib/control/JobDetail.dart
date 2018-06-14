part of pci;

class JobDetailCtrl extends CrudController {
	static Uri activationUrl = Uri.parse('detail/${Job}');

	JobDetailCtrl(ViewBase view) : super(activationUrl, view);
}
