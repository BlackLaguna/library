import React, { useEffect, useState } from 'react';
import { useNavigate } from 'react-router-dom';
import './Home.css';

const Home = () => {
    const [books, setBooks] = useState([]);
    const [loading, setLoading] = useState(true);
    const [page, setPage] = useState(1);
    const [totalPages, setTotalPages] = useState(1);
    const navigate = useNavigate();

    useEffect(() => {
        const fetchBooks = async () => {
            setLoading(true);
            const token = localStorage.getItem('token');
            try {
                const response = await fetch(`http://localhost:8080/api/books?itemsPerPage=5&page=${page}`, {
                    headers: { 'X-TOKEN': `${token}` },
                });
                if (!response.ok) throw new Error('Failed to fetch books');
                const data = await response.json();
                setBooks(data.member);
                setTotalPages(Math.ceil(data.totalItems / 5));
            } catch (error) {
                console.error('Error:', error);
            } finally {
                setLoading(false);
            }
        };
        fetchBooks();
    }, [page]);

    const handlePrevious = () => {
        if (page > 1) setPage(page - 1);
    };

    const handleNext = () => {
        if (page < totalPages) setPage(page + 1);
    };

    return (
        <div>
            <h1>Book Catalog</h1>
            {loading ? (
                <p>Loading...</p>
            ) : (
                <div className="book-grid">
                    {books.map((book) => (
                        <div
                            key={book.id}
                            className="book-card"
                            onClick={() => navigate(`/book/${book.id}`)}
                        >
                            <h3>{book.authorFirstName} {book.authorLastName}</h3>
                            <p>{book.description}</p>
                            <p>Available: {book.availableQuantity}</p>
                        </div>
                    ))}
                </div>
            )}
            <div className="pagination">
                <button onClick={handlePrevious} disabled={page === 1}>Previous</button>
                <span> Page {page} of {totalPages} </span>
                <button onClick={handleNext} disabled={page === totalPages}>Next</button>
            </div>
        </div>
    );
};

export default Home;