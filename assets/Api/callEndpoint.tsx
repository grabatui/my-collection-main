import {clearAccessToken, clearUser, getAccessToken} from "../helpers/api";
import {refreshToken, RefreshTokenResponse} from "./Auth";

export interface EmptyResponse {
    data: object,
    message: string,
    resultCode: 'success',
}

export interface ErrorResponse {
    data: Violation[],
    message: string,
    resultCode: 'validation_error'|'internal_error'|string,
}

export interface Violation {
    message: string,
    path: string
}


export const callEndpoint = (
    endpoint: string,
    method: 'POST'|'GET'|'PUT'|'PATCH'|'DELETE',
    data?: any,
    onSuccess?: (response: any) => void,
    onError?: (reason: any) => void,
) => {
    let headers = new Headers([
        ['Content-Type', 'application/json'],
        ['Accept', 'application/json'],
    ]);

    const accessToken = getAccessToken();

    if (accessToken) {
        headers.set('Authorization', 'Bearer ' + accessToken.accessToken)
    }

    const lastKey: string = Object.keys(data).pop();
    if (data && typeof data === 'object' && method === 'GET') {
        endpoint += '?';
        for (const key in data) {
            endpoint += key + '=' + data[key];

            if (key !== lastKey) {
                endpoint += '&';
            }
        }
    }

    fetch(
        endpoint,
        {
            method: method,
            body: data && method !== 'GET' ? JSON.stringify(data) : null,
            headers: headers,
        }
    )
        .then((response) => {
            if (response.status < 200 || response.status > 399) {
                if (response.status === 401) {
                    tryToRefreshToken(
                        (_: RefreshTokenResponse) => {
                            callEndpoint(endpoint, method, data, onSuccess, onError);

                            throw new Error('__token_refreshed__');
                        },
                        (error: Error) => {
                            if (error.message !== '__token_refreshed__') {
                                clearUser();
                            }
                        }
                    )

                    return new Promise(() => {{}});
                }
            }

            return response.json();
        })
        .then((response: any) => {
            if (
                (response.resultCode === 'validation_error' || response.resultCode === 'internal_error')
                && onError
            ) {
                if (process.env.NODE_ENV === 'development') {
                    console.error(response);
                }

                onError(response);
                return
            }

            if (
                (
                    (typeof response === 'object' && Object.keys(response).length <= 0 )
                    || response.resultCode !== 'success'
                )
                && onError
            ) {
                onError(response);
                return;
            }

            if (onSuccess) {
                onSuccess(response);
            }
        })
        .catch((reason: any) => {
            if (reason instanceof Error && reason.message === '__token_refreshed__') {
                return;
            }

            if (onError) {
                if (process.env.NODE_ENV === 'development') {
                    console.error(reason);
                }

                onError(reason);
            }
        });
}

const tryToRefreshToken = (
    onSuccess: (response: RefreshTokenResponse) => void,
    onError: (reason: any) => void,
) => {
    const tokens = getAccessToken();

    clearAccessToken();

    if (tokens.refreshToken) {
        refreshToken(
            {refreshToken: tokens.refreshToken},
            onSuccess,
            onError
        );
    }
}
