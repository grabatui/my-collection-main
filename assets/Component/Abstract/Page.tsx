import {Component} from "preact";
import {clearUser, getAccessToken} from "../../helpers/api";
import {getMetadata} from "../../Api/User";
import {menuProfileOpened} from "../../Signal/MenuSignal";


export default abstract class Page<TPropTypes, TState> extends Component<TPropTypes, TState> {
    constructor() {
        super();

        if (getAccessToken()) {
            getMetadata();
        } else {
            clearUser();
        }
    }

    componentDidMount() {
        this.closeAllOpenedMenu();
    }

    protected closeAllOpenedMenu(): void {
        menuProfileOpened.value = false;
    }
}
