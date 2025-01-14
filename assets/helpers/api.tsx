import {ErrorResponse, Violation} from "../Api/callEndpoint";
import Cookies from "universal-cookie";
import {User} from "../Signal/GlobalSignal";

interface Tokens {
    accessToken: string
    refreshToken: string
}

export function makeErrorsByDefaultResponse(response: ErrorResponse): object {
    if (response.resultCode !== 'validation_error') {
        return {};
    }

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

export function setAccessToken(accessToken: string, refreshToken: string, expiredAt: Date): void {
    const cookies = new Cookies(null, {
        path: '/',
    });

    cookies.set(
        'accessToken',
        {accessToken, refreshToken},
        {
            expires: expiredAt,
        }
    );
}

export function clearAccessToken(): void {
    const cookies = new Cookies(null, {
        path: '/',
    });

    cookies.set('accessToken', null);
}

export function getAccessToken(): Tokens {
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
