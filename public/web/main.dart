
library pci;

import 'dart:async';
import 'dart:html';
import 'package:webui/webui.dart';

part 'ProjectListView.dart';
part 'ProjectListCtrl.dart';
part 'ProjectDetailView.dart';
part 'ProjectDetailCtrl.dart';
part 'JobListCtrl.dart';
part 'JobListView.dart';
part 'JobDetailCtrl.dart';
part 'JobDetailView.dart';
part 'JobResultListCtrl.dart';
part 'JobResultListView.dart';
part 'JobResultDetailCtrl.dart';
part 'JobResultDetailView.dart';
part 'JobListener.dart';

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

