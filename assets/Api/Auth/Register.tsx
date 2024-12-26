import {callEndpoint, EmptyResponse} from "../callEndpoint";
import {clearUser} from "../../helpers/api";

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

export const logout = (
    onSuccess?: (response: EmptyResponse) => void,
    onError?: (reason: any) => void,
) => {
    const onSuccessWrapper = (response: EmptyResponse) => {
        clearUser();

        if (onSuccess) {
            onSuccess(response);
        }
    }

    callEndpoint('/api/v1/auth/logout', 'POST', null, onSuccessWrapper, onError);
};
