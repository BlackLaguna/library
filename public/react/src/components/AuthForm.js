import React, { useState } from 'react';
import { useNavigate } from 'react-router-dom';

const AuthForm = () => {
    const [isRegistering, setIsRegistering] = useState(false); // Для переключения между режимами
    const [email, setEmail] = useState('');
    const [password, setPassword] = useState('');
    const [repeatedPassword, setRepeatedPassword] = useState('');
    const [name, setName] = useState('');
    const navigate = useNavigate();

    const handleAuth = async (e) => {
        e.preventDefault();
        const url = isRegistering ? 'http://localhost:8080/api/register' : 'http://localhost:8080/api/login';
        const body = isRegistering
            ? JSON.stringify({ email, password, repeatedPassword, name })
            : JSON.stringify({ email, password });

        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: body,
            });

            if (!response.ok) throw new Error(isRegistering ? 'Registration failed' : 'Login failed');
            const data = await response.json();
            localStorage.setItem('token', data.token);
            navigate('/home');
        } catch (error) {
            console.error('Error:', error);
            alert(isRegistering ? 'Registration failed' : 'Login failed');
        }
    };

    return (
        <form onSubmit={handleAuth} className="form-container">
            <h2>{isRegistering ? 'Register' : 'Login'}</h2>

            <input
                type="email"
                placeholder="Email"
                value={email}
                onChange={(e) => setEmail(e.target.value)}
                required
            />
            <input
                type="password"
                placeholder="Password"
                value={password}
                onChange={(e) => setPassword(e.target.value)}
                required
            />

            {isRegistering && (
                <>
                    <input
                        type="password"
                        placeholder="Repeat Password"
                        value={repeatedPassword}
                        onChange={(e) => setRepeatedPassword(e.target.value)}
                        required
                    />
                    <input
                        type="text"
                        placeholder="Name"
                        value={name}
                        onChange={(e) => setName(e.target.value)}
                        required
                    />
                </>
            )}

            <button type="submit">{isRegistering ? 'Register' : 'Login'}</button>

            <p onClick={() => setIsRegistering(!isRegistering)} style={{ cursor: 'pointer', textAlign: 'center', color: '#4caf50' }}>
                {isRegistering ? 'Already have an account? Login' : 'Don\'t have an account? Register'}
            </p>
        </form>
    );
};

export default AuthForm;
