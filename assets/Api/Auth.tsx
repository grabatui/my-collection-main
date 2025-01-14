import {callEndpoint, EmptyResponse} from "./callEndpoint";
import {clearUser, setAccessToken} from "../helpers/api";

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

export type loginRequest = {
    email: string,
    password: string,
}

export interface LoginResponse {
    data: {
        accessToken: string,
    },
}

export type sendPasswordRequestRequest = {
    email: string,
}

export type resetPasswordRequest = {
    token: string,
    password: string,
    passwordRepeat: string,
}

export const login = (
    data: loginRequest,
    onSuccess?: (response: LoginResponse) => void,
    onError?: (reason: any) => void,
) => {
    const onSuccessWrapper = (response: LoginResponse) => {
        setAccessToken(response.data.accessToken, new Date(Date.now() + 86400e3 * 7))

        if (onSuccess) {
            onSuccess(response);
        }
    }

    callEndpoint('/api/v1/auth/login', 'POST', data, onSuccessWrapper, onError);
}

export const register = (
    data: registerRequest,
    onSuccess?: (response: RegisterResponse) => void,
    onError?: (reason: any) => void,
) => {
    const onSuccessWrapper = (response: RegisterResponse) => {
        setAccessToken(response.data.accessToken, new Date(Date.now() + 86400e3 * 7))

        if (onSuccess) {
            onSuccess(response);
        }
    }

    callEndpoint('/api/v1/auth/register', 'POST', data, onSuccessWrapper, onError);
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

export const sendResetPasswordRequest = (
    data: sendPasswordRequestRequest,
    onSuccess?: (response: EmptyResponse) => void,
    onError?: (reason: any) => void,
) => {
    callEndpoint('/api/v1/auth/send-reset-password-request', 'POST', data, onSuccess, onError);
}

export const resetPasswordRequest = (
    data: resetPasswordRequest,
    onSuccess?: (response: EmptyResponse) => void,
    onError?: (reason: any) => void,
) => {
    callEndpoint('/api/v1/auth/reset-password', 'POST', data, onSuccess, onError);
}
