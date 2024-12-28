import {ComponentChild} from "preact";
import {registerFormOpened, resetPasswordFormOpened} from "../../Signal/MenuSignal";
import Input from "./Field/Input";
import {login, RegisterResponse} from "../../Api/Auth";
import {getMetadata} from "../../Api/User";
import {ErrorResponse} from "../../Api/callEndpoint";
import AbstractModalForm, {ModalFormPropTypes, ModalFormState} from "./AbstractModalForm";


interface PropTypes extends ModalFormPropTypes {
    onClose?: () => void;
}

interface State extends ModalFormState {
    email: string,
    password: string,
    error?: string,
    errors: any,
}

export default class LoginForm extends AbstractModalForm<PropTypes, State> {
    constructor() {
        super();

        this.state = {
            email: '',
            password: '',
            error: null,
            errors: {},
        }
    }

    onRegisterClick() {
        registerFormOpened.value = true;

        this.props.onClose();
        this.clearForm();
    }

    onResetPasswordClick() {
        resetPasswordFormOpened.value = true;

        this.props.onClose();
        this.clearForm();
    }

    onSubmit(event: Event) {
        event.preventDefault();

        login(
            {
                email: this.state.email,
                password: this.state.password,
            },
            (_: RegisterResponse) => {
                this.clearForm();
                this.props.onClose();

                getMetadata();
            },
            (response: ErrorResponse) => this.processErrorResponse(response),
        )
    }

    clearForm() {
        this.setState({
            email: '',
            password: '',
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
                        id="login_email"
                        value={this.state.email}
                        error={this.state.errors['email']}
                        onValueChange={(event) => this.onChangeStateValue('email', event.currentTarget.value)}
                        isRequired={true}
                        placeholder="name@company.com"
                    />

                    <Input
                        title="Придумайте пароль"
                        name="password"
                        type="password"
                        id="login_password"
                        value={this.state.password}
                        error={this.state.errors['password']}
                        onValueChange={(event) => this.onChangeStateValue('password', event.currentTarget.value)}
                        isRequired={true}
                        placeholder="••••••••"
                    />

                    {this.state.error && <p className="mt-2 text-sm text-red-600 dark:text-red-500">{this.state.error}</p>}

                    <div className="flex justify-between">
                        <a
                            href="#"
                            className="text-sm text-blue-700 hover:underline dark:text-blue-500"
                            onClick={this.onResetPasswordClick.bind(this)}
                        >Забыли пароль?</a>
                    </div>

                    <button
                        type="submit"
                        className="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                    >Авторизоваться</button>

                    <div className="text-sm font-medium text-gray-500 dark:text-gray-300">Ещё не зарегистрированы? <a
                        href="#"
                        className="text-blue-700 hover:underline dark:text-blue-500"
                        onClick={this.onRegisterClick.bind(this)}
                    >Создайте аккаунт</a>
                    </div>
                </form>
            </div>
        );
    }
}