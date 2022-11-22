import { clearEmptyObjects } from "../Tools/Functions";
import UserService from "./UserService";

export type BlogUser = {
    id: number,
    username: string,
    password?: string,
    token?: string,
};
export type BlogUsers = BlogUser[] | undefined;

export type BlogPost = {
    id: number,
    createdBy: number,
    title: string,
    content: string,
    createdAt: string,
    image: string,
    updatedAt: string,
    createdByUser: BlogUser,
    featured: string,
};

export type BlogPosts = BlogPost[] | undefined;

export type BlogMonth = {
    month: string,
    count: string,
};

export type BlogMonths = BlogMonth[] | undefined;

export type BlogPostsListParams = {
    list?: {
        limit?: number,
        page?: number,
    },
    filter?: {
        featured?: boolean,
        archive?: string,
        postId?: string,
    },
};

export type BlogUsersListParams = {
    list?: {
        limit?: number,
        page?: number,
    },
};

export type ApiError = {
    property: string,
    message: string,
};

export type ApiErrors = ApiError[] | undefined;

export type UserLogin = {
    user?: BlogUser,
    errors?: ApiError[],
};

export type UserSave = {
    id?: number,
    errors?: ApiError[],
};

export type UsersResult = {
    list: BlogUsers,
    errors?: ApiError[],
};

export type PostSave = {
    id?: number,
    errors?: ApiError[],
};

export type PostsResult = {
    list: BlogPosts,
    errors?: ApiError[],
};



export default class DataService {
    private uri;
    private userService;
    constructor(uri: string, userService: UserService) {
        this.uri = uri;
        this.userService = userService;
    }

    public fetch(url: string, params: any = undefined) {
        // url += '&version=dev';
        params = params || {};
        params['mode'] = 'cors';
        params['headers'] = {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
        };
        if (this.userService.isLogged()) {
            params['headers']['Authorization'] = this.userService.getToken();
        }
        return fetch(url, params);
    }

    public getPosts(params: BlogPostsListParams): Promise<PostsResult> {
        return new Promise((resolve: CallableFunction, reject: CallableFunction) => {
            this.fetch(this.uri + '/api/posts?json=' + encodeURIComponent(JSON.stringify(clearEmptyObjects(params))))
                .then(res => res.json())
                .then(
                    (result) => {
                        resolve(result);
                    },
                    // Note: it's important to handle errors here
                    // instead of a catch() block so that we don't swallow
                    // exceptions from actual bugs in components.
                    (error) => {
                        console.error(error);
                        resolve(undefined);
                    }
                )
        });
    }

    public savePost(data: BlogPost): Promise<PostSave> {
        return new Promise((resolve: CallableFunction, reject: CallableFunction) => {
            this.fetch(this.uri + '/api/posts' + (undefined !== data.id ? '/' + data.id : ''), {
                method: undefined === data.id ? 'POST' : 'PUT',
                body: JSON.stringify(data)
            })
                .then(res => res.json())
                .then(
                    (result) => {
                        resolve(result);
                    },
                    // Note: it's important to handle errors here
                    // instead of a catch() block so that we don't swallow
                    // exceptions from actual bugs in components.
                    (error) => {
                        console.error(error);
                        resolve(undefined);
                    }
                )
        });
    }

    public deletePost(id: number): Promise<PostSave> {
        return new Promise((resolve: CallableFunction, reject: CallableFunction) => {
            this.fetch(this.uri + '/api/posts/' + id, {
                method: 'DELETE',
            })
                .then(res => res.json())
                .then(
                    (result) => {
                        resolve(result);
                    },
                    // Note: it's important to handle errors here
                    // instead of a catch() block so that we don't swallow
                    // exceptions from actual bugs in components.
                    (error) => {
                        console.error(error);
                        resolve(undefined);
                    }
                )
        });
    }

    public getMonths(): Promise<BlogMonths> {
        return new Promise((resolve: CallableFunction, reject: CallableFunction) => {
            this.fetch(this.uri + '/api/posts/months')
                .then(res => res.json())
                .then(
                    (result) => {
                        resolve(result.list || []);
                    },
                    // Note: it's important to handle errors here
                    // instead of a catch() block so that we don't swallow
                    // exceptions from actual bugs in components.
                    (error) => {
                        console.error(error);
                        resolve(undefined);
                    }
                )
        });
    }


    public login(data: { username: FormDataEntryValue | null, password: FormDataEntryValue | null }): Promise<UserLogin> {
        return new Promise((resolve: CallableFunction, reject: CallableFunction) => {
            this.fetch(this.uri + '/api/user/login', {
                method: 'POST',
                body: JSON.stringify(data)
            })
                .then(res => res.json())
                .then(
                    (result) => {
                        if (undefined !== result['success']) {
                            resolve(result);
                        }

                    },
                    // Note: it's important to handle errors here
                    // instead of a catch() block so that we don't swallow
                    // exceptions from actual bugs in components.
                    (error) => {
                        console.error(error);
                        resolve(false);
                    }
                )
        });
    }

    public getUsers(params: BlogUsersListParams): Promise<UsersResult> {
        return new Promise((resolve: CallableFunction, reject: CallableFunction) => {
            this.fetch(this.uri + '/api/users?json=' + encodeURIComponent(JSON.stringify(clearEmptyObjects(params))))
                .then(res => res.json())
                .then(
                    (result) => {
                        resolve(result);
                    },
                    // Note: it's important to handle errors here
                    // instead of a catch() block so that we don't swallow
                    // exceptions from actual bugs in components.
                    (error) => {
                        console.error(error);
                        resolve(undefined);
                    }
                )
        });
    }

    public saveUser(data: BlogUser): Promise<UserSave> {
        return new Promise((resolve: CallableFunction, reject: CallableFunction) => {
            this.fetch(this.uri + '/api/users' + (undefined !== data.id ? '/' + data.id : ''), {
                method: undefined === data.id ? 'POST' : 'PUT',
                body: JSON.stringify(data)
            })
                .then(res => res.json())
                .then(
                    (result) => {
                        resolve(result);
                    },
                    // Note: it's important to handle errors here
                    // instead of a catch() block so that we don't swallow
                    // exceptions from actual bugs in components.
                    (error) => {
                        console.error(error);
                        resolve(undefined);
                    }
                )
        });
    }

    public deleteUser(id: number): Promise<UserSave> {
        return new Promise((resolve: CallableFunction, reject: CallableFunction) => {
            this.fetch(this.uri + '/api/users/' + id, {
                method: 'DELETE',
            })
                .then(res => res.json())
                .then(
                    (result) => {
                        resolve(result);
                    },
                    // Note: it's important to handle errors here
                    // instead of a catch() block so that we don't swallow
                    // exceptions from actual bugs in components.
                    (error) => {
                        console.error(error);
                        resolve(undefined);
                    }
                )
        });
    }

}
