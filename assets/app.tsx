// any CSS you import will output into a single css file (app.css in this case)
import "./styles/app.css";

import Router from "preact-router";
import {h, render} from "preact";
import Home from "./Page/Home";
import ResetPassword from "./Page/ResetPassword";


const App = () => (
    <Router>
        <Home path="/" />
        <ResetPassword path="/reset-password/:resetToken" />
    </Router>
);

render(<App />, document.getElementById('app'));
