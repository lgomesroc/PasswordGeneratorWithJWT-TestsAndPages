import React, { useState } from 'react';
import axios from 'axios';

const Login = () => {
    const [formData, setFormData] = useState({ username: '', password: '' });
    const [error, setError] = useState('');

    // Função para capturar mudanças nos campos
    const handleChange = (e) => {
        setFormData({ ...formData, [e.target.name]: e.target.value });
    };

    // Função para enviar os dados de login
    const handleSubmit = async (e) => {
        e.preventDefault();
        try {
            const response = await axios.post('http://localhost:8000/login', formData);
            localStorage.setItem('token', response.data.token); // Armazenar token JWT
            window.location.href = '/dashboard'; // Redirecionar para o dashboard
        } catch (err) {
            const errorMessage =
                err.response?.status === 401
                    ? 'Usuário ou senha inválidos. Tente novamente.'
                    : 'Erro ao conectar ao servidor. Verifique a conexão.';
            setError(errorMessage); // Exibir mensagem de erro
        }
    };

    return (
        <div style={{ maxWidth: '300px', margin: 'auto', textAlign: 'center' }}>
            <h2>Login</h2>
            {error && <p style={{ color: 'red' }}>{error}</p>}
            <form onSubmit={handleSubmit}>
                <input
                    type="text"
                    name="username"
                    placeholder="Usuário"
                    value={formData.username}
                    onChange={handleChange}
                    required
                    style={{ marginBottom: '10px', width: '100%' }}
                />
                <input
                    type="password"
                    name="password"
                    placeholder="Senha"
                    value={formData.password}
                    onChange={handleChange}
                    required
                    style={{ marginBottom: '20px', width: '100%' }}
                />
                <button type="submit" style={{ marginBottom: '10px', width: '100%' }}>
                    Entrar
                </button>
            </form>
            {/* Botão de redirecionamento para a página de registro */}
            <button
                onClick={() => (window.location.href = '/register')}
                style={{ marginTop: '10px', backgroundColor: '#4caf50', color: 'white', width: '100%' }}
            >
                Cadastrar Usuário
            </button>
        </div>
    );
};

export default Login;
