export interface ErrorResponse {
    data: Violation[],
    message: string,
    resultCode: string,
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
    fetch(
        endpoint,
        {
            method: method,
            body: data ? JSON.stringify(data) : null,
            headers: [
                ['Content-Type', 'application/json'],
                ['Accept', 'application/json'],
            ]
        }
    )
        .then((response) => response.json())
        .then((response: any) => {
            console.log(response);
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
