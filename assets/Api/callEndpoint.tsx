import {clearAccessToken, getAccessToken, setAccessToken} from "../helpers/api";

export interface ErrorResponse {
    data: Violation[],
    message: string,
    resultCode: 'validation_error'|'internal_error',
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
        headers.set('Authorization', 'Bearer ' + accessToken)
    }

    fetch(
        endpoint,
        {
            method: method,
            body: data ? JSON.stringify(data) : null,
            headers: headers,
        }
    )
        .then((response) => {
            if (response.status === 401) {
                clearAccessToken();
                return {};
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

            if (onSuccess) {
                onSuccess(response);
            }
        })
        .catch((reason: any) => {
            if (onError) {
                if (process.env.NODE_ENV === 'development') {
                    console.error(reason);
                }

                onError(reason);
            }
        });
}
