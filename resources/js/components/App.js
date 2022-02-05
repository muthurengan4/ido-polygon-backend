import React from "react";
import ReactDOM from "react-dom";
import { BrowserRouter, Switch, Route, Link } from "react-router-dom";
import Burn from "./Burn";
import DeployContract from "./DeployContract";
import Mint from "./Mint";

function App() {
    return (
        <BrowserRouter>
            <Switch>
                <Route path="/burn" component={Burn} />
                <Route path="/mint" component={Mint} />
                <Route path="/deploy-contract" component={DeployContract} />
                <Route path="/send-token" component={Burn} />
                {/* <Route component={Home} /> */}
            </Switch>
        </BrowserRouter>
    );
}

export default App;

if (document.getElementById("app")) {
    ReactDOM.render(<App />, document.getElementById("app"));
}
