import {callEndpoint} from "../callEndpoint";

export type registerRequest = {
    email: string,
    name: string,
    password: string,
    passwordRepeat: string,
}

export interface RegisterResponse {
    data: {
        accessToken: string,
    },
}

export const register = (
    data: registerRequest,
    onSuccess?: (response: RegisterResponse) => void,
    onError?: (reason: any) => void,
) => {
    callEndpoint('/api/v1/auth/register', 'POST', data, onSuccess, onError);
};
