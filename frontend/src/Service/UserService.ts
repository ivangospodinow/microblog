import LocalStorage from "./Storage/LocalStorage";

export type UserData = {
    id: number,
    username: string,
};

export default class UserService {
    private data?: UserData;

    constructor(data: UserData) {
        this.data = data;
    }

    public login(data: UserData) {
        this.data = data;
        this.save();
    }

    public logout() {
        this.data = undefined;
        this.save();
    }

    public isLogged(): boolean {
        return this.data && undefined !== this.data.id ? true : false;
    }


    public getId(): number {
        if (this.data && this.data.id) {
            return this.data.id;
        }
        throw new Error('User id is not set');
    }

    public getUserName(): string {
        if (this.data && this.data.username) {
            return this.data.username;
        }
        return 'unknown';
    }

    public save() {
        LocalStorage.setObject('user', this.data || {});
    }
}
