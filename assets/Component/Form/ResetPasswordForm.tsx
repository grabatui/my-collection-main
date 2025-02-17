import {ComponentChild} from "preact";
import Input from "./Field/Input";
import {ErrorResponse} from "../../Api/callEndpoint";
import AbstractModalForm, {ModalFormPropTypes, ModalFormState} from "./AbstractModalForm";
import {resetPasswordRequest} from "../../Api/Auth";

interface PropTypes extends ModalFormPropTypes {
    onClose?: () => void;
    resetToken: string;
}

interface State extends ModalFormState{
    password: string,
    passwordRepeat: string,
    error?: string,
    errors: any,
}

export default class ResetPasswordForm extends AbstractModalForm<PropTypes, State> {
    constructor() {
        super();

        this.state = {
            password: '',
            passwordRepeat: '',
            error: null,
            errors: {},
        }
    }

    onSubmit(event: Event) {
        event.preventDefault();

        resetPasswordRequest(
            {
                token: this.props.resetToken,
                password: this.state.password,
                passwordRepeat: this.state.passwordRepeat,
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
            password: '',
            passwordRepeat: '',
            errors: {},
            error: null,
        })
    }

    render(): ComponentChild {
        return (
            <div className="p-4 md:p-5">
                <form className="space-y-4" action="#" onSubmit={this.onSubmit.bind(this)}>
                    <Input
                        title="Придумайте пароль"
                        name="password"
                        type="password"
                        id="reset_password_password"
                        value={this.state.password}
                        error={this.state.errors['password']}
                        onValueChange={(event) => this.onChangeStateValue('password', event.currentTarget.value)}
                        isRequired={true}
                        placeholder="••••••••"
                    />

                    <Input
                        title="Повторите пароль"
                        name="passwordRepeat"
                        type="password"
                        id="reset_password_password_repeat"
                        value={this.state.passwordRepeat}
                        error={this.state.errors['passwordRepeat']}
                        onValueChange={(event) => this.onChangeStateValue('passwordRepeat', event.currentTarget.value)}
                        isRequired={true}
                        placeholder="••••••••"
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