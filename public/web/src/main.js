const PciApp = require('./PciApp.js').PciApp;

window.onerror = (error) => {
	window.alert(error);
};
let app = new PciApp(XMLHttpRequest, FormData, window);
app.run('/list/Project');
