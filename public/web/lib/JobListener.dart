part of pci;

class JobListener implements EventBusListener {

  static const eventJobStart = "JobStart";
  static const eventJobStop = "JobStop";
  static const eventJobStateChange = "JobStateChange";

  Timer _timer;
  bool _isBuilding = false;

  @override
  void register(EventBus eventBus) {
    eventBus.listenOn(eventJobStart, start);
    eventBus.listenOn(eventJobStop, stop);
  }

  void start(String event) {
    Duration d = new Duration(seconds: 5);
    _timer = new Timer.periodic(d, _run);
  }

  void stop(String event) {
    if (_timer == null){
      return;
    }
    _timer.cancel();
  }

  void _run(Timer timer) {
    Rest.instance.get('/rest/Project?method=isBuilding').then((data) {
      print("JobListener: ${data}");
      if (data == null) {
        return;
      }
      var current = data['isBuilding'];
      if (current != _isBuilding) {
        _isBuilding = current;
        EventBus.instance.fire(eventJobStateChange);
      }
    });
  }
}
