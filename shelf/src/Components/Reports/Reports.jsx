import React from 'react'
import { useEffect } from 'react';
import { useState } from 'react'
import './Reports.css'
import axios from "axios";

const Reports = () => {

    const [reports , setReports] = useState([]);
    const [reportInfo , setReportInfo] = useState([]);
    const [reportInfoVis , setReportInfoVis] = useState(0);
    const [loading, setLoading] = useState(false);
    const [currentPage, setCurrentPage] = useState(1);
    const [reportsPerPage] = useState(10);

    const classes = {
        'container':true,
        "ReportList":true,
    };
    
    useEffect( () => {
        setLoading(1);
        axios
        .get('https://hodikids.com/api/reports')
        .then((res) => 
        setReports(res.data));
        setLoading(0);
    },[reportInfoVis])
    
    const indexOfLastReport = currentPage * reportsPerPage;
    const indexOfFirstReport = indexOfLastReport - reportsPerPage;
    const currentReports = reports.slice(indexOfFirstReport, indexOfLastReport);
    const paginate = pageNumber => setCurrentPage(pageNumber);
    const pageNumbers = [];

    for (let i = 1; i <= Math.ceil(reports.length / reportsPerPage); i++) {
        pageNumbers.push(i);
    }

    return (
        <div>
            <div className="Reports">            
                {(() => {
                    switch (reportInfoVis) {
                    case 0:
                        if (loading) {
                            return <h2>Loading...</h2>;
                        }
                        return  <div className={classes}>
                                    {currentReports.map((report) => (
                                        <li className="ReportListElement" key={report.id} 
                                        onClick = { () =>
                                           {   
                                               setReportInfo(report)
                                               setReportInfoVis(1)
                                           }
                                           }>
                                       <b>USER ID: </b>{report.user_id}
                                       </li>
                                    ))}
                                    <nav className="page">
                                        {pageNumbers.map(number => (
                                            <button onClick={() => paginate(number)} href='!#' key={number} className='page-item'>
                                              {number}
                                            </button>
                                        ))}
                                    </nav>
                                </div> 
                    case 1:
                        return  <div className="reportInfo">
                                    <pre>
                                        <b>user id: </b>
                                        {reportInfo.user_id}
                                    </pre>
                                    <pre>
                                        <b>message: </b> 
                                        {reportInfo.message}
                                    </pre>
                                    <button
                                        onClick={ () => setReportInfoVis(0)}>back</button>
                                    <button onClick={() => 
                                    {
                                        setReportInfoVis(2)
                                    }}>
                                        delete
                                    </button>
                                    
                                </div>
                    case 2:
                        return  <div className="popup">
                                    The user with that name: <b>{reportInfo.id}</b> will be deleted. Are you sure?
                                    <button onClick={ () => { 
                                        axios
                                        .get(`https://hodikids.com/api/reports/${reportInfo.id}/delete`) 
                                        setReportInfoVis(0)}}
                                        > Yes
                                    </button>
                                    <button onClick={ () => {setReportInfoVis(1)}}>
                                        No
                                    </button>
                                </div>
                    default:
                        return null
                    }
                })()}
            </div>
        </div>
    )
}

export default Reports