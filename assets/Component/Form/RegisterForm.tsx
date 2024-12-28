import {ComponentChild} from "preact";
import {authFormOpened} from "../../Signal/MenuSignal";
import {register, RegisterResponse} from "../../Api/Auth";
import {ErrorResponse} from "../../Api/callEndpoint";
import Input from "./Field/Input";
import {getMetadata} from "../../Api/User";
import AbstractModalForm, {ModalFormPropTypes, ModalFormState} from "./AbstractModalForm";


interface PropTypes extends ModalFormPropTypes {
    onClose?: () => void;
}

interface State extends ModalFormState {
    email: string,
    name: string,
    password: string,
    passwordRepeat: string,
    errors: any,
}

export default class RegisterForm extends AbstractModalForm<PropTypes, State> {
    constructor() {
        super();

        this.state = {
            email: '',
            name: '',
            password: '',
            passwordRepeat: '',
            errors: {},
        }
    }

    onAuthClick() {
        authFormOpened.value = true;

        this.props.onClose();
        this.clearForm();
    }

    onSubmit(event: Event) {
        event.preventDefault();

        register(
            {
                email: this.state.email,
                name: this.state.name,
                password: this.state.password,
                passwordRepeat: this.state.passwordRepeat,
            },
            (_: RegisterResponse) => {
                this.clearForm();
                this.props.onClose();

                getMetadata();
            },
            (response: ErrorResponse) => this.processErrorResponse(response),
        );
    }

    clearForm() {
        this.setState({
            email: '',
            name: '',
            password: '',
            passwordRepeat: '',
            errors: {},
        });
    }

    render(): ComponentChild {
        return (
            <div className="p-4 md:p-5">
                <form className="space-y-4" action="#" onSubmit={this.onSubmit.bind(this)}>
                    <Input
                        title="Ваш Email"
                        name="email"
                        type="email"
                        id="register_email"
                        value={this.state.email}
                        error={this.state.errors['email']}
                        onValueChange={(event) => this.onChangeStateValue('email', event.currentTarget.value)}
                        isRequired={true}
                        placeholder="name@company.com"
                    />

                    <Input
                        title="Имя"
                        name="name"
                        id="register_name"
                        value={this.state.name}
                        error={this.state.errors['name']}
                        onValueChange={(event) => this.onChangeStateValue('name', event.currentTarget.value)}
                        isRequired={true}
                    />

                    <Input
                        title="Придумайте пароль"
                        name="password"
                        type="password"
                        id="register_password"
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
                        id="register_password_repeat"
                        value={this.state.passwordRepeat}
                        error={this.state.errors['passwordRepeat']}
                        onValueChange={(event) => this.onChangeStateValue('passwordRepeat', event.currentTarget.value)}
                        isRequired={true}
                        placeholder="••••••••"
                    />

                    <button
                        type="submit"
                        className="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                    >Зарегистрироваться</button>

                    <div className="text-sm font-medium text-gray-500 dark:text-gray-300">Уже зарегистрированы? <a
                        href="#"
                        className="text-blue-700 hover:underline dark:text-blue-500"
                        onClick={this.onAuthClick.bind(this)}
                    >Авторизуйтесь</a>
                    </div>
                </form>
            </div>
        );
    }
}