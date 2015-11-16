part of pci;

class JobResultDetailView extends DefaultDetailView {

  void registerBindings() {
    super.registerBindings();
    addBinding(new UiButtonBinding('button[name="back"]', false));
  }
}

