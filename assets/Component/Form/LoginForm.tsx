import {Component, ComponentChild, RenderableProps} from "preact";
import {openRegisterFromLogin} from "../../Signal/MenuSignal";
import Input from "./Field/Input";


type propTypes = {
    onClose?: () => void;
}

type state = {
    email: string,
    password: string,
    errors: any,
}

export default class LoginForm extends Component<propTypes, state> {
    constructor() {
        super();

        this.state = {
            email: '',
            password: '',
            errors: {},
        }
    }

    onChangeStateValue(field: string, value: any) {
        this.setState({
            ...this.state,
            [field]: value,
            errors: {
                ...this.state.errors,
                [field]: null,
            },
        });
    }

    onRegisterClick() {
        openRegisterFromLogin.value = true;

        this.props.onClose();
        this.clearForm();
    }

    onSubmit(event: Event) {
        event.preventDefault();

        // TODO
    }

    clearForm() {
        this.setState({
            email: '',
            password: '',
            errors: {},
        })
    }

    render(props?: RenderableProps<any>, state?: Readonly<any>, context?: any): ComponentChild {
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

                    <div className="flex justify-between">
                        <a href="#" className="text-sm text-blue-700 hover:underline dark:text-blue-500">Забыли пароль?</a>
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