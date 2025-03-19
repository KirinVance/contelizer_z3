export class GorestToolkit {
    static fixIdsIfApiUser(user) {
        if (user.gorestId === undefined) {
            user.gorestId = user.id;
            user.id = 0;
        }
    }

    static getUserFromForm(form) {
        const user = {};
        const formData = form.serializeArray();

        formData.forEach(({ name, value }) => {
            let match = name.match(/^gorest_user\[(.*?)\]$/);
            if (match) {
                user[match[1]] = value;
            }
        });

        return user;
    }

    static getFindDataFromForm(form) {
        const findData = {};
        const formData = form.serializeArray();

        formData.forEach(({ name, value }) => {
            let match = name.match(/^find_gorest_user\[(.*?)\]$/);
            if (match) {
                findData[match[1]] = value;
            }
        });

        return findData;
    }
}
