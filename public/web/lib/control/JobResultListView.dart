part of pci;

class JobResultListView extends View implements TableListener {

  JobResultListView() : super('JobResultList');

  onTableRow(TableRowElement tableRow, DataClass object) {
    tableRow.classes.clear();
    if (object.get('jobstate_uid') == null) {
      return;
    }
    if (object.get('jobstate_uid') == 3) {
      tableRow.classes.add('bg-danger');
    }
  }

  onTableCellValue(TableCellElement cell, String column, String property, DataClass object) {
    // Ignore
  }

  @override
  onTableCellLink(TableCellElement cell, AnchorElement link, String cls, String property, DataClass row) {
    // Ignore
  }
}
