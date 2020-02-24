const mvc = require('webui.js/lib/src/mvc');

class ServerEvent extends mvc.Subject {
	constructor() {
		super();
		this.eventMesssageOk = `messageOk`;
		this.eventMessageFail = `messageFail`;
		this.evtSource = null;
	}

	run() {
		this.evtSource = new EventSource('/event.php');
		//console.log(this.evtSource.withCredentials);
		//console.log(this.evtSource.readyState);
		//console.log(this.evtSource.url);
		this.evtSource.onopen = this._onOpen.bind(this);
		this.evtSource.onmessage = this._onMessage.bind(this);
		this.evtSource.onerror = this._onError.bind(this);
	}

	_onOpen(e) {
		//console.log("onopen");
	}

	_onMessage(e) {
		this.fire(new mvc.Event(ServerEvent.name, this.eventMesssageOk, e.data));
	}

	_onError(e) {
		this.fire(new mvc.Event(ServerEvent.name, this.eventMesssageFail));
	}
}
exports.ServerEvent = ServerEvent;