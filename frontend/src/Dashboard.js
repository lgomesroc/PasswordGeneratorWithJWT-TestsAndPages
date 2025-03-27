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
        <div style={{ maxWidth: '600px', margin: 'auto', textAlign: 'center' }}>
            <h2>Dashboard</h2>
            <div>
                <h3>Senhas:</h3>
                <ul>
                    {passwords.map((password) => (
                        <li key={password.id}>
                            {password.password} - {password.created_at}
                        </li>
                    ))}
                </ul>
                <button onClick={generatePassword}>Gerar Nova Senha</button>
                {newPassword && <p>Nova senha gerada: {newPassword}</p>}
                {/* Botão de deslogar */}
                <button onClick={logout} style={{ marginTop: '20px', backgroundColor: 'red', color: 'white' }}>
                    Deslogar
                </button>
            </div>
        </div>
    );
};

export default Dashboard;
