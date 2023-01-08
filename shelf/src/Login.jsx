import axios from "axios";
import React, { useState } from "react";

export const Login = ( {setLogin} ) => {
    const [email_log, setEmail] = useState('');
    const [pass_log, setPass] = useState('');
    const [popup , setPopup] = useState();

    const handleSubmit = (e) => {
        e.preventDefault();
        console.log(email_log);
        console.log(pass_log);

        try {
            axios({
                method: 'post',
                url: "https://hodikids.com/api/login",
                data: {
                        "email" : email_log,
                        "password": pass_log
                      }
            })
            .then((res) => {               
                if(res.status === 200){
                    if (res.data.user.is_superuser === 1) {
                        setLogin(1);
                        setPopup(0);
                    }
                }
            })
        } catch (error) {
            console.log(error);
        }
        if(popup !== 0){
            setPopup(1);
        }
    }
    
  
    return (
        <>
            <div className="auth-form-container">
                <h2>Login {}</h2>
                <form className="login-form" onSubmit={handleSubmit}>
                    <label htmlFor="email">email</label>
                    <input value={email_log} onChange={(e) => setEmail(e.target.value)}type="email" placeholder="youremail@gmail.com" id="email" name="email" />
                    <label htmlFor="password">password</label>
                    <input value={pass_log} onChange={(e) => setPass(e.target.value)} type="password" placeholder="********" id="password" name="password" />
                    <button type="submit">Log In</button>
                </form> 
            </div>
            <div className="popup_login">
                {(() => {
                    switch(popup){
                        case 0:  return  <></>
                        case 1:  return <div><br/><b>E-mail or Password <br/> is incorrect. <br/>Please try again.</b></div>
                        default: return <></>
                    }
                })()}
            </div>
        </>
    )
}