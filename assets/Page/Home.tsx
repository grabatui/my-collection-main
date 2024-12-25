import {Component, ComponentChild, RenderableProps} from "preact";
import Menu from "../Component/Menu";


export default class Home extends Component<any, any> {
    render(props?: RenderableProps<any>, state?: Readonly<any>, context?: any): ComponentChild {
        return (
            <div>
                <Menu />

                <div>Home</div>
            </div>
        );
    }
}
