import {ErrorResponse, Violation} from "../Api/callEndpoint";
import Cookies from "universal-cookie";
import {User} from "../Signal/GlobalSignal";


export function makeErrorsByDefaultResponse(response: ErrorResponse): object {
    let rawResult: any = [];
    response.data.forEach((violation: Violation) => {
        if (!rawResult.hasOwnProperty(violation.path)) {
            rawResult[violation.path] = [];
        }

        rawResult[violation.path].push(violation.message);
    });

    let result: any = {};
    for (let field in rawResult) {
        result[field] = rawResult[field].join(' ');
    }

    return result;
}

export function setAccessToken(accessToken: string, expiredAt: Date): void {
    const cookies = new Cookies(null, {
        path: '/',
    });

    cookies.set('accessToken', accessToken, {
        expires: expiredAt,
    })
}

export function clearAccessToken(): void {
    const cookies = new Cookies(null, {
        path: '/',
    });

    cookies.set('accessToken', null);
}

export function getAccessToken(): string {
    const cookies = new Cookies(null, {
        path: '/',
    });

    return cookies.get('accessToken');
}

export function clearUser(): void {
    clearAccessToken();

    User.value = {
        data: {},
    };
}
