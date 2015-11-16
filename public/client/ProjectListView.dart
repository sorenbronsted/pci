
part of pci;

class ProjectListView extends DefaultListView {
  void set projects(projects) => populate(projects);

  onTableRow(TableRowElement tableRow, Map row) {
    tableRow.classes.clear();
    if (row['jobstate_uid'] == null) {
      return;
    }
    if (row['jobstate_uid'] == 3) {
      tableRow.classes.add('bg-danger');
    }
  }

  onTableCellValue(TableCellElement cell, String column, Map row) {
    if (column == 'Project-build') {
      var a = new AnchorElement();
      a.classes.addAll(['btn', 'btn-primary', 'btn-xs']);
      a.href = "/#list/Project/${row['uid']}/build";
      a.text = 'Build';
      cell.append(a);
    }
  }
}
