export default class User {
    constructor(attributes = {}) {
        Object.assign(this, attributes);
    }

    isProfileCompleted() {
        return this.company_id && this.roles;
    }

    is(user) {
        return this.id === user.id;
    }
}
