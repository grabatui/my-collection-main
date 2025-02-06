import {Component, ComponentChild, Fragment} from "preact";
import {Link} from 'preact-router/match';
import Modal from "./Modal";
import LoginForm from "./Form/LoginForm";
import RegisterForm from "./Form/RegisterForm";
import {
    registerFormOpened,
    authFormOpened,
    menuProfileOpened,
    sendResetPasswordFormOpened,
    resetPasswordFormOpened, resetToken
} from "../Signal/MenuSignal";
import {User} from "../Signal/GlobalSignal";
import Loader from "./Loader";
import {logout} from "../Api/Auth";
import SendResetPasswordForm from "./Form/SendResetPasswordForm";
import ResetPasswordForm from "./Form/ResetPasswordForm";


type state = {
    isLoginOpen: boolean,
    isRegisterOpen: boolean,
}

export default class Menu extends Component<any, state> {
    constructor() {
        super();

        this.state = {
            isLoginOpen: false,
            isRegisterOpen: false,
        }
    }

    openLoginModal() {
        this.setState({isLoginOpen: true});
    }

    closeLoginModal() {
        authFormOpened.value = false;

        this.setState({isLoginOpen: false});
    }

    closeRegisterModal() {
        registerFormOpened.value = false;

        this.setState({isRegisterOpen: false});
    }

    closeSendResetPasswordModal() {
        sendResetPasswordFormOpened.value = false;
    }

    closeResetPasswordModal() {
        resetPasswordFormOpened.value = false;
    }

    toggleProfileDropdownMenu() {
        menuProfileOpened.value = !menuProfileOpened.value;
    }

    onLogoutClient() {
        logout();
    }

    render(): ComponentChild {
        return (
            <Fragment>
                <nav className="bg-gray-200 shadow shadow-gray-300 w-100 px-8 md:px-auto">
                    <div
                        className="md:h-16 h-28 mx-auto md:px-4 container flex items-center justify-between flex-wrap md:flex-nowrap"
                    >
                        <div className="text-indigo-500 md:order-1">
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                className="h-10 w-10"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    strokeLinecap="round"
                                    strokeLinejoin="round"
                                    strokeWidth="2"
                                    d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"
                                />
                            </svg>
                        </div>

                        <div className="text-gray-500 order-3 w-full md:w-auto md:order-2">
                            <nav className="flex font-semibold justify-between">
                                {this.getMainMenuLink("/", "Главная")}
                                {this.getMainMenuLink("/series", "Сериалы")}
                            </nav>
                        </div>

                        <div className="order-2 md:order-3">
                            {User.value
                                ? (User.value.data.name ? this.getAuthorizedButton() : this.getUnauthorizedButton())
                                : <Loader/>
                            }
                        </div>
                    </div>
                </nav>

                <Modal
                    isOpen={this.state.isLoginOpen || authFormOpened.value}
                    onClose={this.closeLoginModal.bind(this)}
                    title={"Авторизация"}
                >
                    <LoginForm onClose={this.closeLoginModal.bind(this)} />
                </Modal>

                <Modal
                    isOpen={this.state.isRegisterOpen || registerFormOpened.value}
                    onClose={this.closeRegisterModal.bind(this)}
                    title={"Регистрация"}
                >
                    <RegisterForm onClose={this.closeRegisterModal.bind(this)} />
                </Modal>

                <Modal
                    isOpen={sendResetPasswordFormOpened.value}
                    onClose={this.closeSendResetPasswordModal.bind(this)}
                    title={"Сброс пароля"}
                >
                    <SendResetPasswordForm onClose={this.closeSendResetPasswordModal.bind(this)} />
                </Modal>

                <Modal
                    isOpen={resetPasswordFormOpened.value}
                    onClose={this.closeResetPasswordModal.bind(this)}
                    title={"Сброс пароля"}
                >
                    <ResetPasswordForm
                        onClose={this.closeResetPasswordModal.bind(this)}
                        resetToken={resetToken.value}
                    />
                </Modal>
            </Fragment>
        );
    }

    getUnauthorizedButton(): ComponentChild {
        return (
            <div>
                <button
                    className="px-4 py-2 bg-indigo-500 hover:bg-indigo-600 text-gray-50 rounded-xl flex items-center gap-2"
                    onClick={this.openLoginModal.bind(this)}
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        className="h-5 w-5"
                        viewBox="0 0 20 20"
                        fill="currentColor"
                    >
                        <path
                            fillRule="evenodd"
                            d="M3 3a1 1 0 011 1v12a1 1 0 11-2 0V4a1 1 0 011-1zm7.707 3.293a1 1 0 010 1.414L9.414 9H17a1 1 0 110 2H9.414l1.293 1.293a1 1 0 01-1.414 1.414l-3-3a1 1 0 010-1.414l3-3a1 1 0 011.414 0z"
                            clipRule="evenodd"
                        />
                    </svg>

                    <span>Авторизуйтесь</span>
                </button>
            </div>
        );
    }

    getAuthorizedButton(): ComponentChild {
        return (
            <div
                onClick={(event) => event.stopPropagation()}>
                <button
                    className="px-4 py-2 bg-indigo-500 hover:bg-indigo-600 text-gray-50 rounded-xl flex items-center gap-2 w-44"
                    onClick={this.toggleProfileDropdownMenu.bind(this)}
                >
                    <svg
                        className="w-2.5 h-2.5 ms-3"
                        aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 10 6"
                        style={"transform: rotate(" + (menuProfileOpened.value ? '180' : '0') + "deg); transition: transform 0.1s;"}
                    >
                        <path
                            stroke="currentColor"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="m1 1 4 4 4-4"
                        />
                    </svg>

                    <span>{User.value.data.name}</span>
                </button>

                {this.getDropdownMenu()}
            </div>
        );
    }

    getDropdownMenu(): ComponentChild {
        return (
            <div
                id="dropdownDivider"
                className={'z-10 bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700 dark:divide-gray-600 ' + (menuProfileOpened.value ? 'block' : 'hidden')}
                style="position: absolute; margin: 0px;"
            >
                <ul className="py-2 text-sm text-gray-700 dark:text-gray-200">
                    <li onClick={this.toggleProfileDropdownMenu.bind(this)}>
                        <Link
                            href={"/profile"}
                            className="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white"
                        >Профиль</Link>
                    </li>
                </ul>

                <div className="py-2">
                    <a
                        href="#"
                        className="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white"
                        onClick={this.onLogoutClient.bind(this)}
                    >Выйти</a>
                </div>
            </div>
        );
    }

    private getMainMenuLink(
        url: string,
        name: string,
    ): ComponentChild {
        let colorClassName = 'text-gray-600 dark:text-gray-500';
        if (
            (url === '/' && document.location.pathname === '/')
            || (url !== '/' && document.location.pathname.startsWith(url))
        ) {
            colorClassName = 'text-indigo-600 dark:text-indigo-500';
        }

        return (
            <Link
                className={"hover:underline px-2 " + colorClassName}
                href={url}
            >{name}</Link>
        );
    }
}
