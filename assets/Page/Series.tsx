import {ComponentChild} from "preact";
import Page from "../Component/Abstract/Page";


export default class Series extends Page<any, any> {
    renderInner(): ComponentChild {
        return (
            <div>Series</div>
        );
    }
}