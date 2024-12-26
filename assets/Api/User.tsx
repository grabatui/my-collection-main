import {callEndpoint} from "./callEndpoint";
import {User} from "../Signal/GlobalSignal";

export interface UserMetadataResponse {
    data: {
        id: string,
        name: string,
        email: string,
        roles: Array<string>,
    },
}

export const getMetadata = (
    onSuccess?: (response: UserMetadataResponse) => void,
    onError?: (reason: any) => void,
) => {
    const onSuccessWrapper = (response: UserMetadataResponse) => {
        User.value = response;

        if (onSuccess) {
            onSuccess(response);
        }
    }

    const onErrorWrapper = (response: any) => {
        // TODO: Global error

        User.value = {
            data: {},
        };

        if (onError) {
            onError(response);
        }
    };

    callEndpoint('/api/v1/user/metadata', 'GET', null, onSuccessWrapper, onErrorWrapper)
};
