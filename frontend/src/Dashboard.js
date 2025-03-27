import React, { useState, useEffect } from 'react';
import axios from 'axios';

const Dashboard = () => {
    const [passwords, setPasswords] = useState([]);
    const [newPassword, setNewPassword] = useState('');

    // Função para buscar as senhas do backend
    const fetchPasswords = async () => {
        try {
            const token = localStorage.getItem('token');
            const response = await axios.get('http://localhost:8000/passwords', {
                headers: { Authorization: `Bearer ${token}` },
            });
            setPasswords(response.data);
        } catch (err) {
            console.error('Erro ao buscar senhas:', err);
        }
    };

    // Função para gerar uma nova senha
    const generatePassword = async () => {
        try {
            const token = localStorage.getItem('token');
            const response = await axios.post('http://localhost:8000/generate', {}, {
                headers: { Authorization: `Bearer ${token}` },
            });
            setNewPassword(response.data.password);
            fetchPasswords(); // Atualizar lista de senhas
        } catch (err) {
            console.error('Erro ao gerar senha:', err);
        }
    };

    // Função para deslogar
    const logout = () => {
        localStorage.removeItem('token'); // Remover o token do armazenamento local
        window.location.href = '/'; // Redirecionar para a página de login
    };

    useEffect(() => {
        fetchPasswords();
    }, []);

    return (
        <div className="container mt-5">
            <h2 className="text-center mb-4">Dashboard</h2>
            <div className="text-end">
                <button onClick={logout} className="btn btn-danger mb-4">Logout</button>
            </div>
            <div className="card p-4 shadow-lg">
                <h3>Senhas:</h3>
                <ul className="list-group">
                    {passwords.map((password) => (
                        <li key={password.id} className="list-group-item">
                            {password.password} - {password.created_at}
                        </li>
                    ))}
                </ul>
                <button onClick={generatePassword} className="btn btn-primary mt-4">
                    Gerar Nova Senha
                </button>
                {newPassword && <p className="mt-3 text-success">Nova senha gerada: {newPassword}</p>}
            </div>
        </div>
    );
};

export default Dashboard;
