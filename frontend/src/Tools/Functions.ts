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