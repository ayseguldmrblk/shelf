import './App.css';
import React, { useState } from "react"
import Sidebar from './Components/Sidebar/Sidebar';
import HomePage from './Components/HomePage/HomePage';
import AccountPage from './Components/AccountPage/AccountPage';
import Books from './Components/Books/Books';
import Members from './Components/Members/Members';
import Reports from './Components/Reports/Reports';
import {Login} from "./Login";


function App() {
    const [selected2 , setSelected2] = useState(0);
    const [isLogin , setLogin] = useState(0);

    return (
        <>
            {
            <div className="App">
                {(() => {
                        switch (isLogin) {
                        case 0:
                            return <Login setLogin={setLogin}/>
                        case 1:
                            return  <div className="AppGlass">
                                        <Sidebar setSelected2={setSelected2}/>
                                        {(() => {
                                        switch (selected2) {
                                        case 0:
                                            return <HomePage/>
                                        case 1:
                                            return <AccountPage/>
                                        case 2:
                                            return <Books/>
                                        case 3:
                                            return <Members/>
                                        case 4:
                                            return <Reports/>
                                        default:
                                            return null
                                        }
                                    })()}
                                </div>
                        default:
                            return null
                        }
                })()}
            </div>
            }
        </>
    );
}

export default App;
