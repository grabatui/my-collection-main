import {ComponentChild} from "preact";
import Page from "../Component/Abstract/Page";


export default class Profile extends Page<any, any> {
    renderInner(): ComponentChild {
        return (
            <div>Profile</div>
        );
    }
}