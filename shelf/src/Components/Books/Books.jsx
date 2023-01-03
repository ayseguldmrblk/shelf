import React from 'react'
import { useEffect } from 'react';
import { useState } from 'react'
import './Books.css'
import axios from "axios";

const Books = () => {

    const [books , setBooks] = useState([]);
    const classes = {
        'container':true,
        "BookList":true,
    };

    const [bookInfo , setBookInfo] = useState([]);
    const [bookInfoVis , setBookInfoVis] = useState(0);

    useEffect( () => {
        axios
        .get('https://hodikids.com/api/books')
        .then((res) =>
        setBooks(res.data));
    },[])

    return (
        <div>
            <h1 className="header">BOOKS</h1>

            <div className="Books">
                {(() => {
                    switch (bookInfoVis) {
                    case 0:
                        return  <ul className={classes}>
                                    {books.map((book) => (
                                        <li className="BooksListElement" key={book.id}
                                            onClick = { () =>
                                            {
                                                setBookInfo(book)
                                                setBookInfoVis(1)
                                            }
                                            }>
                                            {book.name}
                                        </li>
                                    ))}
                                </ul>
                    case 1:
                        return  <div className="bookInfo">
                                    <button className="buttonVis"
                                        onClick={ () => setBookInfoVis(0)}>EXIT</button>
                                    <pre>
                                        bookname:
                                        {bookInfo.name}
                                    </pre>
                                    <pre>
                                        page count:
                                        {bookInfo.page_count}
                                    </pre>
                                    <pre>
                                        donator:
                                        {bookInfo.donator}
                                    </pre>
                                    <pre>
                                        author:
                                        {bookInfo.author}
                                    </pre>
                                    <pre>
                                        category:
                                        {bookInfo.category}
                                    </pre>
                                    <pre>
                                        image:
                                        <img style={{
                                            resizeMode: "contain",
                                            height: 100,
                                            width: 200
                                            }}
                                             src={'https://hodikids.com'+bookInfo.image}
                                        />
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

export default Books
