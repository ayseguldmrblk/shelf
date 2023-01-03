import React from 'react'
import { useState } from 'react'
import './AccountPage.css'
import axios from "axios";
import { useEffect } from 'react';

import { useRef } from 'react';

const AccountPage = () => {

    const [begin , setBegin] = useState(1);
    const [users , setUsers] = useState([]);
    const [adminUserName , setUserName] = useState();
    const [adminFirstName , setFirstName] = useState();
    const [adminLastName , setLastName] = useState();
    const [adminPassword , setPassword] = useState();
    const [adminEmail , setEmail] = useState();
    const [adminPhone , setPhone] = useState();

    const [isOpen , setIsOpen] = useState(0);
    const infoUserNameRef = useRef();
    const infoFNameRef = useRef();
    const infoLNameRef = useRef();
    const infoPassRef = useRef();
    const infoMailRef = useRef();
    const infoPhoneRef = useRef();

    useEffect( () => {
        axios
        .get('users.json')
        .then((res) => 
        setUsers(res.data))
        .then(() => 
        setInfo(users))
        .finally(() => 
        setBegin(0));
    })

    function handleInfo() {
        setUserName(infoUserNameRef.current.value)
        setFirstName(infoFNameRef.current.value)
        setLastName(infoLNameRef.current.value)
        setPassword(infoPassRef.current.value)
        setEmail(infoMailRef.current.value)
        setPhone(infoPhoneRef.current.value)
    }

    function setInfo(users) {   
        if (begin === 1) {
            setUserName(users[5].username)
            setFirstName(users[5].first_name)
            setLastName(users[5].last_name)
            setPassword(users[5].password)
            setEmail(users[5].email)
            setPhone(users[5].phone)
        }
                        
    }
    return (    
        <div>
            <h1 className="header">ACCOUNT PAGE</h1>
            <div className="info">
                {(() => {
                    switch(isOpen){
                        case 0:
                            return  <div>
                                        <pre>
                                            User name   : 
                                            {adminUserName}
                                        </pre>
                                        <pre>
                                            Password    :
                                            {adminPassword}
                                        </pre>
                                        <pre>
                                            First name  : 
                                            {adminFirstName}
                                        </pre>
                                        <pre>
                                            Last name   :
                                            {adminLastName}
                                        </pre>
                                        <pre>
                                            E-mail      :
                                            {adminEmail}
                                        </pre>
                                        <pre>
                                            Phone number:
                                            {adminPhone}
                                        </pre>
                                            <button onClick={ () => {setIsOpen(1)}}>set</button>
                                        </div>
                        case 1:
                            return  <div>
                                        <pre>
                                            User name     : 
                                            <input ref={infoUserNameRef}></input>
                                        </pre>
                                        <pre>
                                            Password      :
                                            <input ref={infoPassRef} type="password"></input>
                                        </pre>
                                        <pre>
                                            Password again:
                                            <input type="password"></input>
                                        </pre>
                                        <pre>
                                            First name    : 
                                            <input ref={infoFNameRef}></input>
                                        </pre>
                                        <pre>
                                            Last name     :
                                            <input ref={infoLNameRef}></input>
                                        </pre>
                                        <pre>
                                            E-mail        :
                                            <input ref={infoMailRef}></input>
                                        </pre>
                                        <pre>
                                            Phone number  :
                                            <input ref={infoPhoneRef}></input>
                                        </pre>
                                        <button onClick={ () => {setIsOpen(0)}}>exit</button>
                                        <button onClick={ () => {
                                                                    setIsOpen(0)
                                                                    handleInfo()
                                                                }}>save</button>
                                    </div>

                        default:
                            return null
                    }
                })()}
            </div>
        </div>
    )
}

export default AccountPage