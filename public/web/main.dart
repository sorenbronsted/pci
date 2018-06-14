
library pci;

import 'dart:async';
import 'dart:html';
import 'package:webui/webui.dart';
import 'package:logging/logging.dart';
import 'package:intl/intl.dart';
import 'package:intl/date_symbol_data_local.dart';

// Model
part 'lib/model/Project.dart';
part 'lib/model/Job.dart';
part 'lib/model/JobResult.dart';

// Control
part 'lib/control/ProjectList.dart';
part 'lib/control/ProjectListView.dart';
part 'lib/control/ProjectDetail.dart';
part 'lib/control/JobList.dart';
part 'lib/control/JobDetail.dart';
part 'lib/control/JobResultList.dart';
part 'lib/control/JobResultListView.dart';
part 'lib/control/JobResultDetail.dart';

void main() {
  Intl.defaultLocale = 'da_DK';
  initializeDateFormatting();

  Table.css = new TableCssBootStrap();
  InputValidator.css = new InputValidatorListenerBootStrap();
  Anchor.css = new AnchorCssBootStrap();

  Logger.root.level = Level.FINE;
  Logger.root.onRecord.listen((LogRecord rec) {
    print('${rec.level.name}: ${rec.time}: ${rec.loggerName}: ${rec.message}');
  });

  // routing
  Repo repo = Repo.instance;
  repo.add(new Router());
  repo.add(new RouterCtrl(new RouterView()));

  // model
  repo.add(new CurrentViewState());
  repo.add(new Project());
  repo.add(new Job());
  repo.add(new JobResult());

  // controller
  repo.add(new ProjectListCtrl(new ProjectListView()));
  repo.add(new ProjectDetailCtrl(new View('ProjectDetail')));
  repo.add(new JobListCtrl(new View('JobList')));
  repo.add(new JobDetailCtrl(new View('JobDetail')));
  repo.add(new JobResultListCtrl(new JobResultListView()));
  repo.add(new JobResultDetailCtrl(new View('JobResultDetail')));

  // run
  Router router = repo.getByType(Router);
  router.goto(ProjectListCtrl.activationUrl);
}

