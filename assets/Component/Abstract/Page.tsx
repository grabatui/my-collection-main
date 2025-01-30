import {Component, ComponentChild} from "preact";
import {clearUser, getAccessToken} from "../../helpers/api";
import {getMetadata} from "../../Api/User";
import {menuProfileOpened} from "../../Signal/MenuSignal";
import Menu from "../Menu";


export default abstract class Page<TPropTypes, TState> extends Component<TPropTypes, TState> {
    constructor() {
        super();

        if (getAccessToken()) {
            getMetadata();
        } else {
            clearUser();
        }
    }

    abstract renderInner(): ComponentChild;

    componentDidMount() {
        this.closeAllOpenedMenu();
    }

    protected closeAllOpenedMenu(): void {
        menuProfileOpened.value = false;
    }

    render(): ComponentChild {
        return (
            <div onClick={() => this.closeAllOpenedMenu()}>
                <Menu/>

                <div className="w-100 px-8 md:px-auto md:h-16 h-28 mx-auto md:px-4 container p-6">
                    {this.renderInner()}
                </div>
            </div>
        );
    }
}
