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
        <div className="container d-flex justify-content-center align-items-center vh-100">
            <div className="card p-4 shadow-lg" style={{ maxWidth: '400px', width: '100%' }}>
                <h2 className="text-center mb-4">Login</h2>
                {error && <div className="alert alert-danger">{error}</div>}
                <form onSubmit={handleSubmit}>
                    <div className="mb-3">
                        <label className="form-label">Usuário</label>
                        <input
                            type="text"
                            name="username"
                            className="form-control"
                            placeholder="Digite seu usuário"
                            value={formData.username}
                            onChange={handleChange}
                            required
                        />
                    </div>
                    <div className="mb-3">
                        <label className="form-label">Senha</label>
                        <input
                            type="password"
                            name="password"
                            className="form-control"
                            placeholder="Digite sua senha"
                            value={formData.password}
                            onChange={handleChange}
                            required
                        />
                    </div>
                    <button type="submit" className="btn btn-primary w-100">Entrar</button>
                </form>
                <button
                    onClick={() => (window.location.href = '/register')}
                    className="btn btn-link w-100 mt-3"
                >
                    Cadastrar Usuário
                </button>
            </div>
        </div>
    );
};

export default Login;
