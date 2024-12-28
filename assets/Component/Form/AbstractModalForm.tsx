import {Component} from "preact";
import {ErrorResponse} from "../../Api/callEndpoint";
import {makeErrorsByDefaultResponse} from "../../helpers/api";

export interface ModalFormPropTypes {
    onClose?: () => void;
}

export interface ModalFormState {
    error?: string;
    errors: any;
}

export default abstract class AbstractModalForm<TProps extends ModalFormPropTypes, TState extends ModalFormState> extends Component<TProps, TState> {
    onChangeStateValue(field: string, value: any) {
        this.setState({
            ...this.state,
            [field]: value,
            errors: {
                ...this.state.errors,
                [field]: null,
            },
            error: null,
        });
    }

    processErrorResponse(response: ErrorResponse) {
        if (response.resultCode !== 'validation_error') {
            this.setState({
                ...this.state,
                error: response.message,
            });
        } else {
            this.setState({
                ...this.state,
                errors: makeErrorsByDefaultResponse(response),
            });
        }
    }
}