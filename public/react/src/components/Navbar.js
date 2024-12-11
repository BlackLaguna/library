import React, { useEffect, useState } from 'react';
import { Link } from 'react-router-dom';
import './Navbar.css';

const Navbar = () => {
    const [user, setUser] = useState(null);
    const token = localStorage.getItem('token');

    useEffect(() => {
        if (token) {
            const fetchUserData = async () => {
                try {
                    const response = await fetch('http://localhost:8080/api/me', {
                        headers: { 'X-TOKEN': token },
                    });
                    if (!response.ok) throw new Error('Failed to fetch user data');
                    const data = await response.json();
                    setUser(data);  // Сохраняем данные пользователя
                } catch (error) {
                    console.error('Error fetching user data:', error);
                }
            };

            fetchUserData();
        }
    }, [token]);

    return (
        <div className="navbar">
            <div className="navbar-left">
                {token && <Link to="/home" className="navbar-link">Home</Link>}  {}
            </div>
            <div className="navbar-center">
                <h1>My Book App</h1>
            </div>
            <div className="navbar-right">
                {!token ? (
                    <>
                        <Link to="/login" className="navbar-link">Login</Link>  {}
                    </>
                ) : (
                    <>
                        <span className="navbar-user">{user ? `Hello, ${user.name}` : 'Loading...'}</span>  {}
                        <Link to="/login" className="navbar-link" onClick={() => { localStorage.removeItem('token'); }}>Logout</Link>  {}
                    </>
                )}
            </div>
        </div>
    );
};

export default Navbar;
