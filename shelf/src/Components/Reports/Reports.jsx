import axios from "axios";
import React from 'react'
import { useEffect } from 'react';
import { useState } from 'react'
import './Reports.css'

const Reports = () => {

    const [reports , setReports] = useState([]);
    const classes = {
        'container':true,
        "ReportList":true,
    };

    const [reportInfo , setReportInfo] = useState([]);
    const [reportInfoVis , setReportInfoVis] = useState(0);

    useEffect( () => {
        axios
        .get('users.json')
        .then((res) => 
        setReports(res.data));
    },[])
    return (
        <div>
           <h1 className="header">REPORTS</h1>
           <div className="Reports">
                {(() => {
                    switch (reportInfoVis) {
                    case 0:
                        return <ul className={classes} >
                        {reports.map((report) => (
                            <li className="ReportListElement" key={report.id}
                                onClick = { () =>
                                {
                                    setReportInfo(report)
                                    setReportInfoVis(1)
                                }
                            }>
                                {report.username}
                            </li>
                        ))}
                    </ul>
                    case 1:
                        return  <div className="reportInfo">
                        <button className="buttonVis"
                            onClick={ () => setReportInfoVis(0)}>EXIT</button>
                        <pre>
                            User name: 
                            {reportInfo.username}
                        </pre>
                        <pre>
                            First name: 
                            {reportInfo.first_name}
                        </pre>
                        <pre>
                            Last name:
                            {reportInfo.last_name}
                        </pre>
                        <pre>
                            E-mail:
                            {reportInfo.email}
                        </pre>
                        <pre>
                            Phone number:
                            {reportInfo.phone}
                        </pre>
                    </div>
                    default :
                        return null
                }})}
           </div>
        </div>
    )
}

export default Reports