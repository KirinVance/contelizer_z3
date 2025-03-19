import $ from 'jquery';
import { GorestBackend } from './GorestBackend.js';
import { GorestResponseHandler } from './GorestResponseHandler.js';
import { GorestToolkit } from './GorestToolkit.js';

export class HtmlHandler {
    static refreshUsersTable(users) {
        let tableHtml = '';
        users.forEach((user) => {
            GorestToolkit.fixIdsIfApiUser(user);
            tableHtml += HtmlHandler.generateUserHtml(user);
        }, this);

        $("tbody").html(tableHtml);

        HtmlHandler.mountUsersButtons();
    }

    static generateUserHtml(user) {
        return `
        <tr>
            <td>${user.id}</td>
            <td>${user.gorestId}</td>
            <td>${user.name}</td>
            <td>${user.email}</td>
            <td>${user.gender}</td>
            <td>${user.status}</td>
            <td>
                <button data-id="${user.id}" data-gorest-id="${user.gorestId}" class="update-user btn btn-success btn-sm mx-1">✏️</button>
                <button data-id="${user.id}" data-gorest-id="${user.gorestId}" class="delete-user btn btn-warning btn-sm mx-1">❌</button>
            </td>
        </tr>`;
    }

    static mountUsersButtons() {
        $(".update-user").each(function() {
            $(this).click(function() {
                $("#loading").removeClass("d-none");
                GorestBackend.getUpdateForm({
                    id: $(this).attr('data-id'),
                    gorestId: $(this).attr('data-gorest-id'),
                }, (response) => {
                    if (response.success) {
                        HtmlHandler.mountUpdateForm(response.form);
                    } else {
                        HtmlHandler.flash(response.success, response.message);
                    }
                });
            });
        });

        $(".delete-user").each(function() {
            $(this).click(function() {
                $("#loading").removeClass("d-none");
                GorestBackend.getDeleteForm({
                    id: $(this).attr('data-id'),
                    gorestId: $(this).attr('data-gorest-id'),
                }, (response) => {
                    if (response.success) {
                        HtmlHandler.mountDeleteForm(response.form);
                    } else {
                        HtmlHandler.flash(response.success, response.message);
                    }
                });
            });
        });
    }


    static mountFindForm(form) {
        $("#loading").addClass("d-none");
        $("#app-container").append(form);

        $("#close-form").click(() => {
            $("#form-overlay").remove();
        });

        $(".find-form").submit(function() {
            event.preventDefault();
            $("#loading").removeClass("d-none");

            const findData = GorestToolkit.getFindDataFromForm($(".find-form"));
            console.log(findData);

            GorestBackend.find(findData, (response) => {
                HtmlHandler.handleFindFormResponse(response);
            });
        });
    }

    static mountCreateForm(form) {
        $("#loading").addClass("d-none");
        $("#app-container").append(form);

        $("#close-form").click(() => {
            $("#form-overlay").remove();
        });

        $(".create-form").submit(function() {
            event.preventDefault();
            $("#loading").removeClass("d-none");

            const user = GorestToolkit.getUserFromForm($(".create-form"));

            GorestBackend.create(user, (response) => {
                HtmlHandler.handleFormResponse(response);
            });
        });
    }

    static mountUpdateForm(form) {
        $("#loading").addClass("d-none");
        $("#app-container").append(form);

        $("#close-form").click(() => {
            $("#form-overlay").remove();
        });

        $(".update-form").submit(function() {
            event.preventDefault();
            $("#loading").removeClass("d-none");

            const user = GorestToolkit.getUserFromForm($(".update-form"));

            GorestBackend.update(user, (response) => {
                HtmlHandler.handleFormResponse(response);
            });
        });
    }

    static mountDeleteForm(form) {
        $("#loading").addClass("d-none");
        $("#app-container").append(form);

        $("#close-form").click(() => {
            $("#form-overlay").remove();
        });

        $(".delete-form").submit(function() {
            event.preventDefault();
            $("#loading").removeClass("d-none");

            const user = GorestToolkit.getUserFromForm($(".delete-form"));

            GorestBackend.delete(user, (response) => {
                HtmlHandler.handleFormResponse(response);
            });
        });
    }

    static handleFormResponse(response) {
        $("#loading").addClass("d-none");
        HtmlHandler.flash(response.success, response.message);

        if (!response.success) {
            return;
        }

        $("#form-overlay").remove();
        HtmlHandler.reload();
    }

    static handleFindFormResponse(response) {
        $("#loading").addClass("d-none");
        HtmlHandler.flash(response.success, response.message);

        if (!response.success) {
            return;
        }

        $("#form-overlay").remove();
        HtmlHandler.refreshUsersTable(response.users);
    }

    static reload() {
        GorestBackend.index((response) => {
            if (!response.success) {
                return HtmlHandler.flash(response.message);
            }
    
            HtmlHandler.refreshUsersTable(response.users);
        });
    }

    static flash(success, message) {
        let flashMessage = $("#flash-message");

        flashMessage.removeClass("alert-success alert-danger d-none")
            .addClass(success ? "alert-success" : "alert-danger")
            .text(message)
            .fadeIn();

        setTimeout(() => {
            flashMessage.fadeOut();
        }, 3000);
    }
}
