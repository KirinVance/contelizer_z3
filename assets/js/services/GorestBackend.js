import $ from 'jquery';

export class GorestBackend {
    static fetch(route, data, responseFunction) {
        const url = $("#app-container").attr('data-local-url');
        let fullRoute = `${url}/gorest/${route}`;

        fetch(fullRoute, {
            headers: {"Content-Type": "application/json"},
            method: 'POST',
            body: JSON.stringify(data),
        })
        .then(response => response.json())
        .then((response) => {
            responseFunction(response);
        })
        .catch(error => console.error("Error: ", error));

        return true;
    }

    static index(responseFunction) {
        const mode = $("#app-container").attr("data-mode");
        return this.fetch(`${mode}/`, {}, responseFunction);
    }

    static find({name, email}, responseFunction) {
        const mode = $("#app-container").attr("data-mode");
        return this.fetch(`${mode}/find`, {name, email}, responseFunction);
    }

    static create({name, email, gender, status, _token}, responseFunction) {
        const mode = $("#app-container").attr("data-mode");
        return this.fetch(`${mode}/create`, {name, email, gender, status, _token}, responseFunction);
    }

    static update({id, gorestId, name, email, gender, status, _token}, responseFunction) {
        const mode = $("#app-container").attr("data-mode");
        return this.fetch(`${mode}/update`, {id, gorestId, name, email, gender, status, _token}, responseFunction);
    }

    static delete({id, gorestId, _token}, responseFunction) {
        const mode = $("#app-container").attr("data-mode");
        return this.fetch(`${mode}/delete`, {id, gorestId, _token}, responseFunction);
    }

    static sync(responseFunction) {
        return this.fetch(`sync`, {}, responseFunction);
    }

    static getFindForm(responseFunction) {
        const mode = $("#app-container").attr("data-mode");
        return this.fetch(`${mode}/find-form`, {}, responseFunction);
    }

    static getCreateForm(responseFunction) {
        return this.fetch(`create-form`, {}, responseFunction);
    }

    static getUpdateForm({id, gorestId}, responseFunction) {
        const mode = $("#app-container").attr("data-mode");
        return this.fetch(`${mode}/update-form`, {id, gorestId}, responseFunction);
    }

    static getDeleteForm({id, gorestId}, responseFunction) {
        const mode = $("#app-container").attr("data-mode");
        return this.fetch(`${mode}/delete-form`, {id, gorestId}, responseFunction);
    }
}
