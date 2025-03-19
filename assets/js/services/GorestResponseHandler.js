import { HtmlHandler } from './HtmlHandler';

export class GorestResponseHandler {
    static handleGetUpdateForm(response) {
        if (response.success) {
            HtmlHandler.mountUpdateForm(response.form);
        } else {
            HtmlHandler.flash(response.success, response.message);
        }
    }

    static handleGetDeleteForm(response) {
        if (response.success) {
            HtmlHandler.mountDeleteForm(response.form);
        } else {
            HtmlHandler.flash(response.success, response.message);
        }
    }
}
