import {Component, ComponentChild} from "preact";
import Menu from "../Component/Menu";
import {getMetadata} from "../Api/User";
import {clearUser, getAccessToken} from "../helpers/api";
import {menuProfileOpened} from "../Signal/MenuSignal";


export default class Home<TPropTypes, TState> extends Component<TPropTypes, TState> {
    constructor() {
        super();

        if (getAccessToken()) {
            getMetadata();
        } else {
            clearUser();
        }
    }

    closeAllOpenedMenu(): void {
        menuProfileOpened.value = false;
    }

    render(): ComponentChild {
        return (
            <div onClick={() => this.closeAllOpenedMenu()}>
                <Menu />

                <div>Home</div>
            </div>
        );
    }
}
