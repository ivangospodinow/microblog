import { API_URL } from "../config";

export function splitStringToLines(str: string): string[] {
    return str.split(/\r?\n/);
}

export function stringsArrayToString(strs: string[]): string {
    return strs.join('\r\n');
}


export function clearEmptyObjects(o: any) {
    for (var k in o) {
        if (!o[k] || typeof o[k] !== "object") {
            continue // If null or not an object, skip to the next iteration
        }

        // The property is an object
        clearEmptyObjects(o[k]); // <-- Make a recursive call on the nested object
        if (Object.keys(o[k]).length === 0) {
            delete o[k]; // The object had no properties, so delete that property
        }
    }
    return o;
}

export function convertFileToBase64(file: File): Promise<string> {
    return new Promise((resolve: CallableFunction, reject: CallableFunction) => {
        var reader = new FileReader();
        reader.readAsDataURL(file);
        reader.onload = function () {
            resolve(reader.result);
        };
        reader.onerror = function (error) {
            reject('Error: ', error);
        };
    });
}

export function imagePath(src: string): string {
    if (!src) {
        src = '/img/placeholder.jpg';
    }

    if (src.substr(0, 10) === 'data:image') {
        return src;
    }

    return API_URL + src;
}