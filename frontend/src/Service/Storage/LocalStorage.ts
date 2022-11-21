const storage = window.localStorage;

const DATA_KEY_PREFIX = '_mb_';

class LocalStorage {

    getString(key: string): string {
        return storage.getItem(DATA_KEY_PREFIX + key) || '';
    }

    setString = (key: string, value: string) => {
        storage.setItem(DATA_KEY_PREFIX + key, value);
    }

    setObject = (key: string, value: object) => {
        storage.setItem(DATA_KEY_PREFIX + key, JSON.stringify(value));
    }

    getObject = (key: string) => {
        const data = storage.getItem(DATA_KEY_PREFIX + key);
        if (null !== data) {
            return JSON.parse(data);
        }
        return {};
    }
}

export default new LocalStorage();