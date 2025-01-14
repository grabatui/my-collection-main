import {ComponentChild} from "preact";
import Input from "./Field/Input";
import {sendResetPasswordRequest} from "../../Api/Auth";
import {ErrorResponse} from "../../Api/callEndpoint";
import AbstractModalForm, {ModalFormPropTypes, ModalFormState} from "./AbstractModalForm";

interface PropTypes extends ModalFormPropTypes {
    onClose?: () => void;
}

interface State extends ModalFormState{
    email: string,
    error?: string,
    errors: any,
}

export default class SendResetPasswordForm extends AbstractModalForm<PropTypes, State> {
    constructor() {
        super();

        this.state = {
            email: '',
            error: null,
            errors: {},
        }
    }

    onSubmit(event: Event) {
        event.preventDefault();

        sendResetPasswordRequest(
            {
                email: this.state.email,
            },
            () => {
                this.clearForm();

                this.props.onClose();
            },
            (response: ErrorResponse) => this.processErrorResponse(response),
        )
    }

    clearForm() {
        this.setState({
            email: '',
            errors: {},
            error: null,
        })
    }

    render(): ComponentChild {
        return (
            <div className="p-4 md:p-5">
                <form className="space-y-4" action="#" onSubmit={this.onSubmit.bind(this)}>
                    <Input
                        title="Ваш Email"
                        name="email"
                        type="email"
                        id="reset_password_email"
                        value={this.state.email}
                        error={this.state.errors['email']}
                        onValueChange={(event) => this.onChangeStateValue('email', event.currentTarget.value)}
                        isRequired={true}
                        placeholder="name@company.com"
                    />

                    {this.state.error && <p className="mt-2 text-sm text-red-600 dark:text-red-500">{this.state.error}</p>}

                    <button
                        type="submit"
                        className="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                    >Отправить</button>
                </form>
            </div>
        );
    }
}