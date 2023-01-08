import React from 'react'
import { useState } from 'react';
import { SidebarData } from '../../Data/Data';
import Logo from '../../imgs/logo.png'
import './Sidebar.css'


function Sidebar( {setSelected2} ) {

    function logOut(){
        
    }
    const [selected , setSelected] = useState(0);
    return (
        <div className="Sidebar">
            <div className="logo">
                <img src={Logo} alt="" />              
            </div>

            <div>
                {SidebarData.map((item , index) => {
                    return(
                        <div className={selected===index?'menuItem active':'menuItem'}
                            key={index}
                            onClick={
                                ()=> {setSelected(index)
                                      setSelected2(index)
                                      if (index === 5) {
                                        window.location.reload(false)
                                        logOut();
                                      }
                                    }
                            }>
  
                            <item.icon/>
                            <span>
                                {item.heading}
                            </span>
                        </div>
                    )
                })}
            </div>
        </div>
    );
};



export default Sidebar