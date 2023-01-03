import React from 'react'
import { useEffect } from 'react';
import { useState } from 'react'
import './Members.css'
import axios from "axios";

const Members = () => {

    const [users , setUsers] = useState([]);
    const classes = {
        'container':true,
        "MermberList":true,
    };

    const [userInfo , setUserInfo] = useState([]);
    const [userInfoVis , setUserInfoVis] = useState(0);
    
    useEffect( () => {
        axios
        .get('users.json')
        .then((res) =>
        setUsers(res.data));
    },[])

    return (
        <div>
            <h1 className="header">MEMBERS</h1>
            
            <div className="Members">
                {(() => {
                    switch (userInfoVis) {
                    case 0:
                        return <ul className={classes} >
                                    {users.map((user) => (
                                        <li className="MembersListElement" key={user.id}
                                            onClick = { () =>
                                            {
                                                setUserInfo(user)
                                                setUserInfoVis(1)
                                            }
                                        }>
                                            {user.username}
                                        </li>
                                    ))}
                                </ul>  
                    case 1:
                        return  <div className="userInfo">
                                    <button className="buttonVis"
                                        onClick={ () => setUserInfoVis(0)}>EXIT</button>
                                    <pre>
                                        User name: 
                                        {userInfo.username}
                                    </pre>
                                    <pre>
                                        First name: 
                                        {userInfo.first_name}
                                    </pre>
                                    <pre>
                                        Last name:
                                        {userInfo.last_name}
                                    </pre>
                                    <pre>
                                        E-mail:
                                        {userInfo.email}
                                    </pre>
                                    <pre>
                                        Phone number:
                                        {userInfo.phone}
                                    </pre>
                                </div>
                    default:
                        return null
                    }
                })()}
            </div>
        </div>
    )
}

export default Members