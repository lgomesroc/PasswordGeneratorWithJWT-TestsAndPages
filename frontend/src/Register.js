import React, { useState } from 'react';

const Register = () => {
    const [formData, setFormData] = useState({ username: '', password: '' });
    const [successMessage, setSuccessMessage] = useState('');
    const [error, setError] = useState('');

    const handleChange = (e) => {
        setFormData({ ...formData, [e.target.name]: e.target.value });
    };

    const handleSubmit = async (e) => {
        e.preventDefault();
        try {
            const response = await fetch('http://localhost:8000/register', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(formData),
            });
            if (!response.ok) throw new Error('Erro ao cadastrar usuário. Verifique os dados.');
            setSuccessMessage('Usuário cadastrado com sucesso!');
            setError('');
        } catch (err) {
            setError(err.message);
            setSuccessMessage('');
        }
    };

    return (
        <div className="container d-flex justify-content-center align-items-center vh-100">
            <div className="card p-4 shadow-lg" style={{ maxWidth: '400px', width: '100%' }}>
                <h2 className="text-center mb-4">Cadastrar Usuário</h2>
                {successMessage && <div className="alert alert-success">{successMessage}</div>}
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
                    <button type="submit" className="btn btn-success w-100">Cadastrar</button>
                </form>
                <button
                    onClick={() => (window.location.href = '/')}
                    className="btn btn-link w-100 mt-3"
                >
                    Voltar ao Login
                </button>
            </div>
        </div>
    );
};

export default Register;
