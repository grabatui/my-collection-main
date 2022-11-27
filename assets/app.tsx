// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';

import Router from 'preact-router';
import {h, render} from 'preact';


const App = () => (
    <div>
        <div>Hello world</div>
        <Router />
    </div>
);

render(<App />, document.getElementById('app'));
