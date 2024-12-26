import {Component, ComponentChild, RenderableProps} from "preact";
import Menu from "../Component/Menu";
import {getMetadata} from "../Api/User";
import {getAccessToken} from "../helpers/api";
import {User} from "../Signal/GlobalSignal";


export default class Home extends Component<any, any> {
    constructor() {
        super();

        if (getAccessToken()) {
            getMetadata();
        } else {
            User.value = {
                data: {},
            }
        }
    }

    render(props?: RenderableProps<any>, state?: Readonly<any>, context?: any): ComponentChild {
        return (
            <div>
                <Menu />

                <div>Home</div>
            </div>
        );
    }
}
