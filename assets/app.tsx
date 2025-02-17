// any CSS you import will output into a single css file (app.css in this case)
import "./styles/app.css";

import Router from "preact-router";
import {h, render} from "preact";
import Home from "./Page/Home";
import ResetPassword from "./Page/ResetPassword";
import Profile from "./Page/Profile";
import Series from "./Page/Series";


const App = () => (
    <Router>
        <Home path="/" />
        <Profile path="/profile" />
        <ResetPassword path="/reset-password/:resetToken" />

        <Series path="/series" />
    </Router>
);

render(<App />, document.getElementById('app'));
