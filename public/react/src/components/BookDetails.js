import React, { useEffect, useState } from 'react';
import { useParams } from 'react-router-dom';
import './BookDetails.css';

const BookDetails = () => {
    const { id } = useParams();
    const [book, setBook] = useState(null);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);
    const [reservationDates, setReservationDates] = useState({ reserveFrom: '', reserveTo: '' });

    const token = localStorage.getItem('token');

    const fetchBookDetails = async () => {
        setLoading(true);
        try {
            const response = await fetch(`http://localhost:8080/api/books/${id}`, {
                headers: { 'X-TOKEN': `${token}` },
            });
            if (!response.ok) throw new Error('Failed to fetch book details');
            const data = await response.json();
            setBook(data);
        } catch (error) {
            setError(error.message);
        } finally {
            setLoading(false);
        }
    };

    useEffect(() => {
        fetchBookDetails();
    }, [id, token]);

    const handleReservation = async () => {
        try {
            const response = await fetch(`http://localhost:8080/api/books/${id}/reserve`, {
                method: 'POST',
                headers: {
                    'X-TOKEN': `${token}`,
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    reserveFrom: new Date(reservationDates.reserveFrom).getTime() / 1000,
                    reserveTo: new Date(reservationDates.reserveTo).getTime() / 1000,
                }),
            });
            if (!response.ok) throw new Error('Failed to reserve book');
            alert('Book reserved successfully!');
            setReservationDates({ reserveFrom: '', reserveTo: '' });
            await fetchBookDetails();
        } catch (error) {
            alert(error.message);
        }
    };

    const handleCheckout = async () => {
        try {
            const response = await fetch(`http://localhost:8080/api/books/${id}/checkout`, {
                method: 'POST',
                headers: {
                    'X-TOKEN': `${token}`,
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({}),
            });
            if (!response.ok) throw new Error('Failed to checkout book');
            alert('Book checked out successfully!');
            await fetchBookDetails();
        } catch (error) {
            alert(error.message);
        }
    };

    const handleReturn = async () => {
        try {
            const response = await fetch(`http://localhost:8080/api/books/${id}/return`, {
                method: 'POST',
                headers: {
                    'X-TOKEN': `${token}`,
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({}),
            });
            if (!response.ok) throw new Error('Failed to return book');
            alert('Book returned successfully!');
            await fetchBookDetails();
        } catch (error) {
            alert(error.message);
        }
    };

    const formatDate = (dateString) => {
        const options = { year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' };
        return new Date(dateString).toLocaleDateString(undefined, options);
    };

    const isOverdue = (dateTo) => {
        return new Date(dateTo) < new Date();
    };

    const handleInputChange = (e) => {
        const { name, value } = e.target;
        setReservationDates((prevState) => ({ ...prevState, [name]: value }));
    };

    if (loading) return <p>Loading...</p>;
    if (error) return <p>Error: {error}</p>;

    const finishedReservations = Object.values(book.reservations || {}).filter(
        (reservation) => reservation.status === 'FINISHED'
    );

    const activeReservations = Object.values(book.reservations || {}).filter(
        (reservation) => ['NEW', 'ACTIVE'].includes(reservation.status)
    );

    return (
        <div className="book-details">
            <h1>{book.name}</h1>
            <h2>Finished Reservations</h2>
            <div className="reservations">
                {finishedReservations.map((res) => (
                    <div key={res["@id"]} className="reservation-card">
                        <p><strong>Status:</strong> {res.status}</p>
                        <p><strong>From:</strong> {formatDate(res.dateFrom)}</p>
                        <p><strong>To:</strong> {formatDate(res.dateTo)}</p>
                    </div>
                ))}
            </div>
            <h2>Active Reservations</h2>
            <div className="reservations">
                {activeReservations.map((res) => (
                    <div
                        key={res["@id"]}
                        className={`reservation-card ${isOverdue(res.dateTo) ? 'overdue' : ''}`}
                    >
                        <p><strong>Status:</strong> {res.status}</p>
                        <p><strong>From:</strong> {formatDate(res.dateFrom)}</p>
                        <p><strong>To:</strong> {formatDate(res.dateTo)}</p>
                    </div>
                ))}
            </div>
            <h2>Make a Reservation</h2>
            <form onSubmit={(e) => { e.preventDefault(); handleReservation(); }}>
                <label>
                    From:
                    <input
                        type="datetime-local"
                        name="reserveFrom"
                        value={reservationDates.reserveFrom}
                        onChange={handleInputChange}
                        required
                    />
                </label>
                <label>
                    To:
                    <input
                        type="datetime-local"
                        name="reserveTo"
                        value={reservationDates.reserveTo}
                        onChange={handleInputChange}
                        required
                    />
                </label>
                <button type="submit">Reserve</button>
            </form>
            <button onClick={handleCheckout}>Checkout</button>
            <button onClick={handleReturn}>Return</button>
        </div>
    );
};

export default BookDetails;
