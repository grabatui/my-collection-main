import {ComponentChild} from "preact";
import Menu from "../Component/Menu";
import Page from "../Component/Abstract/Page";


export default class Profile extends Page<any, any> {
    render(): ComponentChild {
        return (
            <div onClick={() => this.closeAllOpenedMenu()}>
                <Menu/>

                <div>Profile</div>
            </div>
        )
    }
}