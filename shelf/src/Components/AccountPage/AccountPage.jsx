import React from 'react'
import { useState } from 'react'
import './AccountPage.css'
import axios from "axios";
import { useEffect } from 'react';
import { useRef } from 'react';

const AccountPage = ({setLogin}) => {

    const [admin , setAdmin] = useState([]);
    const [isOpen , setIsOpen] = useState(0);
    const [difPass , setDifPass] = useState(0);
    const infoNameRef  = useRef();
    const infoPassRef1 = useRef();
    const infoPassRef2 = useRef();
    const infoMailRef  = useRef();
    const infoPhoneRef = useRef();
    
    const [updated , setUpdated] = useState(0);

    useEffect( () => {
        axios
        .get(`https://hodikids.com/api/users`)
        .then((res) => 
        controlA(res.data[res.data.length-1]))
    },[updated])

    function updateUser(){
        if(infoPassRef1.current.value === infoPassRef2.current.value){
            axios
            .put(`https://hodikids.com/api/user/${admin.id}/update` ,
            {
                "name": infoNameRef.current.value,
                "email": infoMailRef.current.value,
                "password" : infoPassRef1.current.value,
                "phone" :infoPhoneRef.current.value
            },)
            .then((response) => {
                console.log(response)
            })
            setUpdated(1);
            setIsOpen(0)
        }
        else{
            setDifPass(1);
            setUpdated(1);
        }
    }
    
    function controlA(data){
        if(data.is_superuser === 1){
            setAdmin(data);
        }
        else{
            console.log("error!");
        }
    }

    return (    
        <div>
            <div className="info">
                {(() => {
                    switch(isOpen){
                        case 0:
                            return  <div>
                                        <pre>
                                            <b>Name        :</b> 
                                            {admin.name}
                                        </pre>
                                        <pre>
                                            <b>E-mail      :</b>
                                            {admin.email}
                                        </pre>
                                        <pre>
                                            <b>Phone</b>       :
                                            {admin.phone}
                                        </pre>

                                            <button onClick={ () => {setIsOpen(1)}}>set</button>
                                        </div>
                        case 1:
                            return  <div>
                                        <pre>
                                            Name          : 
                                            <input ref={infoNameRef}></input>
                                        </pre>
                                        <pre>
                                            Password      :
                                            <input ref={infoPassRef1} type="password"></input>
                                        </pre>
                                        <pre>
                                            Password again:
                                            <input ref={infoPassRef2} type="password"></input>
                                        </pre>
                                        <pre>
                                            E-mail        :
                                            <input ref={infoMailRef}></input>
                                        </pre>
                                        <pre>
                                            Phone         :
                                            <input ref={infoPhoneRef}></input>
                                        </pre>
                                        <button onClick={ () => {setIsOpen(2)}}>exit</button>
                                        <button onClick={() => 
                                        {
                                            updateUser()
                                        }}>
                                            save
                                        </button>
                                        <div className="popup_pass">
                                            {(() => {
                                                switch(difPass){
                                                    case 1:  return <div><br/><b>Passwords are not same!!</b></div>
                                                    default: return <></>
                                                }
                                            })()}
                                        </div>
                                    </div>
                        case 2:
                            return  <div className="exit_popup">
                                        <h4>Your changes will not be saved. Are you sure?</h4>
                                        <button onClick={ () => {setIsOpen(0)}}> Yes</button>
                                        <button onClick={ () => {setIsOpen(1)}}> No</button>
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