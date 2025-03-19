import $ from 'jquery';
import { GorestBackend } from './GorestBackend';
import { GorestResponseHandler } from './GorestResponseHandler';
import { HtmlHandler } from './HtmlHandler';

export class InitialHtmlMounter {
    static mount() {
        $("#btn-reload").click(() => {
            HtmlHandler.reload();
        });

        $("#btn-local").click(() => {
            InitialHtmlMounter.switchToLocalMode();
        });
        
        $("#btn-api").click(() => {
            InitialHtmlMounter.switchToApiMode();
        });

        $("#btn-sync").click(() => {
            GorestBackend.sync((response) => {
                HtmlHandler.flash(response.success, response.message);
                if (response.success) {
                    HtmlHandler.reload();
                }
            });
        });

        $("#btn-create").click(() => {
            $("#loading").removeClass("d-none");
            GorestBackend.getCreateForm((response) => {
                if (response.success) {
                    HtmlHandler.mountCreateForm(response.form);
                } else {
                    HtmlHandler.flash(response.success, response.message);
                }
            });
        });

        $("#btn-find").click(() => {
            $("#loading").removeClass("d-none");
            GorestBackend.getFindForm((response) => {
                if (response.success) {
                    HtmlHandler.mountFindForm(response.form);
                } else {
                    HtmlHandler.flash(response.success, response.message);
                }
            });
        });
    }

    static switchToApiMode() {
        $("#app-container").attr("data-mode", "api");
        $("#btn-sync").addClass("d-none");
        $("#btn-local").removeClass("d-none");
        $("#btn-api").addClass("d-none");
        $("#mode-span").text("API");
        HtmlHandler.reload();
    }

    static switchToLocalMode() {
        $("#app-container").attr("data-mode", "local");
        $("#btn-sync").removeClass("d-none");
        $("#btn-local").addClass("d-none");
        $("#btn-api").removeClass("d-none");
        $("#mode-span").text("LOCAL");
        HtmlHandler.reload();
    }
}
