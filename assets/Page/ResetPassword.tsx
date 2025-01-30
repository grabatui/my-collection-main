import Home from "./Home";
import {resetPasswordFormOpened, resetToken} from "../Signal/MenuSignal";


interface PropTypes {
    path: string,
    resetToken?: string,
    url?: string,
}


export default class ResetPassword extends Home<PropTypes> {
    componentDidMount() {
        super.componentDidMount();

        resetToken.value = this.props.resetToken;

        resetPasswordFormOpened.value = true
    }
}
