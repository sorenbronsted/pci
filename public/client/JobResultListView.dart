
part of pci;

class JobResultListView extends DefaultListView {

  void registerBindings() {
    super.registerBindings();
    addBinding(new UiButtonBinding('#build', false));
  }

  void set jobResults(jobs) => populate(jobs);

  onTableRow(TableRowElement tableRow, Map row) {
    tableRow.classes.clear();
    if (row['jobstate_uid'] == null) {
      return;
    }
    if (row['jobstate_uid'] == 3) {
      tableRow.classes.add('bg-danger');
    }
  }
}
