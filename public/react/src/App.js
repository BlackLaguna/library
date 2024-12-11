import React from 'react';
import './App.css';
import { BrowserRouter as Router, Routes, Route, Navigate } from 'react-router-dom';
import AuthForm from './components/AuthForm';
import Home from './components/Home';
import BookDetails from './components/BookDetails';
import Navbar from './components/Navbar';

const App = () => {
    const token = localStorage.getItem('token');

    return (
        <Router>
            <Navbar />  {}
            <Routes>
                {!token ? (
                    <>
                        <Route path="/login" element={<AuthForm />} />
                        <Route path="/register" element={<AuthForm />} />
                        <Route path="*" element={<Navigate to="/login" />} />  {}
                    </>
                ) : (
                    <>
                        <Route path="/home" element={<Home />} />
                        <Route path="/book/:id" element={<PrivateRoute component={BookDetails} />} />
                        <Route path="*" element={<Navigate to="/home" />} />  {}
                    </>
                )}
            </Routes>
        </Router>
    );
};

const PrivateRoute = ({ component: Component }) => {
    const token = localStorage.getItem('token');
    return token ? <Component /> : <Navigate to="/login" />;
};

export default App;
