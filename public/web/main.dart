
library pci;

import 'dart:async';
import 'dart:html';
import 'package:webui/webui.dart';

part 'lib/ProjectListView.dart';
part 'lib/ProjectListCtrl.dart';
part 'lib/ProjectDetailView.dart';
part 'lib/ProjectDetailCtrl.dart';
part 'lib/JobListCtrl.dart';
part 'lib/JobListView.dart';
part 'lib/JobDetailCtrl.dart';
part 'lib/JobDetailView.dart';
part 'lib/JobResultListCtrl.dart';
part 'lib/JobResultListView.dart';
part 'lib/JobResultDetailCtrl.dart';
part 'lib/JobResultDetailView.dart';
part 'lib/JobListener.dart';

void main() {
  var bus = EventBus.instance;
  bus.register(new ProjectListCtrl());
  bus.register(new ProjectDetailCtrl());
  bus.register(new JobListCtrl());
  bus.register(new JobDetailCtrl());
  bus.register(new JobResultListCtrl());
  bus.register(new JobResultDetailCtrl());
  bus.register(new JobListener());
  UiInputValidator.css = new UiBootStrapInputValidatorListener();
  Address.instance.goto("list/Project");
  bus.fire(JobListener.eventJobStart);
}

